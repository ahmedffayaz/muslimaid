<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailToUser;
use App\Jobs\SendNotification;
use Illuminate\Http\Request;
use App\Models\Cashout;
use App\Models\User;
use App\Models\CashbackStatus;
use App\Models\CashbackStatusChange;
use Carbon\Carbon;

class CashoutController extends Controller
{
    function __construct()
    {
        $this->middleware('is_cashout_module_access', ['only' => ['index', 'show', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cashouts = Cashout::latest()->paginate(10);

        return view('admin-dashboard.cashouts.index', compact('cashouts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Cashout $cashout)
    {
        $cashout->update(['new_cashout'=>0]);
        $users  = User::latest()->get();
        $statuses = CashbackStatus::all();
        return view('admin-dashboard.cashouts.show',compact('cashout','users','statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cashout $cashout)
    {
        try {
            if($request->input('status') == 'pending'){
                $cashout->update(['status'=>'pending']);

                foreach($cashout->cashbacks as $cashback){
                    $cashback->update(['status'=>5]);
                }
            }
            elseif($request->input('status') == 'paid'){
                $cashout->update(['status'=>'paid']);
                foreach($cashout->cashbacks as $cashback){
                    $cashback->update(['status'=>4]);
                    CashbackStatusChange::create([
                        'user_cashback_id' => $cashback->id,
                        'cashback_status_id' => $cashback->status
                    ]);
                }

                // Send email to user
                $userEmailTemplateKey = 'user_withdrawal_confirmation';
                $filterMessageVariables = ['{{AMOUNT}}', '{{DATE}}', '{{METHOD}}'];
                $method = '';
                if($cashout->payment_method == "charity"){
                    $method = "Giveback";
                } else if ($cashout->payment_method == "bank"){
                    $method = "Bank";
                } else if ($cashout->payment_method == "paypal") {
                    $method = "PayPal";
                } else {
                    $method = $cashout->payment_method;
                }

                $requestFilteredMessage = [number_format($cashout->amount, 2), convertTime($cashout->created_at), $method];
                $this->sendEmail($cashout, $userEmailTemplateKey, $filterMessageVariables, $requestFilteredMessage);

                // Send Push Norification
                $deviceToken = optional($cashout->user->devices()->whereType('web')->latest()->first())->fcm_token;
                $title = 'Successful Withdrawal Confirmation';
                $message = 'Your Transaction is Completed through ' . $cashout->payment_method;
                $deviceToken != null ? $this->sendNotification($title, $message, $cashout, $deviceToken) : '';
            }
            elseif($request->input('status') == 'donated'){
                $cashout->update(['status'=>'donated']);
                foreach($cashout->cashbacks as $cashback){
                    $cashback->update(['status'=>7]);
                    CashbackStatusChange::create([
                        'user_cashback_id' => $cashback->id,
                        'cashback_status_id' => $cashback->status
                    ]);
                }

                // Send email to user
                $userEmailTemplateKey = 'cashout_donation';
                $filterMessageVariables = [];
                $requestFilteredMessage = [];
                $this->sendEmail($cashout, $userEmailTemplateKey, $filterMessageVariables, $requestFilteredMessage);

                // Send Push Norification
                $deviceToken = optional($cashout->user->devices()->whereType('web')->latest()->first())->fcm_token;
                $title = 'Cashback donated';
                $message = 'Your cashback is donated with charity';
                $deviceToken != null ? $this->sendNotification($title, $message, $cashout, $deviceToken) : '';
            }
            flash()->success('Cashout Updated Successfully.');
            return redirect()->back();
        } catch (\Throwable $th) {
            flash()->error('Something went wrong, try again later.');
            return redirect()->back();
        }
    }

    function sendEmail($cashout, $userEmailTemplateKey, $filterMessageVariables, $requestFilteredMessage)
    {
        $data = [
            'name' => $cashout->user->first_name . ' ' . $cashout->user->last_name,
            'email' => $cashout->user->email,
            'subject' => null,
            'message' => null
        ];

        SendEmailToUser::dispatch($userEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);
    }

    function sendNotification($title, $message, $cashout, $deviceToken)
    {
        $url = route('account.cashouts');
        $user = $cashout->user()->get();

        dispatch(new SendNotification($title, $message, $deviceToken, $url, $user));
    }
}
