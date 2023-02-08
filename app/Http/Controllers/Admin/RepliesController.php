<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


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
        $validatedData = $request->validate([
            'reply' => 'required|max:255'
        ]);

        $reply = TicketReply::create([
            'reply' => $validatedData['reply'],
            'user_id' => Auth::user()->id,
            'ticket_id' => $request->input('ticket_id'),
            'reply_by'=>'admin'
        ]);

        $reply->ticket->update(['status'=>'pending']);
        return back();
    }
}
