<?php

namespace App\Http\Controllers\Client;

use App\Models\TicketReply;
use Illuminate\Http\Request;
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

        return back();
    }
}
