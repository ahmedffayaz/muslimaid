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
                @if($clicks->count())
                <div class="card">
                    <div class="card-header">
                        <h5>Clicks</h5>
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
                                    @foreach ($clicks as $click)
                                    <tr>
                                        <td><a href="{{route('store.show',$click->store->slug)}}" target="_blank">{{$click->store->name}}</a></td>
                                        
                                        <td>{{Carbon\Carbon::parse($click->created_at)->isoFormat('Do MMMM YYYY')}}</td>
                                       
                                    </tr> 
                                    @endforeach
                                    
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- <div class="card-divider"></div>
                    <div class="card-footer">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link page-link--with-arrow" href="" aria-label="Previous">
                                    <svg class="page-link__arrow page-link__arrow--left" aria-hidden="true" width="8px" height="13px">
                                        <use xlink:href="images/sprite.svg#arrow-rounded-left-8x13"></use>
                                    </svg>
                                </a>
                            </li>
                            <li class="page-item"><a class="page-link" href="">1</a></li>
                            <li class="page-item active"><a class="page-link" href="">2 <span class="sr-only">(current)</span></a></li>
                            <li class="page-item"><a class="page-link" href="">3</a></li>
                            <li class="page-item">
                                <a class="page-link page-link--with-arrow" href="" aria-label="Next">
                                    <svg class="page-link__arrow page-link__arrow--right" aria-hidden="true" width="8px" height="13px">
                                        <use xlink:href="images/sprite.svg#arrow-rounded-right-8x13"></use>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div> --}}
                </div>
                @else
                <div class="text-center">
                    <h2>No Clicks Acitivity</h2>
                    <p>Your clicks activity will be listed here.</p>
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