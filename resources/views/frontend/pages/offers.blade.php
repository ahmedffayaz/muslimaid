@extends('layouts.frontend.app')

@section('content')
<style>[dir=ltr] .product-card--layout--horizontal .product-card__actions {
    left: 90px;
}
.product-card--layout--horizontal .product-card__image {
    width: 90px;
    padding: 14px;
}
   </style>
<!-- Cashbacks -->
@php $categories = getCategories(); @endphp
@isset($categories)
<!-- .block-product-columns -->
@if($page->lb_content)
<div class="block block-product-columns mt-5">
    <div class="container">
        <div class="row">
           
            <div class="col-lg-12">
                <div id="your_container"> <!-- The element you want to render the content in -->
                    {!! $page->lb_content !!}
                  </div>
            </div>
        </div>
    </div>
</div>
@endif
<div class="container">
    <div class="page-header__breadcrumb">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{url('/')}}">Home</a>
                    <svg class="breadcrumb-arrow" width="6px" height="9px">              
                        <use xlink:href="{{asset('frontend/images/sprite.svg')}}#arrow-rounded-right-6x9"></use>
                    </svg>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Offers</li>
            </ol>
        </nav>
    </div>
</div>
<div class="container p-2 my-2">
    <div class="row">
        <div class="col-12">
            <div class="block-finder__body">
               <img class="banner__size" src="{{url('storage/photos/1/bulbb.jpg')}}" alt="bjh">
                 <div class="block-finder__header">
                    <div class="block-finder__title">Offers</div>
                    <div class="block-finder__subtitle"></div>
                 </div>
            </div>
            <div class="category-text panel  mb-4 mt-2">
                <p class="mt-1">
                    With Cashblack To Your Door, you can discover local restaurants and grocery stores to order African, Caribbean or 
                    from any other authentic Black-owned vendors and earn cashback through our partnerships with your favourite 
                    delivery apps such as Deliveroo and Uber Eats. Whether there’s rice at home or not, you can be sure that through 
                    Cashblack To Your Door, you’ll always be able to find local Black-owned retailers for your next order.
                    You can go to <span class="text-bold text-success"> Grocery Stores</span> or 
                    <span class="text-bold text-success">Restaurants.</span>
                </p>
            </div>
        </div>
    </div>
</div>


<div class="block block-product-columns pt-3 mt-3">
    <div class="container">
        <div class="row">
           
            @foreach ($categories as $category)
               @if($category->stores->count()) 
            
            <div class="col-lg-4 mt-5">
                <div class="block-header">
                    <h3 class="block-header__title"> @if($category->logo_type == 'upload')
        
                        <img src="{{asset('storage/categories/images/'.$category->logo_upload)}}" width="25px" alt="">
                      
                        @elseif($category->logo_type == 'link')
                        
                        <img src="{{$category->logo_link}}"  width="25px" alt="">
                     
                        @endif{{$category->name}}</h3>
                    <div class="block-header__divider"></div>
                    <ul class="block-header__groups-list">
                        <li><a href="{{route('cashabck',$category->slug)}}" type="button" class="block-header__group color-primary">View More</a></li>
                    </ul>
                </div>
                <div class="block-product-columns__column">
                  
                    @foreach ($category->stores->unique()->take(5) as $store)
                        
                    
                    <div class="block-product-columns__item">
                        <div class="product-card product-card--hidden-actions product-card--layout--horizontal">
                            <div class="product-card__image product-image">
                                <a href="{{route('store.show',$store->slug)}}" class="product-image__body">
                                    <img class="product-image__img"   @if($store->logo->first())
                                    @if($store->logo->first()->is_fake)
                                        src="{{asset('frontend/images/logos/'.$store->logo->first()->image)}}"
                                    @else
                                        src="{{asset('storage/stores/images/'.$store->logo->first()->image)}}"
                                    @endif
                                @else
                                    src="{{asset('frontend/images/products/product-16.jpg')}}" 
                                @endif alt="">
                                </a>
                            </div>
                            <div class="product-card__info">
                                <div class="product-card__name">
                                    <a href="{{route('store.show',$store->slug)}}">{{$store->name}}</a>
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
                            <div class="product-card__actions">
                               
                                <div class="product-card__prices">
                                    @if($store->cashback)
                                        
                                        @if($store->custom_cashback_percentage)
                                            @if($store->cashback->type=='fixed'){{$store->cashback->currency}}@endif{{($store->custom_cashback_percentage/100)*$store->cashback->sale_commission}}@if($store->cashback->type=='percentage')%@endif
                                        @else
                                            @if($store->cashback->type=='fixed'){{$store->cashback->currency}}@endif{{(SiteSetting()['cashback_percentage']/100)*$store->cashback->sale_commission}}@if($store->cashback->type=='percentage')%@endif
                                        @endif
                                         Cashback
                                    @endif
                                </div>
                             
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endforeach
         
        </div>
    </div>
</div>
<!-- .block-product-columns / end -->

@endisset

@endsection