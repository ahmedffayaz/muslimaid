<?php

namespace App\Http\Controllers\Admin;

use App\Models\TicketReply;
use Illuminate\Http\Request;
use App\Jobs\SendNotification;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\JsonResponse;
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
                ->withInput();
        }

        try {
            $reply = TicketReply::create([
                'reply' =>  $request->input('reply'),
                'user_id' => Auth::user()->id,
                'ticket_id' => $request->input('ticket_id'),
                'reply_by'=>'admin'
            ]);

            $ticket = Ticket::findOrFail($request->ticket_id);

            $reply->ticket->update(['status' => 'pending']);

            sendEmailNotification($ticket);

            $title = 'Admin Replied';
            $message = 'Your ticket has been replied by Admin';
            $url = url('account/tickets');
            $user = $ticket->user()->get();
            $deviceToken = optional($ticket->user->devices()->whereType('web')->first())->fcm_token;
            $deviceToken != null ? dispatch(new SendNotification($title, $message, $deviceToken, $url, $user)) : '';

            if (!$request->ajax()) {
                flash()->success('Ticket has been replied successfully');
                return back();
            } else {
                return response()->json([
                    'status' => JsonResponse::HTTP_OK,
                    'message' => 'Ticket has been replied successfully'
                ], JsonResponse::HTTP_OK);
            }
        } catch (Exception $e) {
            if (!$request->ajax()) {
                flash()->success('Something went wrong');
                return back();
            } else {
                return response()->json([
                    'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'Something went wrong'
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
}
