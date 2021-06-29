@extends('layouts.frontend.app')
@section('content')
<div class="block mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-3 d-flex">
                @include('client-dashboard.side-nav')
            </div>
            <div class="col-12 col-lg-9 mt-4 mt-lg-0">
                <div class="dashboard">
                    <div class="dashboard__profile card profile-card">
                        <div class="card-body profile-card__body">
                            <div class="profile-card__avatar">
                                <img src="{{asset('frontend/images/avatars/avatar-3.jpg')}}" alt="">
                            </div>
                            <div class="profile-card__name">{{$user->first_name}} {{$user->last_name}}</div>
                            <div class="profile-card__email">{{$user->email}}</div>
                            <div class="profile-card__edit">
                                <a href="{{route('account.profile')}}" class="btn btn-secondary btn-sm">Edit Profile</a>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard__address card address-card address-card--featured">
                        {{-- <div class="address-card__body">
                            <div class="address-card__name">Cashback<span class="text-md-right">Total Earned <br> 0$ </span></div>
                            <div class="address-card__row">
                                
                                
                            </div>
                            <div class="address-card__row">
                                <div class="address-card__row-title">Paid</div>
                                <div class="address-card__row-content">0$</div>
                            </div>
                            <div class="address-card__row">
                                <div class="address-card__row-title">Total</div>
                                <div class="address-card__row-content">0$</div>
                            </div>
                           
                        </div> --}}
                    </div>
                    @if($user->cashbacks->count())
                    <div class="dashboard__orders card">
                        <div class="card-header">
                            <h5 class="d-inline-block">Recent Cashback</h5>
                            @if($user->cashbacks->count() > 5)
                            <a href="{{route('account.cashback')}}" class="float-right font-14">View All</a>
                            @endif
                        </div>
                        <div class="card-divider"></div>
                        <div class="card-table">
                            <div class="table-responsive-sm">
                                <table>
                                    <thead>
                                        <tr>
                                            
                                            <th>Store</th>
                                            <th>Order Amount</th>
                                            <th>Cashback</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->cashbacks->take(5) as $item)
                                            <tr>
                                                
                                                <td><a href="{{route('store.show',$item->store->slug)}}" target="_blank">{{$item->store->name}}</a></td>
                                                <td>{{ currency() }} {{$item->order_value}}</td>
                                                <td>{{ currency() }} {{$item->amount}}</td>
                                                <td>{{Carbon\Carbon::parse($item->event_date)->isoFormat('Do MMMM YYYY')}}</td>
                                                <td>{{$item->statusMap->status}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($user->clicks->count())
                    <div class="dashboard__orders card">
                        <div class="card-header">
                            <h5 class="d-inline-block">Recent Clicks </h5>
                            @if($user->clicks->count() > 5)
                            <a href="{{route('account.clicks')}}" class="float-right font-14">View All</a>
                            @endif
                        </div>
                        <div class="card-divider"></div>
                        <div class="card-table">
                            <div class="table-responsive-sm">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Store</th>
                                            <th>Date</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->clicks->take(5) as $item)
                                            <tr>
                                                <td><a href="{{route('store.show',$item->store->slug)}}" target="_blank">{{$item->store->name}}</a></td>
                                                <td>{{Carbon\Carbon::parse($item->created_at)->isoFormat('Do MMMM YYYY')}}</td>
                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                                   
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection