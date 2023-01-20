<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Ticket;
use App\Models\ExitClick;
use App\Models\UserCashback;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.client-dashboard.tickets.index');
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $clicks = $user->clicks;
        return view('frontend.client-dashboard.tickets.create', compact('clicks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::where('ticket_id',$id)->firstOrFail();
        return view('frontend.client-dashboard.tickets.show',compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        $ticket->update([
            'claim_amount' => $request->input('amount')
            ]);
         $this->sendEmailNotification($ticket);
        flash()->success("We've received your claim.<br> Please allow up to six months to get a decision from the retailer.");
        return redirect()->route('account.tickets.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function step2(Request $request)
    {
        $store_id = $request->input('store_id');
        $claim = $request->input('claim_type');
        $user = Auth::user();
        $clicks = $user->clicks->where('store_id', $store_id);       
        if($claim  =='missing cashback'){
            return view('frontend.client-dashboard.tickets.ticket_step2',compact('store_id','claim','clicks'));
        }
        if($claim == 'declined cashback'){
            $cashback =  UserCashback::where([
                'store_id'=>$store_id,
                'user_id'=>$user->id,
            ])->where('status',2)->get();

            if(count($cashback)){
                return view('frontend.client-dashboard.tickets.ticket_step2',compact('store_id','claim','clicks','cashback'));

            }else{
                flash()->error('We have no record of a declined transaction with this retailer.');
                return redirect()->back();
            }

           
        }
        if($claim == 'incorrect amount'){

            $cashback =  UserCashback::where([
                'store_id'=>$store_id,
                'user_id'=>$user->id,
            ])->whereIn('status',[1,4,3])->get();

            if(count($cashback)){
                return view('frontend.client-dashboard.tickets.ticket_step2',compact('store_id','claim','clicks','cashback'));

            }else{
                flash()->error('We have no record of a pending, confirmed or paid transaction with this retailer.');
                return redirect()->back();
            }
            
        }

    }

    public function step3(Request $request){
       
        $click_id = $request->input('click_id');
        $click = ExitClick::where('id',$click_id)->first();
        $claim_type = $request->input('claim_type');
        $claim = new Ticket;
        $claim->store_id=$click->store_id;
        $claim->user_id=$click->user_id;
        $claim->click_id=$click->id;
        $claim->ticket_id = strtoupper(Str::random(12));
        $claim->cashback_id=$click->cashback->id ?? NULL;
        $claim->claim_amount=$click->cashback->order_value ?? NULL;
        $claim->claim_type=$claim_type;
        $claim->title = 'Claim: '.$claim_type;
        if( $claim_type == 'missing cashback'){
            $claim->category_id='1';
        }else if($claim_type == 'declined cashback'){
            $claim->category_id='2';
        }else if ($claim_type =='incorrect amount'){
            $claim->category_id='3';
        }
        $claim->ticket_type = 'claim';
        $claim->status = 'open';
        $claim->save();
        if($claim_type=='incorrect amount' || $claim_type == 'declined cashback'){
            flash()->success("We've received your claim.<br> Please allow up to six months to get a decision from the retailer.");
            return redirect()->route('account.tickets.index');
            $this->sendEmailNotification($claim);

        }

        return view('frontend.client-dashboard.tickets.ticket_step3',compact('claim'));

    }

    public function sendEmailNotification(Ticket $ticket){


        $user_email_template = EmailTemplate::where('key','user_new_claim')->first(); 
        $admin_email_template = EmailTemplate::where('key','admin_new_claim')->first(); 

        $filtered_user_message  = str_replace(['{{SITE_TITLE}}', '{{SITE_URL}}', '{{NAME}}', '{{EMAIL}}','{{TICKET_ID}}', '{{CLAIMTYPE}}','{{MESSAGE}}'],
                                    [SiteSetting()['website_title'], url('/') ,$ticket->user->first_name.' '.$ticket->user->last_name,$ticket->user->email,$ticket->ticket_id,$ticket->claim_type,$ticket->message],
                                    $user_email_template->message );
        $filtered_admin_message  = str_replace(['{{SITE_TITLE}}', '{{SITE_URL}}', '{{NAME}}', '{{EMAIL}}','{{TICKET_ID}}', '{{CLAIMTYPE}}','{{MESSAGE}}'],
                                    [SiteSetting()['website_title'], url('/') ,$ticket->user->first_name.' '.$ticket->user->last_name,$ticket->user->email,$ticket->ticket_id,$ticket->claim_type,$ticket->message],
                                    $admin_email_template->message );


        $email_data = array(
            'name' =>  $ticket->user->first_name.' '.$ticket->user->last_name,
            'email' => $ticket->user->email,
            'email_message'=>$filtered_user_message,
            'subject'=>$user_email_template->subject
        );
        Mail::send('emails.email_template', $email_data, function ($message) use ($email_data) {
            $message->to($email_data['email'], $email_data['name'])
                ->subject($email_data['subject']);
        });

        $email_data = array(
            'name' =>  $ticket->user->first_name.' '.$ticket->user->last_name,
            'email' => $ticket->user->email,
            'email_message'=>$filtered_admin_message,
            'subject'=>$admin_email_template->subject
        );
        Mail::send('emails.email_template', $email_data, function ($message) use ($email_data) {
            $message->to('admin@trs.com', $email_data['name'])
                ->subject($email_data['subject']);
        });

    }

}
