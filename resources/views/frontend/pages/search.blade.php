@extends('layouts.frontend.app')
@section('content')
<style>.products-list[data-layout="list"] .product-card .product-card__image{
    width:150px;
}
.products-list[data-layout="list"] .product-card .product-card__name {
    font-size: 18px;
    line-height: 24px;
    font-weight: 500;
}
.products-list[data-layout="list"] .product-card .product-card__actions {
    width: 220px;
    text-align: center;
}.products-list[data-layout="grid-3-sidebar"] .product-card--hidden-actions:hover, .products-list[data-layout="grid-4-full"] .product-card--hidden-actions:hover {
    margin-bottom: auto;
}</style>
<div class="page-header mt-3">
    <div class="page-header__container container">
        {{-- <div class="page-header__breadcrumb">
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
                    <li class="breadcrumb-item active" aria-current="page">Screwdrivers</li>
                </ol>
            </nav>
        </div> --}}
        @if($term)
        <div class="page-header__title">
            <h1>Search results for "{{$term}}"</h1>
        </div>
        @endif
    </div>
</div>
<div class="container">
    <div class="shop-layout shop-layout--sidebar--start">
        @include('frontend.stores.sidebar')
        <div class="shop-layout__content">
            <div class="block">
                <div class="products-view">
                    <div class="products-view__options">
                        <div class="view-options view-options--offcanvas--mobile">
                            <div class="view-options__layout">
                                <div class="layout-switcher">
                                    <div class="layout-switcher__list">
                                        {{-- <button data-layout="grid-3-sidebar" data-with-features="false" title="Grid" type="button" class="layout-switcher__button ">
                                            <svg width="16px" height="16px">
                                                <use xlink:href="{{asset('frontend/images/sprite.svg')}}#layout-grid-16x16"></use>
                                            </svg>
                                        </button> --}}
                                        {{-- <button data-layout="list" data-with-features="false" title="List" type="button" class="layout-switcher__button  layout-switcher__button--active ">
                                            <svg width="16px" height="16px">
                                                <use xlink:href="{{asset('frontend/images/sprite.svg')}}#layout-list-16x16"></use>
                                            </svg>
                                        </button> --}}
                                    </div>
                                </div>
                            </div>
                            @if($term)
                            <div class="view-options__legend">We found {{$stores->total()}} results for <b>{{$term}}</b></div>
                            <div class="view-options__divider"></div>
                            @endif
                        </div>
                    </div>
                    <div class="products-view__list products-list  scrolling-pagination" data-layout="list" data-with-features="false" data-mobile-grid-columns="2">
                        <div class="products-list__body">
                            @foreach($stores as $store)
                                <div class="products-list__item">
                                    <div class="product-card product-card--hidden-actions ">
                                        <div class="product-card__image product-image">
                                            <a href="{{route('store.show',$store->slug)}}" class="product-image__body">
                                                <img class="product-image__img"  @if($store->logo->first())
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
                                        <div class="product-card__info align-self-center">
                                            <div class="product-card__name text-center text-lg-left">
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
                                                @if($store->cashback)
                                                @if($store->cashback->type=='fixed'){{$store->cashback->currency}} @endif{{$store->cashback->sale_commission}}@if($store->cashback->type=='percentage')%@endif Cashback
                                           @endif
                                            </div>
                                            <div class="product-card__buttons mt-2">
                                                {{-- <button class="btn btn-primary product-card__addtocart" type="button">Shop Now</button> --}}
                                                <a href="{{route('store.show',$store->slug)}}" class="btn btn-primary product-card__addtocart product-card__addtocart--list m-auto" type="button">Shop Now</a>
                                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {!! $stores->links()!!} 
                    </div>
                    {{-- <div class="products-view__pagination">
                        
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
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