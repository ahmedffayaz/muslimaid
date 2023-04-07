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
use App\Http\Controllers\Controller;
use App\Models\CashbackStatusChange;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $charities = Charity::latest()->get();
        $stores = Store::paginate('10');
        $usercashback = UserCashback::where('status', '3')->get();
        $term = null;
        return view('frontend.client-dashboard.withdraw', compact('stores', 'term', 'charities', 'usercashback'));
    }

    public function paymentDetails()
    {
        return view('frontend.client-dashboard.payment_details');
    }

    public function paymentSave(Request $request)
    {
        $payment = PaymentInfo::updateOrCreate([
            'user_id'   => Auth::user()->id,
            'payment_method'   => $request->payment_method,
        ], $request->all());

        flash()->success('Payment method updated successfully');
        return redirect()->back();
    }

    public function cashout(Request $request)
    {
        $user = Auth::user();
        $previousCashouts = $user->cashouts()->where('status', 'paid')->count();
        if (isset(SiteSetting()['min_cashout_amount']) && $previousCashouts == 0) {
            $min = SiteSetting()['min_cashout_amount'];
        } else if (isset(SiteSetting()['next_cashout_amount']) && $previousCashouts > 0) {
            $min = SiteSetting()['next_cashout_amount'];
        } else if($previousCashouts == 0) {
            $min = 1;
        }else{
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

        flash()->success("We're processing your withdrawal. Please allow 4 working days for " . $balance . " to reach your " . $request->payment_method . " account.");
        return redirect()->back();
    }

    public function CharityCashout(Request $request, Cashout $cashout)
    {
        $user = Auth::user();
        $previousCashouts = $user->cashouts()->where('status', 'paid')->count();
        if (isset(SiteSetting()['min_cashout_amount']) && $previousCashouts == 0) {
            $min = SiteSetting()['min_cashout_amount'];
        } else if (isset(SiteSetting()['next_cashout_amount']) && $previousCashouts > 0) {
            $min = SiteSetting()['next_cashout_amount'];
        } else if($previousCashouts == 0) {
            $min = 1;
        }else{
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
            flash()->error('You have already  withdraw request.');
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'charity_types_id' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cashout = Cashout::create([
            'user_id' => $user->id,
            'charity_types_id' => $request->charity_types_id,
            'cashout_type' => $request->payment_method,
            'amount' => $request->amount,
            'new_cashout' => '1',
            'payment_method' => $request->payment_method,
            'status' => 'processing donation'
        ]);

        $cashback = $user->cashbacks()->where('id', $request->id)->first();
        $cashback->update(['status' => 5, 'cashout_id' => $cashout->id]);
        $cashback->statusHistory()->create([
            'cashback_status_id' => $cashback->status
        ]);

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

        flash()->success("We're processing your withdrawal. Please allow 4 working days for " . $request->amount . " to reach your " . $request->payment_method . " account.");
        return redirect()->back();
    }
}
