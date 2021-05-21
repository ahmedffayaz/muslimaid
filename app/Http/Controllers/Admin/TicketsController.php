<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use Carbon\Carbon;

class TicketsController extends Controller
{
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function update(Request $request, $id)
    {
        //
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


    public function closeTicket(Ticket $ticket){

        $ticket->update(['status'=>'closed',
        'closing_time'=> Carbon::now(),
        'closed_by'=>auth()->user()->id]);
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
        // dd($request->all());
        $tickets = $tickets->newQuery();

        // Search by ticket_id
        if ($request->input('ticket_id')) {
            $tickets->where('id', $request->input('ticket_id'));
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

        // Search by priority.
        if ($request->input('priority')!=-1) {
            $tickets->where('priority', $request->input('priority'));
        }
        
        $tickets = $tickets->latest()->paginate(30);
        $route='search';
        // dd($tickets);
        return view('admin-dashboard.tickets.index_data', compact('tickets','route'))->render();
    }
}
