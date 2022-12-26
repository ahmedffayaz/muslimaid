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
    
    .shine {
    background: #f6f7f8;
    background-image: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
    background-repeat: no-repeat;
    background-size: 800px 230px; 
    display: inline-block;
    position: relative; 
    
    -webkit-animation-duration: 1s;
    -webkit-animation-fill-mode: forwards; 
    -webkit-animation-iteration-count: infinite;
    -webkit-animation-name: placeholderShimmer;
    -webkit-animation-timing-function: linear;
    }
    .block-finder__body {
        background: none no-repeat;
    }
    .block-finder__title{
    background-color: #fff;
    padding: 0.5rem 1rem;
    position: absolute;
    left: 3rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.5rem;
    }
    .box {
       height: 230px;
        margin: 8px 3px;
    }
    .panel {
    padding: 1.2rem;
    background-color: #fff;
    text-align: left;
}
.rounded-border {
    border: 1px solid #d9d9d9;
}
.block-finder__header{
    padding: 1px;
}
.size{
   
}
@-webkit-keyframes placeholderShimmer {
  0% {
    background-position: -468px 0;
  }
  
  100% {
    background-position: 468px 0; 
  }
}
</style>

<div class="container p-2 my-2 mt-2">
    <div class="row">
        <div class="col-12">
          <div class="block-finder__body">
             <img class="banner__size" src="{{asset('storage/categories/images/'.$category->banner_upload)}}" alt="">
                <div class="block-finder__header">
                   <div class="block-finder__title">{{$category->name}}</div>
                   <div class="block-finder__subtitle"></div>
                </div>  
         </div> 
            <div class="panel  mb-4 my-2">
               <p>{{$category->description}}</p>
            </div>   
        </div>
    </div>
</div>

<div class="block mt-5">
    
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">{{$category->name}}</h1>
                <p  class="text-center">{!!$category->description!!}</p>
                @if($category->picks->count())
                
                <div class="row bhoechie-tab-container my-5 col-lg-10 mx-auto">
                    
                    <div class="col-lg-8 col-md-8 bhoechie-tab align-self-center">
                        <!-- flight section -->
                        @foreach ($category->picks->take(5) as $key=>$pick)
                            
                        
                        <div class="bhoechie-tab-content @if($key==0) active @endif">
                            <div class="row">
                                <div class="col-lg-12 align-self-center py-4">
                                    <img style="max-height: 50px;" class="mb-2" @if($pick->store->logo->first())
                                    src="{{asset('storage/stores/images/'.$pick->store->logo->first()->image)}}"
                                    @else
                                    src="{{asset('frontend/images/products/product-16.jpg')}}" 
                                    @endif alt="">
                                    <h4>{{$pick->store->name}}</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                        Maecenas fermentum, diam non iaculis finibus, ipsum arcu sollicitudin dolor. 
                                        </p>
                                        <p> 
                                            <b> @if($pick->store->cashback->type=='fixed'){{$pick->store->cashback->currency}} @endif{{$pick->store->cashback->sale_commission}}@if($pick->store->cashback->type=='percentage')%@endif Cashback</b>
                                        </b>
                                    </p>
                                    <a href="{{route('store.show',$pick->store->slug)}}" class="btn btn-primary">Shop Now</a>
                                </div>
                               
                            </div>
                        </div>
                        @endforeach
            
                    </div>
                    
                    <div class="col-lg-4 col-md-4 bhoechie-tab-menu align-self-center">
                        <div class="list-group">
                            @foreach ($category->picks->take(5) as $key=>$pick)
                            <a href="#" class="list-group-item @if($key==0) active @endif text-center">
                            <img style="max-height: 40px;" class=""  @if($pick->store->logo->first())
                            @if($pick->store->logo->first()->is_fake)
                                src="{{asset('frontend/images/logos/'.$pick->store->logo->first()->image)}}"
                            @else
                                src="{{asset('storage/stores/images/'.$pick->store->logo->first()->image)}}"
                            @endif
                        @else
                            src="{{asset('frontend/images/products/product-16.jpg')}}" 
                        @endif alt="">
                                    <p class="small mb-0 text-dark mt-1"> @if($pick->store->cashback->type=='fixed'){{$pick->store->cashback->currency}} @endif{{$pick->store->cashback->sale_commission}}@if($pick->store->cashback->type=='percentage')%@endif Cashback</p>
                            </a>

                            @endforeach
                        </div>
                        </div>
                </div>
                 
                @endif
               
                @if($stores->count())
                <div class="block">
                    <div class="block-header">
                    </div>
                    <div class="products-view">
                        <div class="products-view__list products-list scrolling-pagination" data-layout="grid-5-full" data-with-features="false" data-mobile-grid-columns="2">
                            <div class="products-list__body ">
                                @foreach ($stores as $store)
                                    
                               
                                <div class="products-list__item text-center">
                                    <div class="product-card ">
                                        <div class="product-card__image product-image">
                                            <a href="{{route('store.show',$store->slug)}}" class="product-image__body" style="padding-bottom:100px">
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
                                               
                                                @if($store->custom_cashback_percentage)
                                                    @if($store->cashback->type=='fixed'){{$store->cashback->currency}}@endif{{($store->custom_cashback_percentage/100)*$store->cashback->sale_commission}}@if($store->cashback->type=='percentage')%@endif
                                                @else
                                                    @if($store->cashback->type=='fixed'){{$store->cashback->currency}}@endif{{(SiteSetting()['cashback_percentage']/100)*$store->cashback->sale_commission}}@if($store->cashback->type=='percentage')%@endif
                                                @endif
                                                 Cashback
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                {!! $stores->links()!!} 
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
            loadingHtml: ` <div class="products-list__body"> 
                        <box class="shine products-list__item"></box>
                        <box class="shine products-list__item"></box>
                        <box class="shine products-list__item"></box>
                        <box class="shine products-list__item"></box>
                        <box class="shine products-list__item"></box>
                        
                        <box class="shine products-list__item"></box>
                        <box class="shine products-list__item"></box>
                        <box class="shine products-list__item"></box>
                        <box class="shine products-list__item"></box>
                        <box class="shine products-list__item"></box>
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