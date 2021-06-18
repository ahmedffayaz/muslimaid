@extends('layouts.frontend.app')
@section('content')
{{-- <div class="page-header">
    <div class="page-header__container container">
        <div class="page-header__breadcrumb">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.html">Home</a>
                        <svg class="breadcrumb-arrow" width="6px" height="9px">
                            <use xlink:href="images/sprite.svg#arrow-rounded-right-6x9"></use>
                        </svg>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="">Breadcrumb</a>
                        <svg class="breadcrumb-arrow" width="6px" height="9px">
                            <use xlink:href="images/sprite.svg#arrow-rounded-right-6x9"></use>
                        </svg>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">My Account</li>
                </ol>
            </nav>
        </div>
        <div class="page-header__title">
            <h1>My Account</h1>
        </div>
    </div>
</div> --}}
<div class="block mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-3 d-flex">
                @include('client-dashboard.side-nav')
            </div>
            <div class="col-12 col-lg-9 mt-4 mt-lg-0">
                @if($cashbacks->count())
                <div class="card">
                   
                    <div class="card-header">
                        <h5>My Cashback</h5>
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
                                    @foreach ($cashbacks as $cashback)
                                    <tr>
                                        <td><a href="{{route('store.show',$cashback->store->slug)}}" target="_blank">{{$cashback->store->name}}</a></td>
                                        <td>{{ currency() }} {{$cashback->order_value}}</td>
                                        <td>{{ currency() }} {{$cashback->amount}}</td>
                                        <td>{{Carbon\Carbon::parse($cashback->event_date)->isoFormat('Do MMMM YYYY')}}</td>
                                        <td>{{$cashback->statusMap->status}}</td>
                                    </tr> 
                                    @endforeach
                                    
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                   
                </div>
                @else
                <div class="text-center">
                   <h2>No Earning Yet</h2>
                   <p>Once you start earning cashback your transactions will be listed here.</p>
                    <img src="{{asset('frontend/images/pages/wallet.png')}}" alt="" width="100%" style="max-width: 300px">
                    <div>
                        <a href="{{route('offers')}}" class="btn btn-primary mt-4">View Offers</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection