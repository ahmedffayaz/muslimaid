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
@php if(array_key_exists('min_cashout_amount',SiteSetting()->toArray())) $min = SiteSetting()['min_cashout_amount']; else $min = 1;  @endphp
<div class="block mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-3 d-flex">
                @include('client-dashboard.side-nav')
            </div>
            <div class="col-12 col-lg-9 mt-4 mt-lg-0">
                @include('flash::message')
                <div class="row ">
                    <div class="col-lg-8">
                        <h2>Withdraw</h2>
                        <p>You can withdraw your earned cashback in a variety of ways.</p>
                    </div>
                    <div class="col-lg-4 text-right" >
                        <h3>Balance {{ currency() }}{{number_format((float)Auth::user()->availableBalance(), 2, '.', '')}}</h3>
                        </p>Select a payment method</p>
                    </div>
                </div>
                <div class="products-view__list products-list" data-layout="list" data-with-features="false" data-mobile-grid-columns="2">
                    <div class="products-list__body">

                        @if(SiteSetting()['payment_method_paypal'])
                        <div class="products-list__item">
                            <div class="product-card product-card--hidden-actions ">
                                <div class="product-card__image product-image pt-0 " >
                                    <img class="product-image__img" style="width: 150px"
                                    src="{{asset('frontend/images/logos/paypal-logo.png')}}"  alt="">
                                </div>
                                <div class="product-card__info align-self-center">
                                    <div class="product-card__name ">
                                        <ul class="product-card__features-list">
                                            <li>Receive payment using an email address</li>
                                            <li>Minimum withdrawal £{{$min}}</li>                                            
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-card__actions align-self-center">
                                    <div class="product-card__buttons mt-2">
                                        @if(Auth::user()->availableBalance() < $min)
                                        <p class="small">Your cashback balance is currently less than £{{$min}}</p>
                                        @endif
                                        <form action="{{route('account.cashout')}}" class="m-auto" method="POST">
                                            @csrf
                                            <input type="hidden" name="payment_method" value="paypal">
                                            <button type="subimt" class="btn btn-success"   @if(Auth::user()->availableBalance() < $min) disabled @endif>Withdraw</button>                                  
                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                       @endif
                       @if(SiteSetting()['payment_method_bank'])

                        <div class="products-list__item">
                            <div class="product-card product-card--hidden-actions ">
                                <div class="product-card__image product-image align-self-center" >
                                    <h4>Bank Transfer</h4>
                                </div>
                                <div class="product-card__info align-self-center">
                                    <div class="product-card__name ">
                                        <ul class="product-card__features-list">
                                            <li>Free & secure</li>
                                            <li>Minimum withdrawal £{{$min}}</li>                                            
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-card__actions align-self-center">
                                    <div class="product-card__buttons mt-2">
                                        @if(Auth::user()->availableBalance() < $min)
                                        <p class="small">Your cashback balance is currently less than £{{$min}}</p>
                                        @endif
                                        <form action="{{route('account.cashout')}}" class="m-auto" method="POST">
                                            @csrf
                                            <input type="hidden" name="payment_method" value="bank">
                                            <button type="subimt" class="btn btn-success"@if(Auth::user()->availableBalance() < $min) disabled @endif >Withdraw</button>                                  
                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection