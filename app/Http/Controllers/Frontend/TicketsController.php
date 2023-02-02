<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Ticket;
use App\Models\Language;
use App\Mailers\AppMailer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\SendEmailToUser;
use App\Jobs\SendEmailToAdmin;
use App\Models\TicketCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TicketsController extends Controller
{
    public function create()
    {
        $categories = TicketCategory::all();
        $languages = Language::orderBy('id', 'desc')->get();
        return view('frontend.tickets.create', compact('categories', 'languages'));
    }

    public function store(Request $request, AppMailer $mailer)
    {
        $this->validate($request, [
            'title'     => 'required',
            'message'   => 'required'
        ]);

        $ticket = new Ticket([
            'title'     => $request->input('title'),
            'user_id'   => Auth::user()->id,
            'ticket_id' => strtoupper(Str::random(12)),
            'category_id'  => $request->input('category'),
            'priority'  => 'high',
            'ticket_type'  => 'ticket',
            'message'   => $request->input('message'),
            'status'    => "open",
        ]);

        $ticket->save();

        $userEmailTemplateKey = 'user_new_ticket';
        $adminEmailTemplateKey = 'admin_new_ticket';
        $filterMessageVariables = ['{{TICKET_ID}}', '{{CLAIMTYPE}}', '{{CATEGORY}}'];
        $requestFilteredMessage = [$ticket->ticket_id, $ticket->claim_type, $ticket->category->name ?? ''];

        $data = [
            'name' => $ticket->user->first_name . ' ' . $ticket->user->last_name,
            'email' => $ticket->user->email,
            'message' => $ticket->message,
        ];

        SendEmailToUser::dispatch($userEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);
        SendEmailToAdmin::dispatch($adminEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);

        flash()->success("A ticket with ID: #$ticket->ticket_id has been opened.");
        return redirect()->back();
    }
}
