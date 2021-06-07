@extends('layouts.frontend.app')
@section('content')
<div class="page-header">
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
</div>
<div class="block">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-3 d-flex">
                @include('client-dashboard.side-nav')
            </div>
            <div class="col-12 col-lg-9 mt-4 mt-lg-0">
                <h2>Withdraw</h2>
                        <p>You can withdraw your earned cashback in a variety of ways...</p>
                <div class="products-view__list products-list  scrolling-pagination" data-layout="list" data-with-features="false" data-mobile-grid-columns="2">
                    <div class="products-list__body">
                      
                        @foreach($stores as $store)
                            <div class="products-list__item">
                                <div class="product-card product-card--hidden-actions ">
                                    <div class="product-card__image product-image">
                                        <a href="{{route('store.show',$store->slug)}}" class="product-image__body">
                                            <img class="product-image__img" @if($store->logo->first())
                                            src="{{asset('storage/stores/images/'.$store->logo->first()->image)}}"
                                            @else
                                            src="{{asset('frontend/images/products/product-16.jpg')}}" 
                                            @endif alt="">
                                        </a>
                                    </div>
                                    <div class="product-card__info align-self-center">
                                        <div class="product-card__name ">
                                            <a href="{{route('store.show',$store->slug)}}"> {!!textHighlight($store->name,$term)!!}</a>
                                        </div>
                                        @if($store->reviews->count())
                                            <div class="product-card__rating">
                                                <div class="product-card__rating-stars">
                                                    <div class="rating">
                                                        <div class="rating__body">
                                                            @foreach (range(1,5) as $index)
                                                            <svg class="rating__star @if($index <= $store->reviews->avg('rating')) rating__star--active @endif" width="13px" height="12px">
                                                                <g class="rating__fill">
                                                                    <use xlink:href="{{asset('frontend/images/sprite.svg')}}#star-normal"></use>
                                                                </g>
                                                                <g class="rating__stroke">
                                                                    <use xlink:href="{{asset('frontend/images/sprite.svg')}}#star-normal-stroke"></use>
                                                                </g>
                                                            </svg>
                                                            <div class="rating__star rating__star--only-edge @if($index <= $store->reviews->avg('rating')) rating__star--active @endif">
                                                                <div class="rating__fill">
                                                                    <div class="fake-svg-icon"></div>
                                                                </div>
                                                                <div class="rating__stroke">
                                                                    <div class="fake-svg-icon"></div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-card__rating-legend">{{$store->reviews->count()}} Reviews</div>
                                            </div>
                                        @endif

                                    </div>
                                    <div class="product-card__actions align-self-center">
                                        
                                        <div class="product-card__prices">
                                            @if($store->cashback->type=='fixed'){{$store->cashback->currency}} @endif{{$store->cashback->sale_commission}}@if($store->cashback->type=='percentage')%@endif Cashback
                                        </div>
                                        <div class="product-card__buttons mt-2">
                                            {{-- <button class="btn btn-primary product-card__addtocart" type="button">Shop Now</button> --}}
                                            <a href="{{route('store.show',$store->slug)}}" class="btn btn-primary product-card__addtocart product-card__addtocart--list" type="button">Shop Now</a>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {!! $stores->links()!!} 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection