<?php

namespace App\Http\Controllers\Client;

use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use App\Jobs\SendNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RepliesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reply' => 'required|max:255'
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please ensure that the line is no longer than 225 characters.');
        }
        $reply = TicketReply::create([
            'reply' =>  $request->input('reply'),
            'user_id' => Auth::user()->id,
            'ticket_id' => $request->input('ticket_id'),
            'reply_by' => 'user'
        ]);
        $reply->ticket->update(['status' => 'pending']);
        $title = 'Ticket Replied';
        $message = 'Your ticket has been replied';
        $url = url('account/tickets');
        $deviceToken = optional(auth()->user()->devices()->first())->fcm_token;

        $deviceToken != null ? dispatch(new SendNotification($title, $message, $deviceToken,$url)) : '';
        return back();
    }
}
