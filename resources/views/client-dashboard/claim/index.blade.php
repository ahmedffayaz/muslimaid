@extends('layouts.frontend.app')
@section('content')
<div class="block mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-3 d-flex">
                @include('client-dashboard.side-nav')
            </div>
            <div class="col-12 col-lg-9 mt-4 mt-lg-0">
                @include('flash::message')
                @if(Auth::user()->claims->count())
                <div class="card">
                   
                    <div class="card-header">
                        <h5  class="d-inline-block">Claims</h5>
                        <a href="{{route('account.claim.create')}}" class="float-right font-14">Raise a claim</a>
                    </div>
                    <div class="card-divider"></div>
                   
                    <div class="card-table">
                        <div class="table-responsive-sm">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Claim ID</th>
                                        <th>Store</th>
                                        <th>Order Amount</th>
                                        <th>Claim Type</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (Auth::user()->claims as $claim )
                                    <tr>
                                        <td><a href="{{route('account.claim.show',$claim->ticket_id)}}">{{$claim->ticket_id}}</a></td>
                                        <td>{{$claim->store->name}}</td>
                                        <td>{{ currency() }}{{number_format((float)$claim->claim_amount, 2, '.', '')}}</td>
                                        <td class="text-capitalize">{{$claim->claim_type}}</td>
                                        <td>{{Carbon\Carbon::parse($claim->created_at)->isoFormat('Do MMMM YYYY')}}</td>
                                        <td>@if($claim->status =='open')
                                            <span class="badge badge-primary">Pending</span>
                                        @elseif($claim->status=='pending')
                                            @if($claim->lastReply->user_id ==Auth::user()->id) <span class="badge badge-info">Replied </span>
                                            @else <span class="badge badge-warning">Awaiting your reply </span>@endif
                                
                                        @elseif($claim->status=='closed')
                                            <span class="badge badge-success">Closed</span>
                                
                                        @endif</td>
                                    </tr> 
                                    @endforeach
                                    
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                   
                </div>
                @else
                <div class="text-center">
                   <h2>No Claims Found</h2>
                  
                    
                    <div>
                        <a href="{{route('account.claim.create')}}" class="btn btn-primary mt-4">Raise a claim</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection