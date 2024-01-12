<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Cashout;
use Illuminate\Http\Request;
use App\Jobs\SendEmailToUser;
use App\Jobs\SendNotification;
use App\Models\CashbackStatus;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\CashbackStatusChange;
use Illuminate\Support\Facades\Validator;

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
        $cashouts = Cashout::whereHas('user')->latest()->paginate(10);

        return view('admin-dashboard.cashouts.index', compact('cashouts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $cashout = Cashout::whereHas('user')->findOrFail($id);
        $cashout->update(['new_cashout'=>0]);
        $users  = User::withTrashed()->latest()->get();
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
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:donated,paid'
        ]);

        if($validator->fails()){
            if(!$request->ajax()){
                flash()->error($validator->errors()->first());
                return redirect()->back()->withInput();
            }

            return response()->json(['status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            'errors' => $validator->errors()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $cashout = Cashout::with(['user' => function ($query) {
                $query->withTrashed();
            }])->findOrFail($id);

            if($request->input('status') == 'pending'){
                $cashout->update(['status'=>'pending']);

                foreach($cashout->cashbacks as $cashback){
                    $cashback->update(['status'=>5]);
                }
            }
            elseif($request->input('status') == 'paid'){
                // Cashout transfer to admin if user has been deleted and cashout status is pending or processing donation
                if (!empty($cashout->user->deleted_at) && ($cashout->status == 'pending' || $cashout->status == 'processing donation')) {
                    $cashout->update(['user_id' => getAdminUser()->id]);
                }

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
                // Cashout transfer to admin if user has been deleted and cashout status is pending or processing donation
                if (!empty($cashout->user->deleted_at) && ($cashout->status == 'pending' || $cashout->status == 'processing donation')) {
                    $cashout->update(['user_id' => getAdminUser()->id]);
                }

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
