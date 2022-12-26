@extends('layouts.frontend.app')

@push('styles')
    <style>
        .category-card__image {
            width: 100px;
        }

        @media (max-width: 767px){
        [dir=ltr] .block-slideshow__slide-content {
            width: 350px!important;
        }.block-slideshow__slide-text {
            display: block;
        }}
        @media (max-width: 416px){
        [dir=ltr] .block-slideshow__slide-content {
            width: 290px !important;
        }.block-slideshow__slide-text {
            display: block;
        }}

        @media(min-width: 768px){
                .testimonial-store-name{
                right: 20px;
                top: 20px;
                font-size: 18px;
                }
            }

        /*
        // .testimonial
        */
        .testimonial {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }

        .testimonial__avatar {
            -ms-flex-negative: 0;
            flex-shrink: 0;
        }

        [dir=ltr] .testimonial__avatar {
            margin-left: 16px;
            margin-right: 24px;
        }

        [dir=rtl] .testimonial__avatar {
            margin-right: 16px;
            margin-left: 24px;
        }

        .testimonial__avatar img {
            width: 70px;
            border-radius: 1000px;
        }

        .testimonial__author {
            margin-top: -4px;
            font-size: 16px;
            font-weight: 500;
        }

        .testimonial__position {
            margin-top: 3px;
            font-size: 15px;
            font-weight: 500;
        }

        .testimonial__text {
            font-size: 16px;
            margin-top: 12px;
        }

        @media (min-width: 576px) and (max-width: 767px) {
            [dir=ltr] .testimonial__avatar {
                margin-right: 18px;
            }
            [dir=rtl] .testimonial__avatar {
                margin-left: 18px;
            }
            .testimonial__avatar img {
                width: 60px;
            }
        }

        @media (max-width: 575px) {
            .testimonial__avatar {
                display: none;
            }
        }

        /*
        // .testimonials-list
        */
        .testimonials-list__content {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .testimonials-list__item {
            border-bottom: 1px solid #ebebeb;
            padding-top: 28px;
            padding-bottom: 24px;
        }

        .testimonials-list__item:first-child {
            padding-top: 0;
        }

        .testimonials-list__pagination {
            margin-top: 36px;
        }

        @media (max-width: 767px) {
            .testimonials-list__pagination {
                margin-top: 30px;
            }
        }

        .pagination {
            justify-content: center!important;
        }
    </style>
@endpush

@section('content')
@include('flash::message')

@auth
@if($slider)
   <!-- .block-slideshow -->
   <!-- <div class="block-header__title text-center"><span>Welcome back to Cashback</span></div> -->
   <div class="block-slideshow block-slideshow--layout--full block mt-5">
    <div class="container">

        <div class="row">
            <div class="col-12">
                <div class="block-slideshow__body">
                    <div class="owl-carousel">
                        @foreach ($slider->slides as $slide)
                        @if($slide->store)
                            @if($slide->store->cashback)
                        <a class="block-slideshow__slide" href="{{route('store.show',$slide->store->slug)}}">

                            @if($slide->banner == 'default1.png' || $slide->banner == 'default2.png' || $slide->banner == 'default3.png')
                            <div class="block-slideshow__slide-image block-slideshow__slide-image--desktop" style="background-image: url({{asset('frontend/images/slides/'.$slide->banner)}})"></div>
                            @else
                            <div class="block-slideshow__slide-image block-slideshow__slide-image--desktop" style="background-image: url({{asset('storage/slider/slides/images/'.$slide->banner)}})"></div>
                            @endif

                            @if($slide->banner == 'default1.png' || $slide->banner == 'default2.png' || $slide->banner == 'default3.png')
                            <div class="block-slideshow__slide-image block-slideshow__slide-image--mobile" style="background-image: url({{asset('frontend/images/slides/'.$slide->banner)}})"></div>
                            @else
                            <div class="block-slideshow__slide-image block-slideshow__slide-image--mobile" style="background-image: url({{asset('storage/slider/slides/images/'.$slide->banner)}})"></div>
                            @endif


                            <div class="block-slideshow__slide-content" style="background-color:hsla(0,0%,100%,.92); width:450px">
                                <div class="block-slideshow__slide-title" style="padding:20px; background-color:white; margin:0">

                                    @if($slide->logo == 'default1.png' || $slide->logo == 'default2.png' || $slide->logo == 'default3.png')
                                        <img style="height: auto; width: 100px; border: 1px solid #dfdfdf;border-radius: 2px;"
                                        src="{{asset('frontend/images/slides/logo/'.$slide->logo)}}" alt="" class="slider_pic">
                                    @else
                                        <img style="height: auto; width: 100px; border: 1px solid #dfdfdf;border-radius: 2px;"
                                        src="{{asset('storage/slider/slides/images/'.$slide->logo)}}" alt="">
                                    @endif
                                    <span class="slider-store-name">{{$slide->store->name}}</span>
                                </div>
                                <div class="block-slideshow__slide-title" style="padding:30px 20px 0px 20px; margin:0">

                                    @if($slide->store->custom_cashback_percentage)

                                            @if($slide->store->cashback->type=='fixed'){{$slide->store->cashback->currency}}@endif{{($slide->store->custom_cashback_percentage/100)*$slide->store->cashback->sale_commission}}@if($slide->store->cashback->type=='percentage')%@endif

                                    @else

                                            @if($slide->store->cashback->type=='fixed'){{$slide->store->cashback->currency}}@endif{{(SiteSetting()['cashback_percentage']/100)*$slide->store->cashback->sale_commission}}@if($slide->store->cashback->type=='percentage')%@endif

                                    @endif
                                      Cashback
                                </div>
                                <div class="block-slideshow__slide-text" style="padding:10px 20px 30px 20px; ">{{$slide->description}}</div>

                            </div>
                        </a>
                        @endif
                        @endif
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

@if($testimonials)
    @if (count($testimonials) > 0)
        <!-- testimonial -->
        <div class="mt-2 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="testimonials-view">
                            <div class="testimonials-view__list">
                                <div class="testimonials-list">
                                    <ol class="testimonials-list__content">
                                        @foreach ($testimonials as $testimonial)
                                            <li class="testimonials-list__item">
                                                <div class="testimonial">
                                                    <div class="testimonial__avatar"><img src="{{asset('storage/users/images/avatar/' . $testimonial->image )}}"></div>
                                                    <div class="testimonial__content">
                                                        <div class="testimonial__author">{{ $testimonial->name }}</div>
                                                        <div class="testimonial__position">{{ $testimonial->position . ', ' . $testimonial->company }}</div>
                                                        <div class="testimonial__text">{{ $testimonial->description }}</div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ol>
                                    <div class="testimonials-list__pagination">
                                    {{ $testimonials->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- testimonial / end -->
    @endif
@endif

@guest
<!-- .block-slideshow -->
 <div class="block home-header block--highlighted pt-5" style="background: linear-gradient(rgba(0,0,0,.4), rgba(0,0,0,.4)),
    url('{{asset('frontend/images/home_background.jpg')}}'); z-index:-1; background-repeat: no-repeat; background-size: 100% 100%;">
    <div class="container">
        <div class="row">
            {{-- <div class="col-lg-3 d-none d-lg-block"></div> --}}
        @guest
        <div class="col-md-6 align-self-center" style="z-index:1; color:white">
            <h1>Get cashback shopping at 500+ popular brands</h1>
            <p>Join for free with over 15 million members saving hundreds of pounds each year from all the top 5,000 online retailers.</p>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-5 d-flex flex-column mt-4">
            <div class="card flex-grow-1 mb-0  shadow">
                <div class="card-body">
                    <h3 class="card-title">Sign up for free</h3>
                    <form method="POST" action="{{ route('register') }}">
                         @csrf
                         <input type="hidden" class="form-control form-control-lg" id="firstname" placeholder="Enter your first name" name="firstname" value="unnamed">
                         <input type="hidden" class="form-control form-control-lg" id="lastname" placeholder="Enter your last name" name="lastname" value="unnamed" >

                         <div class="form-group">
                             <label>Email address</label>
                             <input type="email" class="form-control" placeholder="Enter email" name="email" required>
                             @error('email')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                         </div>
                         <div class="form-group">
                             <label>Password</label>
                             <input type="password" class="form-control" placeholder="Password" name="password" required>
                             @error('password')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                         </div>
                         <div class="form-group">
                             <label>Repeat Password</label>
                             <input type="password" class="form-control" placeholder="Password" name="password_confirmation" required>
                         </div>
                         <button type="submit" class="btn btn-primary mt-1">Join now for free</button>
                    </form>
                    @if(isFacebookEnabled() || isGoogleEnabled())
                        <div>
                            <hr/>
                            @if(isFacebookEnabled())
                                <a class="btn btn-primary border-0 fb-button" style="background-color: #3b5998; border-radius: 2px" href="{{ url('/login/facebook') }}" role="button">
                                    <i class="fab fa-facebook-f"></i> Join with Facebook</a>
                            @endif
                            @if(isGoogleEnabled())
                                <a class="btn btn-primary border-0 ml-0 g-button" style="background-color: #dd4b39; border-radius: 2px" href="{{ url('/login/google') }}" role="button">
                                    <i class="fab fa-google"></i> Join with Google</a>
                            @endif
                        </div>
                    @endif
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
@if(count($stores))
<div class="block block--highlighted">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="block ">
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

                            </div>
                        </div>
                        <a href="{{url('/pages/offers')}}" class="btn btn-primary mt-5 mx-auto d-table">More Cashback</a>
                    </div>

                </div>


            </div>
        </div>

    </div>
</div>
@endif
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
<div class="block block--highlighted block-categories block-categories--layout--classic mb-5">
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

                            <img src="{{asset('frontend/images/categories/images/'.$category->logo_upload)}}"  alt="">

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
