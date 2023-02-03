<?php

namespace App\Http\Controllers\API;

use App\Models\Ticket;
use App\Models\ExitClick;
use App\Models\UserCashback;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Jobs\SendEmailToUser;
use App\Models\EmailTemplate;
use App\Jobs\SendEmailToAdmin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;


class ClaimController extends Controller
{
    public function claims(){
        $claims =  \Auth::user()->claims;

        $claims = $claims->transform(function ($claim, $key) {

            if($claim->status =='open')  $status =  'Pending';

            elseif($claim->status=='pending')
            {
                if($claim->lastReply->user_id ==\Auth::user()->id) $status = 'Replied';
                else $status =  'Awaiting your reply';
            }
            elseif($claim->status=='closed')
            $status =  'Closed';

            return [
                'id'            => $claim->ticket_id,
                'store_name'    => $claim->store->name,
                'order_amount'  => $claim->claim_amount,
                'claim_type'    => $claim->claim_type,
                'date'          => \Carbon\Carbon::parse($claim->created_at)->isoFormat('Do MMMM YYYY'),
                'status'        => $status
                ];
        });

       $arr = array("status" => 200, "message" =>"User claims", "data" => $claims);
       return \Response::json($arr);

    }


    public function step1()
    {
        $user = \Auth::user();
        $clicks = ExitClick::select('store_id')->where('user_id',$user->id)->distinct()->get();


        $clicks = $clicks->transform(function ($click, $key) {
            return [
                'store_id'   => $click->store_id,
                'store_name' => $click->store->name,
                ];
        });

        $arr = array("status" => 200, "message" => "Select Store", "data" => $clicks);
        return \Response::json($arr);

    }
    public function step2(Request $request)
    {
        $store_id = $request->input('store_id');
        $claim    = $request->input('claim_type');
        $user     = \Auth::user();
        $clicks   = ExitClick::where('user_id',$user->id)->where('store_id', $store_id)->get();
        if($claim  =='missing cashback'){

            $data = [
                'store_id'   => $store_id,
                'claim_type' => $claim,
                'clicks'     => $clicks->transform(function ($click, $key) {
                    return [
                        'click_id' => $click->id,
                        'time'     => \Carbon\Carbon::parse($click->created_at)->isoFormat('Do MMMM YYYY hh:mm:ss')];
                })
            ];
            $arr = array("status" => 200, "message" =>"Select Cashback", "data" => $data);
            return \Response::json($arr);
        }
        if($claim == 'declined cashback'){
            $cashbacks =  UserCashback::where([
                'store_id' => $store_id,
                'user_id'  => $user->id,
            ])->where('status',2)->get();

            if(count($cashbacks)){

                $data = [
                    'store_id'   => $store_id,
                    'claim_type' => $claim,
                    'cashbacks'  => $cashbacks->transform(function ($cashback, $key) {
                                        return [
                                            'click_id'=>$cashback->excit_click_id,
                                            'time'=>\Carbon\Carbon::parse($cashback->event_date)->isoFormat('Do MMMM YYYY hh:mm:ss'),
                                            'order_amount'=>$cashback->order_value,
                                            'cashback_amount'=>$cashback->amount,
                                        ];
                                    })

                ];
                $arr = array("status" => 200, "message" =>"Select Cashback", "data" => $data);
                return \Response::json($arr);
            }else{
                $arr = array("status" => 400, "message" =>"We have no record of a declined transaction with this retailer.", "data" =>[]);
                return \Response::json($arr);
            }
        }
        if($claim == 'incorrect amount'){

            $cashbacks =  UserCashback::where([
                'store_id' => $store_id,
                'user_id'  => $user->id,
            ])->whereIn('status',[1,4,3])->get();

            if(count($cashbacks)){
                $data = [   'store_id'   => $store_id,
                            'claim_type' => $claim,
                            'cashbacks'     => $cashbacks->transform(function ($cashback, $key) {
                                return [
                                    'click_id'=>$cashback->exit_click_id,
                                    'time'=>\Carbon\Carbon::parse($cashback->event_date)->isoFormat('Do MMMM YYYY hh:mm:ss'),
                                    'order_amount'=>$cashback->order_value,
                                    'cashback_amount'=>$cashback->amount,
                                ];
                            })

                ];
                $arr = array("status" => 200, "message" =>"Select Cashback", "data" => $data);
                return \Response::json($arr);

            }else{
                $arr = array("status" => 400, "message" =>"We have no record of a pending, confirmed or paid transaction with this retailer.", "data" =>[]);
                return \Response::json($arr);
            }

        }

    }

    public function step3(Request $request){

        $click_id = $request->input('click_id');
        $click = ExitClick::where('id',$click_id)->first();
        $claim_type = $request->input('claim_type');

        $claim = new Ticket;
        $claim->store_id    = $click->store_id;
        $claim->user_id     = $click->user_id;
        $claim->click_id    = $click->id;
        $claim->ticket_id   = strtoupper(\Str::random(12));
        $claim->cashback_id = $click->cashback->id ?? NULL;
        $claim->claim_amount = $click->cashback->order_value ?? NULL;
        $claim->title       = 'Claim: '.$claim_type;
        $claim->claim_type  = $claim_type;
        $claim->ticket_type = 'claim';
        $claim->status      = 'open';
        $claim->save();


        if($claim_type == 'incorrect amount' || $claim_type == 'declined cashback'){
            $arr = array("status" => 200, "message" =>"We've received your claim. Please allow up to six months to get a decision from the retailer.", "data" => []);
            return \Response::json($arr);
            $this->sendEmailNotification($claim);

        }

        if($claim_type == 'missing cashback'){
            $claim->update([
                'claim_amount' => $request->input('amount')
                ]);
            $arr = array("status" => 200, "message" =>"We've received your claim. Please allow up to six months to get a decision from the retailer.", "data" => []);
            return \Response::json($arr);
            $this->sendEmailNotification($claim);
        }


    }

    public function sendEmailNotification(Ticket $ticket)
    {
        $userEmailTemplateKey = 'user_new_claim';
        $adminEmailTemplateKey= 'admin_new_claim';
        $filterMessageVariables = ['{{TICKET_ID}}', '{{CLAIMTYPE}}'];
        $requestFilteredMessage = [$ticket->ticket_id, $ticket->claim_type];

        $subject = ['subject' => null];
        $data = [
            'name' => $ticket->user->first_name . ' ' . $ticket->user->last_name,
            'email' => $ticket->user->email,
            'message' => $ticket->message,
        ];
        $data = array_merge($data, $subject);

        SendEmailToUser::dispatch($userEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);
        SendEmailToAdmin::dispatch($adminEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);
    }



}
