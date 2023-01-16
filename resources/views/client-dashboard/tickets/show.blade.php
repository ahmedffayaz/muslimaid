@extends('layouts.frontend.app')
@section('content')

<div class="block mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-3 d-flex">
                @include('client-dashboard.side-nav')
            </div>
            <div class="col-12 col-lg-9 mt-4 mt-lg-0">
                {{-- <div class="dashboard__orders card mt-0">
                    <div class="card-header">
                        <h5 class="d-inline-block">Claim</h5>
                        
                        <a href="{{route('account.tickets.create')}}" class="float-right font-14">Raise a Claim</a>
                        
                    </div>
                </div> --}}
                @include('flash::message')
                
                <div class="card mb-4">
                    <div class="order-header">
                        <h5 class="order-header__title">Claim {{$ticket->ticket_id}}</h5>
                        <div class="order-header__subtitle">We've received your claim. Please allow up to six months to get a decision from the retailer. 

                        </div>
                    </div>
                    <div class="card-divider"></div>
                    <div class="card-table">
                        <div class="table-responsive-sm">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Claim</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="card-table__body card-table__body--merge-rows">
                                    <tr>
                                        <td>Retailer: {{$ticket->store->name}}</td>
                                        <td>@if($ticket->status =='open')
                                            <span class="badge badge-primary">Open</span>
                                        @elseif($ticket->status=='pending')
                                            @if($ticket->lastReply->user_id ==Auth::user()->id) <span class="badge badge-info">Replied </span>
                                            @else <span class="badge badge-warning">Awaiting your reply </span>@endif
                                
                                        @elseif($ticket->status=='closed')
                                            <span class="badge badge-success">Closed</span>
                                
                                        @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Claim date: {{Carbon\Carbon::parse($ticket->promotion_end_date)->isoFormat('Do MMMM YYYY')}}</td>
                                        <td></td>
                                       
                                    </tr>
                                    <tr>
                                        <td>Purchase amount: {{ currency() }}{{$ticket->claim_amount}}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Visit date: {{Carbon\Carbon::parse($ticket->promotion_end_date)->isoFormat('Do MMMM YYYY')}}</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            
                            </table>
                        </div>
                    </div>
                    
                </div>
                @if(count($ticket->replies))
                <div class="card mb-4">
                    <div class="order-header">
                        <h5 class="order-header__title">Replies</h5>
                    </div>
                    <div class="card-body pt-0">
                        
                        @foreach($ticket->replies as $reply)
                            <div class="nk-reply-item">
                                <div class="nk-reply-header">
                                    <div class="user-card">
                                        <div class="user-name text-capitalize"><b>{{$reply->user->first_name}} {{$reply->user->last_name}}</b></span></div>
                                    </div>
                                    <div class="date-time float-right">
                                        {{Carbon\Carbon::createFromTimeStamp(strtotime($reply->created_at))->diffForHumans()}}</div>
                                </div>
                                <div class="nk-reply-body">
                                    <div class="nk-reply-entry entry">
                                        <p>{{$reply->reply}}</p>
                                    
                                    </div>
                                </div>
                            </div><!-- .nk-reply-item -->
                        @endforeach
                    
                    </div>
                </div>
                @endif
                @if($ticket->status!='closed')
                <div class="card">
                    <div class="order-header">
                        <h5 class="order-header__title">Reply</h5>
                      
                    </div>
                    <div class="card-body pt-0">
                        <form method="POST" action="{{route('admin.replies.store')}}">
                            @csrf
                            <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
                            <div class="form-group">
                            
                                <textarea id="form-message" class="form-control" name="reply" placeholder="Hello" rows="4" spellcheck="false"></textarea>
                            </div>
                      
                        <button class="btn btn-primary" type="submit">Reply</button>
                        </form>
                    </div>
                </div>
                                        
               

                @endif
                
            </div>
        </div>
    </div>
</div>
@endsection