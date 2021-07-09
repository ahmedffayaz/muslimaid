<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\TicketCategory;
use App\Models\Ticket;
use App\Mailers\AppMailer;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailTemplate;



class TicketsController extends Controller
{
    public function create()
    {
        $categories = TicketCategory::all();
        $languages = Language::orderBy('id', 'desc')->get();
        return view('frontend.tickets.create', compact('categories','languages'));
    }
    public function store(Request $request, AppMailer $mailer)
    {
        $this->validate($request, [
                'title'     => 'required',
                // 'category'  => 'required',
                'message'   => 'required'
            ]);

            $ticket = new Ticket([
                'title'     => $request->input('title'),
                'user_id'   => \Auth::user()->id,
                'ticket_id' => strtoupper(\Str::random(12)),
                'category_id'  => $request->input('category'),
                'priority'  => 'high',
                'ticket_type'  => 'ticket',
                'message'   => $request->input('message'),
                'status'    => "open",
            ]);

            $ticket->save();

            $user_email_template = EmailTemplate::where('key','user_new_ticket')->first(); 
            $admin_email_template = EmailTemplate::where('key','admin_new_ticket')->first(); 

            $filtered_user_message  = str_replace(['{{SITE_TITLE}}', '{{SITE_URL}}', '{{NAME}}', '{{EMAIL}}','{{TICKET_ID}}', '{{CATEGORY}}','{{MESSAGE}}'],
                                        [SiteSetting()['website_title'], url('/') ,$ticket->user->first_name.' '.$ticket->user->last_name,$ticket->user->email,$ticket->ticket_id,$ticket->category->name ?? '',$ticket->message],
                                        $user_email_template->message );
            $filtered_admin_message  = str_replace(['{{SITE_TITLE}}', '[{SITE_URL}}', '{{NAME}}', '{{EMAIL}}','{{TICKET_ID}}', '{{CATEGORY}}','{{MESSAGE}}'],
                                        [SiteSetting()['website_title'], url('/') ,$ticket->user->first_name.' '.$ticket->user->last_name,$ticket->user->email,$ticket->ticket_id,$ticket->category->name ?? '',$ticket->message],
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


            flash()->success("A ticket with ID: #$ticket->ticket_id has been opened.");

            return redirect()->back();
    }
}
