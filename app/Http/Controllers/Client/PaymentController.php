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
use App\Models\CashoutMeta;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    function __construct()
    {
        $this->middleware('is_charity_module_access', ['only' => ['CharityCashout']]);
        $this->middleware('is_cashout_module_access', ['only' => ['index', 'paymentDetails', 'paymentSave', 'cashout']]);
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
            'account_number' =>  [
                Rule::requiredIf(function () use ($request){
                    return $request->payment_method === "bank";
                }),
                'nullable', 'regex:/^[0-9]+$/', 'size:8'
            ],
            'bank_sort_code' => [
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
                'account_number' => 'Account Number',
                'bank_sort_code' => 'Sort Code'
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
            'account_number' => $request->input('account_number'),
            'bank_sort_code' => $request->input('bank_sort_code'),
            'bic' => $request->input('bic'),
            'paypal_email' => $request->input('paypal_email')
        ]);

        flash()->success('Payment method updated successfully');
        return redirect()->back();
    }

    public function cashout(Request $request)
    {
        try {
            $user = Auth::user();
            if (!($user->first_name && $user->last_name && $user->email && $user->phone && $user->address && $user->date_of_birth && $user->street && $user->country_id && $user->postal_code)) {
                flash()->error('Please first complete your profile to withdraw');
                return redirect()->back();
            }

            $previousCashouts = $user->cashouts()->where('status', 'paid')->count();

            if (isset(SiteSetting()['min_cashout_amount']) && $previousCashouts == 0)
                $min = SiteSetting()['min_cashout_amount'];
            else if (isset(SiteSetting()['next_cashout_amount']) && $previousCashouts > 0)
                $min = SiteSetting()['next_cashout_amount'];
            else if ($previousCashouts == 0) $min = 1;
            else $min = 2;

            $method = $user->paymentInfo()->where('payment_method', $request->payment_method)->first();

            if (!$method && $request->payment_method != 'charity') {
                flash()->error('Payment method not found, please add your payment method information', $lifetime = 600);
                return redirect()->back();
            }

            $balance = $user->availableBalance(3);
            $cashbacks = $user->balance;

            if ($balance < $min) {
                flash()->error("You have insufficient balance for withdrawal. You need to have at least $min in your balance for withdrawal.");
                return redirect()->back();
            }

            DB::beginTransaction();
            $cashout = Cashout::create([
                'user_id' => $user->id,
                'amount' => $balance,
                'new_cashout' => '1',
                'status' => 'pending',
                'payment_method' => $method->payment_method,
            ]);

            if ($cashout->payment_method === 'bank') {
                CashoutMeta::create([
                    'cashout_id' => $cashout->id,
                    'type' => 'bank_title',
                    'value' => $method->bank_title
                ]);

                CashoutMeta::create([
                    'cashout_id' => $cashout->id,
                    'type' => 'account_name',
                    'value' => $method->account_name
                ]);

                CashoutMeta::create([
                    'cashout_id' => $cashout->id,
                    'type' => 'account_number',
                    'value' => $method->account_number
                ]);

                CashoutMeta::create([
                    'cashout_id' => $cashout->id,
                    'type' => 'bank_sort_code',
                    'value' => $method->bank_sort_code
                ]);

                CashoutMeta::create([
                    'cashout_id' => $cashout->id,
                    'type' => 'bic',
                    'value' => $method->bic
                ]);

                CashoutMeta::create([
                    'cashout_id' => $cashout->id,
                    'type' => 'address',
                    'value' => $method->address
                ]);

                CashoutMeta::create([
                    'cashout_id' => $cashout->id,
                    'type' => 'city',
                    'value' => $method->city
                ]);

                CashoutMeta::create([
                    'cashout_id' => $cashout->id,
                    'type' => 'postcode',
                    'value' => $method->postcode
                ]);

                CashoutMeta::create([
                    'cashout_id' => $cashout->id,
                    'type' => 'country',
                    'value' => $method->country
                ]);
            }

            if ($cashout->payment_method === 'paypal') {
                CashoutMeta::create([
                    'cashout_id' => $cashout->id,
                    'type' => 'paypal_email',
                    'value' => $method->paypal_email
                ]);
            }

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

            DB::commit();

            // Send email to user and admin
            $this->sendEmail($cashout);

            // Send push notification
            $deviceToken = optional(auth()->user()->devices()->whereType('web')->latest()->first())->fcm_token;
            $deviceToken != null ? $this->sendNotification($cashout, $deviceToken, $user) :'';

            flash()->success("We're processing your withdrawal. Please allow 4 working days for £" . $balance . " to reach your " . $request->payment_method . " account.");
            return redirect()->back();
        } catch (Exception $e) {
            flash()->error("Something went wrong, try again later.");
            return redirect()->back();
        }
    }

    public function CharityCashout(Request $request, Cashout $cashout)
    {
        try {
            $user = Auth::user();
            if (!($user->first_name && $user->last_name && $user->email && $user->phone && $user->address && $user->date_of_birth && $user->street && $user->country_id && $user->postal_code)) {
                flash()->error('Please first complete your profile to withdraw');
                return redirect()->back();
            }
            $previousCashouts = $user->cashouts()->where('status', 'paid')->count();

            if (isset(SiteSetting()['min_cashout_amount']) && $previousCashouts == 0)
                $min = SiteSetting()['min_cashout_amount'];
            else if (isset(SiteSetting()['next_cashout_amount']) && $previousCashouts > 0)
                $min = SiteSetting()['next_cashout_amount'];
            else if ($previousCashouts == 0) $min = 1;
            else $min = 2;

            $cashout_status = $user->cashouts()->where('status', '=', 'pending')->first();
            $balance_old = $user->availableBalance(3);
            $balance = ($balance_old - $request->amount);

            if ($balance_old < $min) {
                flash()->error("You have insufficient balance for withdrawal. You need to have at least $min in your balance for withdrawal.");
                return redirect()->back();
            }

            if ($cashout_status == 'pending') {
                flash()->error('You have already pending withdraw request.');
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

            DB::beginTransaction();

            $cashout = Cashout::create([
                'user_id' => $user->id,
                'charity_types_id' => $request->charity_types_id,
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

            DB::commit();

            // Send email to user and admin
            $this->sendEmail($cashout);

            // Send push notification
            $deviceToken = optional(auth()->user()->devices()->whereType('web')->first())->fcm_token;
            $deviceToken != null ? $this->sendNotification($request, $deviceToken, $user) : '';

            flash()->success("We're processing your withdrawal. Please allow 4 working days for £" . $request->amount . " to reach your " . $request->payment_method . " account.");
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            flash()->error('Something went wrong, try again later.');
            return redirect()->back();
        }
    }

    function sendEmail($cashout)
    {
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
    }

    function sendNotification($request, $deviceToken, $user)
    {
        $title = ' Cashout Requested';
        $message = 'Your Cashout Request has been submitted through ' . $request->payment_method;
        $url = url('account/withdraw');

        dispatch(new SendNotification($title, $message, $deviceToken, $url, $user));
    }

    public function donateAppeal(Request $request)
    {
        $request->validate([
            'appeal_id' => 'nullable|integer'
        ], [
            'appeal_id.integer' => 'Appeal must be integer'
        ]);

        try {
            $user = Auth::user();
            if (!($user->is_email_verified)) {
                if ($request->ajax()) {
                    return response()->json([
                        'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                        'error' => 'Please first verify your email address'
                    ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
                }

                flash()->error('Please first verify your email address');
                return redirect()->back();
            }
            $previousCashouts = $user->cashouts()->where('status', 'paid')->count();

            if (isset(SiteSetting()['min_cashout_amount']) && $previousCashouts == 0)
                $min = SiteSetting()['min_cashout_amount'];
            else if (isset(SiteSetting()['next_cashout_amount']) && $previousCashouts > 0)
                $min = SiteSetting()['next_cashout_amount'];
            else if ($previousCashouts == 0) $min = 1;
            else $min = 2;

            $userCashout = $user->cashouts()->where('status', '=', 'pending')
                                ->orWhere('status', '=', 'processing')
                                ->orWhere('status', '=', 'processing donation')
                                ->first();

            $oldBalance = $user->availableBalance(3);
            $balance = ($oldBalance - $request->amount);

            if ($oldBalance < $min) {
                if ($request->ajax()) {
                    return response()->json([
                        'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                        'error' => "You have insufficient balance for donation. You need to have at least $min in your balance for donate."
                    ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
                }

                flash()->error("You have insufficient balance for donation. You need to have at least $min in your balance for donate.");
                return redirect()->back();
            }

            if (isset($userCashout) && ($userCashout->status == 'pending' || $userCashout->status == 'processing' || $userCashout->status == 'processing donation')) {
                if ($request->ajax()) {
                    return response()->json([
                        'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                        'error' => 'You have already pending request.'
                    ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
                }

                flash()->error('You have already pending request.');
                return redirect()->back();
            }

            //To decrypt and fetch the cashback amounts
            $decryptedId = decrypt($request->cashback_id);
            $amount = UserCashback::where('id', $decryptedId)->first()->amount;

            DB::beginTransaction();

            $cashout = Cashout::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'new_cashout' => '1',
                'payment_method' => 'appeal',
                'status' => 'processing donation'
            ]);

            $appealId = auth()->user()->appeal ? auth()->user()->appeal->id : $request->appeal_id;

            if ($appealId) {
                CashoutMeta::create([
                    'cashout_id' => $cashout->id,
                    'type' => 'appeal_id',
                    'value' => $appealId
                ]);
            }

            $cashback = $user->cashbacks()->where('id', $decryptedId)->first();
            $cashback->update(['status' => 5, 'cashout_id' => $cashout->id]);
            $cashback->statusHistory()->create([
                'cashback_status_id' => $cashback->status,
                'user_cashback_id' => $cashback->id
            ]);

            if ($user->bonus && $user->bonus->status == 'unpaid') {
                $user->bonus->update([
                    'status' => 'paid',
                    'cashout_id' => $cashout->id
                ]);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'status' => JsonResponse::HTTP_OK,
                    'success' => "We're processing your donation. Please allow 4 working days for £" . $amount
                ], JsonResponse::HTTP_OK);
            }

            flash()->success("We're processing your donation. Please allow 4 working days for £" . $amount);
            return redirect()->back();

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $e->getMessage() . ' Something went wrong.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);

            flash()->error('Something went wrong.');
            return redirect()->back();
        }
    }
}
