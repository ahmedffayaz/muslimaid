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
                            <use xlink:href="{{asset('frontend/images/sprite.svg')}}#arrow-rounded-right-6x9"></use>
                        </svg>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="">Breadcrumb</a>
                        <svg class="breadcrumb-arrow" width="6px" height="9px">
                            <use xlink:href="{{asset('frontend/images/sprite.svg')}}#arrow-rounded-right-6x9"></use>
                        </svg>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">My Account</li>
                </ol>
            </nav>
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
                @if($cashouts->count())
                <div class="card">
                    <div class="card-header">
                        <h5>Cashouts Statement</h5>
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
                                    @foreach ($cashouts as $cashout)
                                    <tr>
                                        <td></td>
                                        
                                        <td></td>
                                       
                                    </tr> 
                                    @endforeach
                                    
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                   
                </div>
                @else
                <div class="text-center">
                    <h2>Statement</h2>
                   <p>We'll show your paid cashback and payments here when you have cashback to be paid to you</p>
                   <p>To start earning cashback why not check out our latest offers</p>
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