<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\TicketCategory;
use App\Models\Ticket;
use App\Mailers\AppMailer;

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
                'category'  => 'required',
                'message'   => 'required'
            ]);

            $ticket = new Ticket([
                'title'     => $request->input('title'),
                'user_id'   => \Auth::user()->id,
                'ticket_id' => strtoupper(\Str::random(12)),
                'category_id'  => $request->input('category'),
                'priority'  => 'high',
                'message'   => $request->input('message'),
                'status'    => "open",
            ]);

            $ticket->save();

            // $mailer->sendTicketInformation(\Auth::user(), $ticket);
            flash()->success("A ticket with ID: #$ticket->id has been opened.");

            return redirect()->back();
    }
}
