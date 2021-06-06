@extends('layouts.frontend.app')
@section('content')
<style>
@media (min-width: 992px){
    .product--layout--sidebar .product__content {
    -ms-grid-columns: 30% 70%;
    grid-template-columns: [gallery] calc(30% - 16px) [info] calc(70% - 16px);
    grid-column-gap: 32px;
}
}
.small, small {
    font-size: 70%;
    font-weight: 400;
}
.product-tabs--layout--sidebar .product-tabs__content {
    padding: 40px 30px;
}
.product-tabs__item {
    font-size: 18px;}
</style>
<div class="block mt-5">
<div class="container">
    <div class="shop-layout shop-layout--sidebar--start">
        @include('frontend.stores.sidebar')
        <div class="shop-layout__content">
            <div class="block">
                <div class="product product--layout--sidebar" data-layout="sidebar">
                    <div class="product__content">
                        <!-- .product__gallery -->
                        <div class="product__gallery">
                            <div class="product-gallery">
                                <img class="product-image__img" style="position: relative;" 
                                @if($store->logo->count())
                                    @if($store->logo->first()->is_uploaded)
                                        src="{{asset('storage/stores/images/'.$store->logo->first()->image)}}"
                                    @else
                                        src="{{asset('frontend/images/products/product-16.jpg')}}" alt=""
                                    @endif
                                
                                @else
                                        src="{{asset('frontend/images/products/product-16.jpg')}}" alt=""
                                    @endif
                                >
                            </div>
                        </div>
                        <!-- .product__gallery / end -->
                        <!-- .product__info -->
                        <div class="product__info">
                            <h1 class="product__name">{{$store->name}}</h1>
                            <p>Updated: {{Carbon\Carbon::parse($store->updated_at)->isoFormat('Do MMMM YYYY')}}</p>
                            
                            <div class="product__description">
                                Save money whilst shopping with {{$store->name}} cashback and deals! Simply click get cashback, shop as normal and earn rewards in your Cashback Reborn account.
                            </div>
                            
                        </div>
                        <!-- .product__info / end -->
                      
                        <div class="product__footer">
                            @guest

                            @else
                            <form action="{{route('site.exit_click.store')}}" method="POST" id="form_{{$store->id}}">
                                @csrf
                                <input type="hidden" name="url" id="url" value="{{$store->tracking_url}}">
                                <input type="hidden" name="store_id" id="store_id" value="{{$store->id}}">
                                <input type="hidden" name="user_id" id="user_id" value="{{\Auth::id()}}">

                            </form>
                            @endguest
                            <a href="#" @guest
                            data-toggle="modal" data-target="#signinModal"
                            @else  onclick="document.getElementById('form_{{$store->id}}').submit()"
                            @endguest class="btn btn-primary">Get Cashback<a>
                            
                        </div>
                    </div>
                </div>
                <div class="product-tabs  product-tabs--layout--sidebar  product-tabs--sticky">
                    <div class="product-tabs__list d-none">
                        <div class="product-tabs__list-body">
                            <div class="product-tabs__list-container container">
                                <a href="#tab-description" class="product-tabs__item product-tabs__item--active">Description</a>
                                @if($store->vouchers->count())
                                <a href="#tab-vouchers" class="product-tabs__item">Vouchers & Deals</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="product-tabs__content">
                        <div class="product-tabs__pane product-tabs__pane--active" id="tab-description">
                            <div class="typography">
                                <h4>Description</h4>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas fermentum, diam non iaculis finibus,
                                    ipsum arcu sollicitudin dolor, ut cursus sapien sem sed purus. Donec vitae fringilla tortor, sed
                                    fermentum nunc. Suspendisse sodales turpis dolor, at rutrum dolor tristique id. Quisque pellentesque
                                    ullamcorper felis, eget gravida mi elementum a. Maecenas consectetur volutpat ante, sit amet molestie
                                    urna luctus in. Nulla eget dolor semper urna malesuada dictum. Duis eleifend pellentesque dui et
                                    finibus. Pellentesque dapibus dignissim augue. Etiam odio est, sodales ac aliquam id, iaculis eget
                                    lacus. Aenean porta, ante vitae suscipit pulvinar, purus dui interdum tellus, sed dapibus mi mauris
                                    vitae tellus.
                                </p>
                               
                         
                            @if($store->vouchers->count())
                        
                                <h4>Vouchers</h4>
                            @foreach ($store->vouchers->unique('link_name') as $voucher)
                                <div class="product-card mb-2 p-4"> 
                                    <div class="row">
                                        <div class="col-md-8 align-items-center align-self-center"><h6 class="mt-0 align-self-center">{{$voucher->link_name}}</h6></div>
                                        @guest

                                        @else
                                        <form action="{{route('site.exit_click.store')}}" method="POST" id="form_{{$store->id}}">
                                            @csrf
                                            <input type="hidden" name="url" id="url" value="{{$store->tracking_url}}">
                                            <input type="hidden" name="store_id" id="store_id" value="{{$store->id}}">
                                            <input type="hidden" name="user_id" id="user_id" value="{{\Auth::id()}}">

                                        </form>
                                        @endguest
                                        <div class="col-md-4 text-md-right"><a href="#"
                                            @guest
                                            data-toggle="modal" data-target="#signinModal" 
                                            @else onclick="document.getElementById('form_{{$store->id}}').submit()"
                                            @endguest  class="btn btn-primary btn-sm" target="_blank">Get Deal <i class="fas fa-external-link-alt ml-2"></i></a>
                                        <div><small>Valid until: {{Carbon\Carbon::parse($voucher->promotion_end_date)->isoFormat('Do MMMM YYYY')}}</small></div></div>
                                    </div>
                                </div>
                            
                            @endforeach
                            @endif
                            
                            @if($store->reviews->count())
                            <div class="reviews-view">
                                <div class="reviews-view__list">
                                    <h3 class="reviews-view__header">Reviews</h3>
                                    <div class="reviews-list">
                                        <ol class="reviews-list__content">
                                            @foreach($store->reviews->take(3) as $review)
                                            <li class="reviews-list__item">
                                                <div class="review">
                                                    
                                                    <div class="review__content">
                                                        <div class="review__author">{{$review->reviewer}}</div>
                                                        <div class="review__rating">
                                                            <div class="rating">
                                                                <div class="rating__body">
                                                                
                                                                @foreach (range(1,5) as $index)
                                                                <svg class="rating__star @if($index <= $review->rating) rating__star--active @endif" width="13px" height="12px">
                                                                        <g class="rating__fill">
                                                                            <use xlink:href="{{asset('frontend/images/sprite.svg')}}#star-normal"></use>
                                                                        </g>
                                                                        <g class="rating__stroke">
                                                                            <use xlink:href="{{asset('frontend/images/sprite.svg')}}#star-normal-stroke"></use>
                                                                        </g>
                                                                    </svg>
                                                                    <div class="rating__star rating__star--only-edge @if($index <= $review->rating) rating__star--active @endif">
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
                                                        <div class="review__text">{!!$review->review!!}</div>
                                                        <div class="review__date">{{Carbon\Carbon::parse($review->created_at)->isoFormat('Do MMMM YYYY')}}</div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                        </ol>
                                    
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
    </div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="signinModal" tabindex="-1" role="dialog" aria-labelledby="signinModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="signinModalLabel">Login to get cashback</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <a href="{{route('register')}}" class="btn btn-primary">Login/Register</a>
        </div>
        
      </div>
    </div>
  </div>
@endsection