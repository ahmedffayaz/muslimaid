@extends('layouts.frontend.app')
@section('content')
<style>
    .product-card__rating{
        white-space:normal !important ;
    }
    b, strong{
        display: contents !important;
    }
    .product-card__name{
        font-weight: bold;
    }
</style>
<div class="page-header">
    <div class="page-header__container container">
        <div class="page-header__breadcrumb">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{url('/')}}">Home</a>
                        <svg class="breadcrumb-arrow" width="6px" height="9px">              
                            <use xlink:href="{{asset('frontend/images/sprite.svg')}}#arrow-rounded-right-6x9"></use>
                        </svg>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Charities</li>
                </ol>
            </nav>
        </div>
        <div class="container p-2 my-2">
            <div class="row">
                <div class="col-12">
                    <div class="block-finder__body">
                            <img class="banner__size" style="width:1110px;" src="{{url('storage/photos/static_image_banner.jpg')}}" alt="Blog Image Missing">
                            <div class="block-finder__header">
                                <div class="block-finder__title">Charities</div>
                                <div class="block-finder__subtitle"></div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="category-text panel mb-4 pt-3">
                <p>This is Charities Page Description</p>
            </div>
    </div>
</div>
@if(!isset($page->title)){{abort(404)}} @endif
<div class="block block-product-columns">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>{{$page->title}}</h1>
            </div>
            <div class="col-lg-12">
                <div id="your_container"> <!-- The element you want to render the content in -->
                    {!! $page->lb_content !!}
                   
                  </div>
            </div>
        </div>
    </div>
</div>
<div class="block block-product-columns d-lg-block d-none">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="block-header">
                    <h3 class="block-header__title">Top Charities</h3>
                    <div class="block-header__divider"></div>
                </div>
                <div class="row">
                    @foreach ($charity as $ch)
                        <div class="col-md-4">
                            <div class="block-product-columns__column pt-2">
                                <div class="block-product-columns__item" id="loadMore ">
                                    <div class="product-card product-card--hidden-actions product-card--layout--horizontal" >
                                        <button class="product-card__quickview" type="button">
                                            <svg width="16px" height="16px">
                                                <use xlink:href="images/sprite.svg#quickview-16"></use>
                                            </svg>
                                            <span class="fake-svg-icon"></span>
                                        </button>
                                        <div class="product-card__badges-list">
                                            <div class="product-card__badge product-card__badge--new "> {!! $ch->status ? '<span class="tb-status text-light">active</span>' : '<span class="tb-status text-light">inactive</span>'!!}
                                            </div>
                                        </div>
                                        <div class="product-card__image product-image">
                                            <a href="product.html" class="product-image__body">
                                                <img class="product-image__img" src="{{asset('storage/charities/images/'.$ch->logo_upload)}}" alt="">
                                            </a>
                                        </div>
                                        <div class="product-card__info">
                                            <div class="product-card__name">
                                                <a href="product.html">{{$ch->title}}</a>
                                            </div>
                                            <div class="product-card__rating">
                                              
                                               {!! Illuminate\Support\Str::limit($ch->description, 25 ) !!}
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach 
                </div>
            </div>
            <div class="nk-block-between-md g-3 p-5 card-inner">
                <div class="pagination g" >
                    {!! $charity->links()!!}                                               
                    </div> 
            </div><!-- .nk-block-between --> </div>
    </div>
</div>

@endsection

