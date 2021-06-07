@extends('layouts.frontend.app')
@section('content')
<style>.category-card__image {
    width: 100px;
}</style>
@auth
@if($slider)
   <!-- .block-slideshow -->
   <div class="block-slideshow block-slideshow--layout--full block mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="block-slideshow__body">
                    <div class="owl-carousel">
                        @foreach ($slider->slides as $slide)
                        <a class="block-slideshow__slide" href="{{route('store.show',$slide->store->slug)}}">
                            <div class="block-slideshow__slide-image block-slideshow__slide-image--desktop" style="background-image: url({{asset('storage/slider/slides/images/'.$slide->banner)}})"></div>
                            <div class="block-slideshow__slide-image block-slideshow__slide-image--mobile" style="background-image: url({{asset('storage/slider/slides/images/'.$slide->banner)}})"></div>
                            <div class="block-slideshow__slide-content" style="background-color:hsla(0,0%,100%,.92); width:450px">
                                <div class="block-slideshow__slide-title" style="padding:20px; background-color:white; margin:0">
                                    <img style="height: auto; width: 100px; border: 1px solid #dfdfdf;border-radius: 2px;" 
                                    src="{{asset('storage/slider/slides/images/'.$slide->logo)}}" alt="">
                                    <span class="slider-store-name">{{$slide->store->name}}</span>
                                </div>
                                <div class="block-slideshow__slide-title" style="padding:30px 20px 0px 20px; margin:0">
                                    @if($slide->store->cashback->type=='fixed'){{$slide->store->cashback->currency}}@endif
                                    {{$slide->store->cashback->sale_commission}}
                                    @if($slide->store->cashback->type=='percentage')%@endif Cashback 
                                 
                                </div>
                                <div class="block-slideshow__slide-text" style="padding:10px 20px 30px 20px; ">{{$slide->description}}</div>
                               
                            </div>
                        </a>
                        @endforeach
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- .block-slideshow / end -->
@endif
@endauth
@guest
<!-- .block-slideshow -->
 <div class="block home-header block--highlighted pt-5">
    <div class="container">
        <div class="row">
            {{-- <div class="col-lg-3 d-none d-lg-block"></div> --}}
        @guest
        <div class="col-md-6 align-self-center">
            <h1>Get cashback shopping at 4,500+ popular brands</h1>
            <p>Join for free with over 15 million members saving hundreds of pounds each year from all the top 5,000 online retailers.</p>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-5 d-flex flex-column mt-4">
             <div class="card flex-grow-1 mb-0  shadow">
                 <div class="card-body">
                     <h3 class="card-title">Sign up for free</h3>
                     <form method="POST" action="{{ route('register') }}">
                         @csrf
                         <input type="hidden" class="form-control form-control-lg" id="firstname" placeholder="Enter your first name" name="firstname" value="John">
                         <input type="hidden" class="form-control form-control-lg" id="lastname" placeholder="Enter your last name" name="lastname" value="Doe" >

                         <div class="form-group">
                             <label>Email address</label>
                             <input type="email" class="form-control" placeholder="Enter email" name="email">
                         </div>
                         <div class="form-group">
                             <label>Password</label>
                             <input type="password" class="form-control" placeholder="Password" name="password">
                         </div>
                         <div class="form-group">
                             <label>Repeat Password</label>
                             <input type="password" class="form-control" placeholder="Password" name="password_confirmation" >
                         </div>
                         <button type="submit" class="btn btn-primary mt-4">Join now for free</button>
                     </form>
                 </div>
             </div>
         </div>
        @else 
        <div class="col-md-12 align-self-center text-center" style="padding:100px 0px">
            <h1>Get cashback shopping at 4,500+ popular brands</h1>
            <p>Join for free with over 15 million members saving hundreds of pounds each year from all the top 5,000 online retailers.</p>
            <a href="{{route('offers')}}"class="btn btn-primary">Browse Offers<a>

        </div>
        @endguest
               
           
        </div>
    </div>
</div>
<!-- .block-slideshow / end -->

<div class="block text-center my-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-5">
                <h1 class="text-center">Cashback is quick, rewarding, and flexible</h1>
            </div>
            <div class="col-md-4 p-lg-5">
                <img src="{{asset('frontend/images/pages/shopping.png')}}" alt="" width="100%">
                <h5>Quick and easy</h5>
                <p>So you can get on with your shopping.</p>
            </div>
            
            <div class="col-md-4 p-lg-5">
                <img src="{{asset('frontend/images/pages/add_up.png')}}" alt="" width="100%">
                <h5>It all adds up</h5>
                <p>Members earn on average £345 cashback a year.</p>
            </div>
            <div class="col-md-4 p-lg-5">
                <img src="{{asset('frontend/images/pages/payout.png')}}" alt="" width="100%">
                <h5>Payout how you want</h5>
                <p>Get your money directly to your bank account, PayPal, or Gift Cards.</p>
            </div>
            
        </div>
    </div>
</div>
@endguest
<!-- Cashbacks -->
@isset($stores)
<div class="block">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="block">
                    <div class="block-header">
                        <h3 class="block-header__title">Featured Stores</h3>
                        <div class="block-header__divider"></div>
                    </div>
                    <div class="products-view">
                        <div class="products-view__list products-list" data-layout="grid-5-full" data-with-features="false" data-mobile-grid-columns="2">
                            <div class="products-list__body">
                                @foreach ($stores->take(15) as $store)
                                    
                               
                                <div class="products-list__item text-center">
                                    <div class="product-card ">
                                        
                                        <div class="product-card__image product-image">
                                            <a href="{{route('store.show',$store->slug)}}" class="product-image__body" style="padding-bottom:100px">
                                                <img class="product-image__img" 
                                                @if($store->logo->first())
                                src="{{asset('storage/stores/images/'.$store->logo->first()->image)}}"
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
                                
                            </div>
                        </div>
                        <a href="{{url('/offers')}}" class="btn btn-primary mt-5 mx-auto d-table">More Cashback</a>
                    </div>

                </div>
                

            </div>
        </div>
        
    </div>
</div>
@endisset
<!-- .block-brands -->
{{-- <div class="block block-brands">
    <div class="container">
        <div class="block-brands__slider">
            <div class="owl-carousel">
                <div class="block-brands__item">
                    <a href=""><img src="{{ asset('frontend/images/logos/logo-1.png')}}" alt=""></a>
                </div>
                <div class="block-brands__item">
                    <a href=""><img src="{{ asset('frontend/images/logos/logo-2.png')}}" alt=""></a>
                </div>
                <div class="block-brands__item">
                    <a href=""><img src="{{ asset('frontend/images/logos/logo-3.png')}}" alt=""></a>
                </div>
                <div class="block-brands__item">
                    <a href=""><img src="{{ asset('frontend/images/logos/logo-4.png')}}" alt=""></a>
                </div>
                <div class="block-brands__item">
                    <a href=""><img src="{{ asset('frontend/images/logos/logo-5.png')}}" alt=""></a>
                </div>
                <div class="block-brands__item">
                    <a href=""><img src="{{ asset('frontend/images/logos/logo-6.png')}}" alt=""></a>
                </div>
                <div class="block-brands__item">
                    <a href=""><img src="{{ asset('frontend/images/logos/logo-7.png')}}" alt=""></a>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<!-- .block-brands / end -->

<!-- .block-categories -->
<div class="block block--highlighted block-categories block-categories--layout--classic mb-0">
    <div class="container">
        <div class="block-header">
            <h3 class="block-header__title">Featured Categories</h3>
            <div class="block-header__divider"></div>
        </div>
        <div class="block-categories__list">
            
            @foreach ($featured_categories->take(6) as $category)
                
          
            <div class="block-categories__item category-card category-card--layout--classic">
                <div class="category-card__body">
                    <div class="category-card__image">
                        <a href="{{route('cashabck',$category->slug)}}">
                            @if($category->logo_type == 'upload')
        
                            <img src="{{asset('storage/categories/images/'.$category->logo_upload)}}"  alt="">
                          
                            @elseif($category->logo_type == 'link')
                            
                            <img src="{{$category->logo_link}}"  alt="">
                         
                            @endif</a>
                    </div>
                    <div class="category-card__content">
                        <div class="category-card__name">
                            <a href="{{route('cashabck',$category->slug)}}">{{$category->name}}</a>
                        </div>
                        @if(count($category->childs))
                        <ul class="category-card__links">
                            @foreach ($category->childs->take(5) as $item)
                            <li><a href="{{route('cashabck',$item->slug)}}">{{$item->name}}</a></li>
                            @endforeach
                           
                            
                        </ul>
                        @endif
                       
                        {{-- <div class="category-card__all">
                            <a href="">Show All</a>
                        </div>
                        <div class="category-card__products">
                            572 Products
                        </div> --}}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- .block-categories / end -->


@endsection