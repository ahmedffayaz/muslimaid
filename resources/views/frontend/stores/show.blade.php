@extends('layouts.frontend.app')
@section('content')
<style>
@media (min-width: 992px){
    .product--layout--sidebar .product__content {
    -ms-grid-columns: 20% 80%;
    grid-template-columns: [gallery] calc(20% - 16px) [info] calc(80% - 16px);
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
.coupon-button-type .coupon-deal, .coupon-button-type .coupon-print, .coupon-button-type .coupon-code {
    line-height: 1;
    padding: 14px 38px;
    background: #3366cc;
    color: #FFF;
    font-size: 14px;
    font-weight: 600;
    display: inline-block;
    letter-spacing: 1px;
    /* text-transform: uppercase; */
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    margin-bottom: 2px;
    min-width: 145px;
    text-align: center;
}
    
.coupon-button-type .coupon-deal, .coupon-button-type .coupon-print, .coupon-button-type .coupon-code {
    line-height: 1;
    padding: 14px 38px;
    /* background: #f90; */
    color: #FFF;
    font-size: 14px;
    font-weight: 600;
    display: inline-block;
    letter-spacing: 1px;
    /* text-transform: uppercase; */
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    margin-bottom: 2px;
    /* min-width: 195px; */
    text-align: center;
    cursor: pointer;
    transition-delay: 2s;
    transition-duration: 2s;
}
.coupon-button-type .coupon-code {
    color: #444;
    background: linear-gradient(
315deg
,rgba(0,0,0,.06) 10%,rgba(0,0,0,0) 15%,rgba(0,0,0,0) 35%,rgba(0,0,0,.06) 40%,rgba(0,0,0,.06) 60%,rgba(0,0,0,0) 65%,rgba(0,0,0,0) 85%,rgba(0,0,0,.06) 90%) repeat scroll 0 0/6px 6px rgba(0,0,0,0);
    text-align: right;
    padding: 8px 10px 8px 10px;
    font-size: 14px;
    border: 2px solid #DDD;
    position: relative;
}
.coupon-button-type .coupon-code .get-code {
    position: absolute;
    left: -2px;
    top: -2px;
    background: #3366cc;
    color: #FFF;
    font-size: 14px;
    padding: 10px 10px 10px 10px;
    border-top-left-radius: 2px;
    border-bottom-left-radius: 2px;
    transition: all 0.5s ease;
    min-width: 55%;
    text-align: left;
}
.coupon-button-type .coupon-code .get-code:after {
    content: "";
    display: block;
    width: 0;
    height: 0;
    border-top: 33.8px solid transparent;
    border-left: 45px solid #3366cc;
    position: absolute;
    right: -44px;
    top: 0;
}
.coupon-button-type .coupon-code .get-code:after {
    border-left-color: #3366cc;
}
.dialog-modal__voucher-code {
    margin: 0 auto;
    max-width: 500px;
    border: 2px dashed #1d7bce;
}
</style>
<div class="block mt-5">
    @include('flash::message')
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
                                <img class="product-image__img" style="position: relative; margin:auto; width:120px"
                                @if($store->logo->first())
                                    @if($store->logo->first()->is_fake)
                                        src="{{asset('frontend/images/logos/'.$store->logo->first()->image)}}"
                                    @else
                                        src="{{asset('storage/stores/images/'.$store->logo->first()->image)}}"
                                    @endif
                                @else
                                    src="{{asset('frontend/images/products/product-16.jpg')}}" 
                                @endif alt="">
                                
                            </div>
                        </div>
                        <!-- .product__gallery / end -->
                        <!-- .product__info -->
                        <div class="product__info">
                            <h1 class="product__name">{{$store->name}}</h1>
                            <p style="font-size: 13px">Updated: {{Carbon\Carbon::parse($store->updated_at)->isoFormat('Do MMMM YYYY')}}</p>
                            
                            <div class="product__description">
                                Save money whilst shopping with {{$store->name}} cashback and deals! Simply click get cashback, shop as normal and earn rewards in your Cashback Reborn account.
                            </div>
                            
                        </div>
                        <!-- .product__info / end -->
                      
                        <div class="product__footer">
                            <form action="{{route('site.exit_click.store')}}" method="POST" id="form_{{$store->id}}" target="_blank" class="tracker_form">
                                @csrf
                                <input type="hidden" name="url" id="url" value="{{$store->tracking_url}}">
                                <input type="hidden" name="store_id" id="store_id" value="{{$store->id}}">
                                <input type="hidden" name="voucher_id" id="voucher_id" value="0">
                                <input type="hidden" name="user_id" id="user_id" value="{{Auth::id() ?? 0}}">

                            </form>
                            
                            <h5>@if($store->cashback->type=='fixed'){{$store->cashback->currency}} @endif{{$store->cashback->sale_commission}}@if($store->cashback->type=='percentage')%@endif Cashback</h5>
                            
                            <a href="#" @guest class="btn btn-primary"
                            data-toggle="modal" data-target="#signinModal"
                            @else form_id = "form_{{$store->id}}" class="btn btn-primary store_form"
                            @endguest >Get Cashback<a>
                            
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
                                   {!!$store->description!!}
                                </p>
                                @if($store->vouchers->count())
                        
                                <h4>Vouchers</h4>
                                @foreach ($store->vouchers->unique('link_name') as $voucher)
                                    <div class="product-card mb-2 p-4"> 
                                        <div class="row">
                                            <div class="col-md-8 align-items-center align-self-center"><h6 class="mt-0 align-self-center">{{str_replace($voucher->coupon_code,'',$voucher->link_name)}}</h6></div>
                                            <form action="{{route('site.exit_click.store')}}" method="POST" id="form_voucher_{{$voucher->id}}" target="_blank">
                                                @csrf
                                                <input type="hidden" name="url" id="url" value="{{$voucher->click_url}}">
                                                <input type="hidden" name="store_id" id="store_id" value="{{$store->id}}">
                                                <input type="hidden" name="voucher_id" id="voucher_id" value="{{$voucher->id}}">
                                                <input type="hidden" name="user_id" id="user_id" value="{{Auth::id() ?? 0}}">

                                            </form>
                                           
                                            <div class="col-md-4 text-md-right">
                                                @if($voucher->promotion_type == 'Coupon' && $voucher->coupon_code)
                                                <div class="coupon-detail coupon-button-type"> 
                                                    <a form_id = "form_voucher_{{$voucher->id}}" url="{{$voucher->click_url}}" rel="nofollow" data-type="code" class="coupon-button coupon-code @guest voucher_form_guest @else voucher_form @endguest" data-code="{{$voucher->coupon_code}}">
                                                        <span class="code-text" rel="nofollow">{{$voucher->coupon_code}}</span> 
                                                        <span class="get-code">Get Code</span>
                                                    </a>
                                                </div>
                                                @else
                                                <a style="width: 145px" href="#"
                                                    @guest class="btn btn-primary btn-sm" 
                                                        data-toggle="modal" data-target="#signinModal" 
                                                    @else form_id = "form_voucher_{{$voucher->id}}" class="btn btn-primary btn-sm store_form"
                                                    @endguest 
                                                    target="_blank">Get Deal <i class="fas fa-external-link-alt ml-2"></i>
                                                </a>

                                                @endif

                                                <div>
                                                    <small>Valid until: {{Carbon\Carbon::parse($voucher->promotion_end_date)->isoFormat('Do MMMM YYYY')}}</small>
                                                </div>
                                            </div>
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
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="signinModalLabel">Get Cashback</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
            <h4>Do you want to receive a cashback at<br> <span class="color-primary">{{$store->name}}?</span></h4>

            <img style="max-width: 100px;" class="mx-auto my-4 d-block"
            @if($store->logo->count())
                @if($store->logo->first())
                    src="{{asset('storage/stores/images/'.$store->logo->first()->image)}}"
                @else
                    src="{{asset('frontend/images/products/product-16.jpg')}}" alt=""
                @endif
            
            @else
                    src="{{asset('frontend/images/products/product-16.jpg')}}" alt=""
                @endif
            >
            
           <h5>Get upto @if($store->cashback->type=='fixed'){{$store->cashback->currency}} @endif{{$store->cashback->sale_commission}}@if($store->cashback->type=='percentage')%@endif Cashback</h5>
        </div>
        <div class="modal-footer">
            <a href="{{route('account.login')}}?prvUrl={{ request()->fullUrl() }}"class="btn btn-primary">Yes, Ofcourse</a>
            <a href="#" onclick="document.getElementById('form_{{$store->id}}').submit()" class="btn btn-warning btn-dim">No, Continue without cashback</a>
          </div>
        
      </div>
    </div>
  </div>
  <!-- Modal -->
<div class="modal fade" id="voucherSigninModal" tabindex="-1" role="dialog" aria-labelledby="voucherSigninModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="signinModalLabel">Get Cashback</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
            <h4>Do you want to receive a cashback at<br> <span class="color-primary">{{$store->name}}?</span></h4>

            <img style="max-width: 100px;" class="mx-auto my-4 d-block"
            @if($store->logo->count())
                @if($store->logo->first())
                    src="{{asset('storage/stores/images/'.$store->logo->first()->image)}}"
                @else
                    src="{{asset('frontend/images/products/product-16.jpg')}}" alt=""
                @endif
            
            @else
                    src="{{asset('frontend/images/products/product-16.jpg')}}" alt=""
                @endif
            >
            
           <h5>Get upto @if($store->cashback->type=='fixed'){{$store->cashback->currency}} @endif{{$store->cashback->sale_commission}}@if($store->cashback->type=='percentage')%@endif Cashback</h5>
        </div>
        <div class="modal-footer">
            <a href="{{route('account.login')}}?prvUrl={{ request()->fullUrl() }}" class="btn btn-primary">Yes, Ofcourse</a>
            <a href="" form_id="" target="_blank" class="btn btn-warning btn-dim discard-btn" data-code="">No, Continue without cashback</a>
          </div>
        
      </div>
    </div>
  </div>
  <div class="modal fade" tabindex="-1" role="dialog" id="tracker" aria-labelledby="trackerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          {{-- <h5 class="modal-title">Modal title</h5> --}}
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center py-5">
            <svg style="width: 40px" class="fill-primary mb-3 mx-auto" id="icon-recorded-tick" viewBox="0 0 40 40"><path d="M17 27.556c-.444 0-.889-.112-1.222-.445-.667-.667-.667-1.667 0-2.333l11.889-11.89c.666-.666 1.666-.666 2.333 0 .667.668.667 1.668 0 2.334l-11.889 11.89c-.222.332-.667.444-1.111.444z"></path><path d="M17 27.556c-.444 0-.889-.112-1.222-.445l-5.89-5.889c-.666-.666-.666-1.666 0-2.333.668-.667 1.668-.667 2.334 0l5.89 5.889c.666.666.666 1.666 0 2.333-.223.333-.668.445-1.112.445z"></path><path d="M20 0C9 0 0 9 0 20s9 20 20 20 20-9 20-20S31 0 20 0zm0 35.556c-8.556 0-15.556-7-15.556-15.556S11.444 4.444 20 4.444s15.556 7 15.556 15.556-7 15.556-15.556 15.556z"></path></svg>
          <h2 class="color-primary">We recorded your visit</h2>
          <p><b>{{$store->name}}</b> was opened in another window.</p>
        </div>
        <div class="modal-footer">
          {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
          <button type="button" class="btn btn-primary" data-dismiss="modal">Okay</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" tabindex="-1" role="dialog" id="vouchertracker" aria-labelledby="vouchertrackerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{$store->name}} Voucher</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center py-5">
        <div class="recorded">
            <svg style="width: 40px" class="fill-primary mb-3 mx-auto" id="icon-recorded-tick" viewBox="0 0 40 40"><path d="M17 27.556c-.444 0-.889-.112-1.222-.445-.667-.667-.667-1.667 0-2.333l11.889-11.89c.666-.666 1.666-.666 2.333 0 .667.668.667 1.668 0 2.334l-11.889 11.89c-.222.332-.667.444-1.111.444z"></path><path d="M17 27.556c-.444 0-.889-.112-1.222-.445l-5.89-5.889c-.666-.666-.666-1.666 0-2.333.668-.667 1.668-.667 2.334 0l5.89 5.889c.666.666.666 1.666 0 2.333-.223.333-.668.445-1.112.445z"></path><path d="M20 0C9 0 0 9 0 20s9 20 20 20 20-9 20-20S31 0 20 0zm0 35.556c-8.556 0-15.556-7-15.556-15.556S11.444 4.444 20 4.444s15.556 7 15.556 15.556-7 15.556-15.556 15.556z"></path></svg>
            <h2 class="color-primary">We recorded your visit</h2>
            <p><b>{{$store->name}}</b> was opened in another window.</p>
        </div>
           

          <div class="dialog-modal__voucher pt-2 mt-4 text-center"><div class="dialog-modal__voucher-text font-weight-bold text-sm pb-3">
            Simply copy and paste this code at checkout
        </div><div class="dialog-modal__voucher-code font-weight-bold text-sm text-brand-blue p-3 mb-2">2025</div></div>
        </div>
        <div class="modal-footer">
          {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
          <button type="button" class="btn btn-primary" data-dismiss="modal">Okay</button>
        </div>
      </div>
    </div>
  </div>
@endsection


@push('scripts')
<script>
$('.store_form').on('click',function(e){
    e.preventDefault();
        $('#tracker').modal('show');
        var form = $(this).attr('form_id')
        $('#'+form).submit();
});

$('.voucher_form').on('click',function(e){
    e.preventDefault();
        $('#vouchertracker').modal('show');
        var form = $(this).attr('form_id');
        var code  = $(this).attr('data-code');
        $('.dialog-modal__voucher-code').text(code);
        $('#'+form).submit();
});


$('.voucher_form_guest').on('click',function(e){
    e.preventDefault();
        $('#voucherSigninModal').modal('show');
        var code  = $(this).attr('data-code');
        var form = $(this).attr('form_id');
        $('.discard-btn').attr('form_id',form);
        $('.discard-btn').attr('data-code',code);
        
});


$('.discard-btn').on('click',function(e){
    e.preventDefault();
    $('#voucherSigninModal').modal('hide');
   
        $('#vouchertracker').modal('show');
        $('.recorded').hide();
        var form = $(this).attr('form_id');
        var code  = $(this).attr('data-code');
        $('.dialog-modal__voucher-code').text(code);
        $('#'+form).submit();
});
</script>
@endpush