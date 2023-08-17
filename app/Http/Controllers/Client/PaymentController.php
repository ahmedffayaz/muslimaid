<?php

namespace App\Http\Controllers\Client;

use App\Models\Store;
use App\Models\Cashout;
use App\Models\Charity;
use App\Models\PaymentInfo;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use App\Jobs\SendEmailToUser;
use App\Jobs\SendEmailToAdmin;
use App\Jobs\SendNotification;
use App\Http\Controllers\Controller;
use App\Models\CashbackStatusChange;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    function __construct()
    {
        $this->middleware('is_charity_module_access', ['only' => ['CharityCashout']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stores = Store::paginate('10');
        $usercashback = UserCashback::where('status', '3')->get();
        $term = null;
        if (getImporterYMLSettings(config('app.charity_yaml_path'))) {
            $charities = Charity::latest()->get();
            return view('frontend.client-dashboard.withdraw', compact('stores', 'term', 'charities', 'usercashback'));
        }
        return view('frontend.client-dashboard.withdraw', compact('stores', 'term', 'usercashback'));
    }

    public function paymentDetails()
    {
        return view('frontend.client-dashboard.payment_details');
    }

    public function paymentSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
            'account_name' => [
                Rule::requiredIf(function () use ($request){
                    return $request->payment_method === "bank";
                }),
                'nullable', 'string'
            ],
            'account_number_hidden' =>  [ 
                Rule::requiredIf(function () use ($request){
                    return $request->payment_method === "bank";
                }),
                'nullable', 'regex:/^[0-9]+$/', 'size:8'
            ],
            'bank_sort_code_hidden' => [
                Rule::requiredIf(function () use ($request){
                    return $request->payment_method === "bank";
                }),
                'nullable', 'regex:/^[0-9]+$/', 'size:6'
            ],
            'paypal_email' => [
                Rule::requiredIf(function () use ($request){
                    return $request->payment_method === "paypal";
                }),
                'nullable', 'email'
            ]
        ]);
        if($request->payment_method === "bank"){
            $validator->setAttributeNames([
                'account_number_hidden' => 'Account Number',
                'bank_sort_code_hidden' => 'Sort Code'
            ]);
        }
        if($validator->fails()){
            flash()->error($validator->errors()->first());
            return redirect()->back();
        }

        $payment = PaymentInfo::updateOrCreate([
            'user_id'   => Auth::user()->id,
            'payment_method'   => $request->payment_method,
        ], [
            'account_name' => $request->input('account_name'),
            'bank_title' => $request->input('bank_title'),
            'account_number' => $request->input('account_number_hidden'),
            'bank_sort_code' => $request->input('bank_sort_code_hidden'),
            'bic' => $request->input('bic'),
            'paypal_email' => $request->input('paypal_email')
        ]);

        flash()->success('Payment method updated successfully');
        return redirect()->back();
    }

    public function cashout(Request $request)
    {
        $user = Auth::user();
        $errorMessage = 0;
        if (!($user->first_name && $user->last_name && $user->email && $user->phone && $user->address && $user->date_of_birth && $user->street && $user->country_id && $user->postal_code)) {
            $errorMessage = 1;
            flash()->error('Please first complete your profile to withdraw');
        }
        $previousCashouts = $user->cashouts()->where('status', 'paid')->count();
        if (isset(SiteSetting()['min_cashout_amount']) && $previousCashouts == 0) {
            $min = SiteSetting()['min_cashout_amount'];
        } else if (isset(SiteSetting()['next_cashout_amount']) && $previousCashouts > 0) {
            $min = SiteSetting()['next_cashout_amount'];
        } else if ($previousCashouts == 0) {
            $min = 1;
        } else {
            $min = 2;
        }
        $method = $user->paymentInfo()->where('payment_method', $request->payment_method)->first();

        if (!$method && $request->payment_method != 'charity') {
            flash()->error('Payment method not found, please add your payment method information', $lifetime = 600);
            return redirect()->back();
        }

        $balance = $user->availableBalance(3);
        $cashbacks = $user->balance;

        if ($balance < $min) {
            flash()->error("You have insufficient balance for withdrawl. You need to have at least $min in your balance for withdrawal.");
            return redirect()->back();
        }
        if ($errorMessage == 1) {
            return redirect()->back();
        }
        $cashout = Cashout::create([
            'user_id' => $user->id,
            'amount' => $balance,
            'cashout_type' => $method->payment_method,
            'paypal_email' => $method->paypal_email,
            'address' => $method->address,
            'city' => $method->city,
            'postcode' => $method->postcode,
            'country' => $method->country,
            'account_name' => $method->account_name,
            'bank_title' => $method->bank_title,
            'account_number' => $method->account_number,
            'bank_sort_code' => $method->bank_sort_code,
            'new_cashout' => '1',
            'bic' => $method->bic,
            'payment_method' => $method->payment_method,
            'status' => 'pending'
        ]);

        foreach ($cashbacks as $cashback) {
            $cashback->update(['status' => 5, 'cashout_id' => $cashout->id]);
            $change_status = CashbackStatusChange::create([
                'user_cashback_id' => $cashback->id,
                'cashback_status_id' => $cashback->status
            ]);
        }

        if ($user->bonus && $user->bonus->status == 'unpaid') {
            $user->bonus->update([
                'status' => 'paid',
                'cashout_id' => $cashout->id
            ]);
        }

        $userEmailTemplateKey = 'user_new_cashout_request';
        $adminEmailTemplateKey = 'admin_new_cashout_request';
        $filterMessageVariables = ['{{AMOUNT}}', '{{METHOD}}'];
        $requestFilteredMessage = [$cashout->amount, $cashout->payment_method];

        $data = [
            'name' => $cashout->user->first_name . ' ' . $cashout->user->last_name,
            'email' => $cashout->user->email,
            'subject' => null,
            'message' => null
        ];

        SendEmailToUser::dispatch($userEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);
        SendEmailToAdmin::dispatch($adminEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);

        $title = 'Cashout Request Completion';
        $message = 'Your cashout request has been completed against ' . $request->payment_method;
        $url = url('account/withdraw');
        $deviceToken = optional(auth()->user()->devices()->whereType('web')->first())->fcm_token;

        $deviceToken != null ? dispatch(new SendNotification($title, $message, $deviceToken, $url, $user)) : '';
        flash()->success("We're processing your withdrawal. Please allow 4 working days for " . $balance . " to reach your " . $request->payment_method . " account.");
        return redirect()->back();
    }

    public function CharityCashout(Request $request, Cashout $cashout)
    {
        $user = Auth::user();
        $errorMessage = 0;
        if (!($user->first_name && $user->last_name && $user->email && $user->phone && $user->address && $user->date_of_birth && $user->street && $user->country_id && $user->postal_code)) {
            $errorMessage = 1;
            flash()->error('Please first complete your profile to withdraw');
        }
        $previousCashouts = $user->cashouts()->where('status', 'paid')->count();
        if (isset(SiteSetting()['min_cashout_amount']) && $previousCashouts == 0) {
            $min = SiteSetting()['min_cashout_amount'];
        } else if (isset(SiteSetting()['next_cashout_amount']) && $previousCashouts > 0) {
            $min = SiteSetting()['next_cashout_amount'];
        } else if ($previousCashouts == 0) {
            $min = 1;
        } else {
            $min = 2;
        }

        $cashout_status = $user->cashouts()->where('status', '=', 'pending')->first();
        $balance_old = $user->availableBalance(3);
        $balance = ($balance_old - $request->amount);

        if ($balance_old < $min) {
            flash()->error("You have insufficient balance for withdrawl. You need to have at least $min in your balance for withdrawal.");
            return redirect()->back();
        }

        if ($cashout_status == 'pending') {
            flash()->error('You have already pending withdraw request.');
            return redirect()->back();
        }
        
        if ($errorMessage == 1) {
            return redirect()->back();
        }
        
        $validator = Validator::make($request->all(), [
            'charity_types_id' => 'required',
            'id' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $amount = 0;
        //To decrypt and fetch the cashback amounts
        foreach($request->id as $encryptedId){
            $decryptedId = decrypt($encryptedId);
            $amount = $amount + UserCashback::where('id', $decryptedId)->first()->amount;
        }

        $cashout = Cashout::create([
            'user_id' => $user->id,
            'charity_types_id' => $request->charity_types_id,
            'cashout_type' => $request->payment_method,
            'amount' => $amount,
            'new_cashout' => '1',
            'payment_method' => $request->payment_method,
            'status' => 'processing donation'
        ]);

        foreach($request->id as $encryptedId){
            $decryptedId = decrypt($encryptedId);
            $cashback = $user->cashbacks()->where('id', $decryptedId)->first();
            $cashback->update(['status' => 5, 'cashout_id' => $cashout->id]);
            $cashback->statusHistory()->create([
                'cashback_status_id' => $cashback->status,
                'user_cashback_id' => $cashback->id
            ]);
        }

        if ($user->bonus && $user->bonus->status == 'unpaid') {
            $user->bonus->update([
                'status' => 'paid',
                'cashout_id' => $cashout->id
            ]);
        }

        $userEmailTemplateKey = 'user_new_cashout_request';
        $adminEmailTemplateKey = 'admin_new_cashout_request';
        $filterMessageVariables = ['{{AMOUNT}}', '{{METHOD}}'];
        $requestFilteredMessage = [$cashout->amount, $cashout->payment_method];

        $data = [
            'name' => $cashout->user->first_name . ' ' . $cashout->user->last_name,
            'email' => $cashout->user->email,
            'subject' => null,
            'message' => null
        ];

        SendEmailToUser::dispatch($userEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);
        SendEmailToAdmin::dispatch($adminEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);
        $title = 'Cashout Request Completion';
        $message = 'Your cashout request has been completed against ' . $request->payment_method;
        $deviceToken = optional(auth()->user()->devices()->whereType('web')->first())->fcm_token;
        $url = url('account/withdraw');

        $deviceToken != null ? dispatch(new SendNotification($title, $message, $deviceToken, $url, $user)) : '';
        flash()->success("We're processing your withdrawal. Please allow 4 working days for " . $request->amount . " to reach your " . $request->payment_method . " account.");
        return redirect()->back();
    }
}
                      