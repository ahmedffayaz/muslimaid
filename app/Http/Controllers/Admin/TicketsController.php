<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Jobs\SendNotification;
use App\Models\TicketCategory;
use App\Http\Controllers\Controller;

class TicketsController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view tickets', ['only' => ['index','show']]);
        $this->middleware('permission:close tickets', ['only' => ['closeTicket']]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route='index';
        $tickets = Ticket::orderBy('new_ticket','DESC')->latest()->paginate(30);
        $categories = TicketCategory::latest()->get();
        $users = User::role('user')->latest()->get();
        return view('admin-dashboard.tickets.index', compact('users','categories','tickets','route'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        $ticket->update(['new_ticket'=>0]);
        $newReply = $ticket->newReply;

        if(count($newReply)){
            foreach($newReply as $reply){
                $reply->checked =1;
                $reply->update();
            }
        }
        return view('admin-dashboard.tickets.show',compact('ticket'));
    }

    public function closeTicket(Ticket $ticket)
    {
        $ticket->update(['status'=>'closed',
        'closing_time'=> Carbon::now(),
        'closed_by'=>auth()->user()->id]);
        $title = 'Ticket Closed';
        $message = 'Your ticket has been closed.';
        $deviceToken = auth()->user()->devices()->first()->fcm_token;

        dispatch(new SendNotification($title, $message, $deviceToken));
        flash()->success('Ticket closed');
                return redirect()->back();
    }

    function fetch(Request $request)
    {
        if($request->ajax())
        {
            $route='index';
            $tickets = Ticket::orderBy('new_ticket','DESC')->latest()->paginate(30);
            return view('admin-dashboard.tickets.index_data', compact('tickets','route'))->render();
        }
    }
    public function searchTickets(Request $request, Ticket $tickets)
    {
        $tickets = $tickets->newQuery();

        // Search by ticket_id
        if ($request->input('ticket_id')) {
            $tickets->where('ticket_id', 'like', '%'.$request->input('ticket_id').'%');
        }
        // Search by user .
        if ($request->input('user_id')) {
            $tickets->where('user_id', $request->input('user_id'));
        }
        // Search by id.
        if ($request->input('category')) {
            $tickets->where('category_id', $request->input('category'));
        }

        // Search by title.
        if ($request->input('title')) {
            $tickets->where('title','like', '%'.$request->input('title').'%');

        }

        // Search by status.
        if ($request->input('status')!=-1) {
            $tickets->where('status', $request->input('status'));
        }

        $tickets = $tickets->orderBy('new_ticket','DESC')->latest()->paginate(30);
        $route='search';
        return view('admin-dashboard.tickets.index_data', compact('tickets','route'))->render();
    }
}
