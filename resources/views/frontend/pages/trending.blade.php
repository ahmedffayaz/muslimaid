@extends('layouts.frontend.app')
@section('content')
<style>/*  bhoechie tab */
    div.bhoechie-tab-container{
      z-index: 10;
      background-color: #ffffff;
      padding: 0 !important;
      border-radius: 4px;
      -moz-border-radius: 4px;
      border:1px solid #ddd; 
      -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
      box-shadow: 0 6px 12px rgba(0,0,0,.175);
      -moz-box-shadow: 0 6px 12px rgba(0,0,0,.175);
      background-clip: padding-box;
      opacity: 0.97;
      filter: alpha(opacity=97);
    }
    div.bhoechie-tab-menu{
      padding-right: 0;
      padding-left: 0;
      padding-bottom: 0;
    }
    div.bhoechie-tab-menu div.list-group{
      margin-bottom: 0;
    }
    div.bhoechie-tab-menu div.list-group>a{
      margin-bottom: 0;
    }
    div.bhoechie-tab-menu div.list-group>a .glyphicon,
    div.bhoechie-tab-menu div.list-group>a .fa {
      color: #3366cc;
    }
    div.bhoechie-tab-menu div.list-group>a:first-child{
      border-top-right-radius: 0;
      -moz-border-top-right-radius: 0;
    }
    div.bhoechie-tab-menu div.list-group>a:last-child{
      border-bottom-right-radius: 0;
      -moz-border-bottom-right-radius: 0;
    }
    div.bhoechie-tab-menu div.list-group>a.active,
    div.bhoechie-tab-menu div.list-group>a.active .glyphicon,
    div.bhoechie-tab-menu div.list-group>a.active .fa{
      background-color: transparent;
      background-image: #3366cc;
      color: #ffffff;
      border-radius: 0;
    }
    div.bhoechie-tab-menu div.list-group>a.active:after{
    content:'';
    background-color: #fff;
    box-shadow: -2px 3px 2px 0 rgb(0 0 0 / 5%);
    display: block;
    height: 16px;
    left: -8px;
    position: absolute;
    top: 16px;
    transform: rotate( 45deg );
    width: 16px;
    z-index: 1;
    }
    
    div.bhoechie-tab-content{
      background-color: #ffffff;
      /* border: 1px solid #eeeeee; */
      padding-left: 20px;
    }
    
    div.bhoechie-tab div.bhoechie-tab-content:not(.active){
      display: none;
    }
    @media (min-width: 375px){
[dir=ltr] .products-list[data-layout="grid-5-full"][data-mobile-grid-columns="2"] .product-card .product-card__badges-list {
    left: 0px;
    top:0px;
}}
.product-image__img {
    max-height:100px;
}
</style>


<div class="block mt-5">
    
    <div class="container">
        <div class="row">
            <div class="col-12 mt-5">
                <h1 class="text-center">Trending cashback deals</h1>
                
                @if($stores->count())
                <div class="block">
                    <div class="block-header">
                       
                        
                    </div>
                    <div class="products-view">
                        <div class="products-view__list products-list scrolling-pagination" data-layout="grid-5-full" data-with-features="false" data-mobile-grid-columns="2">
                            <div class="products-list__body ">
                                @foreach ($stores->take(20) as $store)
                                    
                               
                                <div class="products-list__item text-center">
                                    <div class="product-card ">
                                        <div class="product-card__badges-list">
                                            <div class="product-card__badge product-card__badge--new">{{$store->clicks->count()}} clicks today</div>
                                        </div>
                                        <div class="product-card__image product-image">
                                            <a href="{{route('store.show',$store->slug)}}" class="product-image__body" style="padding-bottom:100px;padding-top:20px;">
                                                <img class="product-image__img" 
                                                @if($store->logo->first())
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
                                            <div class="product-card__rating mx-auto">
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
                                                @if($store->cashback->type=='fixed'){{$store->cashback->currency}} @endif{{$store->cashback->sale_commission}}@if($store->cashback->type=='percentage')%@endif Cashback
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                {{-- {!! $stores->links()!!}  --}}
                            </div>
                        </div>
                       
                    </div>
                </div>
                @else
                <h2>No Cashback Found</h2>
                @endif
            </div>
        </div>
        
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
    $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });
});
</script>    
<script type="text/javascript">
    $('ul.pagination').hide();
    $(function() {
        $('.scrolling-pagination').jscroll({
            autoTrigger: true,
            loadingHtml: `<div class="form-group text-center mt-4">
                                        <button class="btn btn-light text-primary btn-loading btn-xl btn-svg-icon">
                                            <svg width="16px" height="16px">
                                                <use xlink:href="{{asset('frontend/images/sprite.svg')}}#quickview-16"></use>
                                            </svg>
                                        </button>
                                    </div>`,
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.scrolling-pagination',
            callback: function() {
                $('ul.pagination').remove();
            }
        });
    });
</script>
@endpush