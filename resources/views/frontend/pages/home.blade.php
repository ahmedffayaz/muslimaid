@extends('layouts.frontend.app')

@push('styles')
    <style>
        .category-card__image {
            width: 100px;
        }

        @media (max-width: 767px) {
            [dir=ltr] .block-slideshow__slide-content {
                width: 350px !important;
            }

            .block-slideshow__slide-text {
                display: block;
            }
        }

        @media (max-width: 416px) {
            [dir=ltr] .block-slideshow__slide-content {
                width: 290px !important;
            }

            .block-slideshow__slide-text {
                display: block;
            }
        }

        @media(min-width: 768px) {
            .testimonial-store-name {
                right: 20px;
                top: 20px;
                font-size: 18px;
            }
        }


    </style>
@endpush

@section('content')
    @include('flash::message')
    @auth
        @if ($slider)
            <!-- .block-slideshow -->
            <div class="block-slideshow block-slideshow--layout--full block mt-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="block-slideshow__body">
                                <div class="owl-carousel">
                                    @foreach ($slider->slides as $slide)
                                        @if ($slide->store)
                                            @if ($slide->store->cashback)
                                                <a class="block-slideshow__slide"
                                                    href="{{ route('store.show', $slide->store->slug) }}">

                                                    @if ($slide->banner == 'default1.png' || $slide->banner == 'default2.png' || $slide->banner == 'default3.png')
                                                        <div class="block-slideshow__slide-image block-slideshow__slide-image--desktop"
                                                            style="background-image: url({{ asset('frontend/images/slides/' . $slide->banner) }})">
                                                        </div>
                                                    @else
                                                        <div class="block-slideshow__slide-image block-slideshow__slide-image--desktop"
                                                            style="background-image: url({{ asset('storage/slider/slides/images/' . $slide->banner) }})">
                                                        </div>
                                                    @endif

                                                    @if ($slide->banner == 'default1.png' || $slide->banner == 'default2.png' || $slide->banner == 'default3.png')
                                                        <div class="block-slideshow__slide-image block-slideshow__slide-image--mobile"
                                                            style="background-image: url({{ asset('frontend/images/slides/' . $slide->banner) }})">
                                                        </div>
                                                    @else
                                                        <div class="block-slideshow__slide-image block-slideshow__slide-image--mobile"
                                                            style="background-image: url({{ asset('storage/slider/slides/images/' . $slide->banner) }})">
                                                        </div>
                                                    @endif

                                                    <div class="block-slideshow__slide-content"
                                                        style="background-color:hsla(0,0%,100%,.92); width:450px">
                                                        <div class="block-slideshow__slide-title"
                                                            style="padding:20px; background-color:white; margin:0">

                                                            @if ($slide->logo == 'default1.png' || $slide->logo == 'default2.png' || $slide->logo == 'default3.png')
                                                                <img style="height: auto; width: 100px; border: 1px solid #dfdfdf;border-radius: 2px;"
                                                                    src="{{ asset('frontend/images/slides/logo/' . $slide->logo) }}"
                                                                    alt="" class="slider_pic">
                                                            @else
                                                                <img style="height: auto; width: 100px; border: 1px solid #dfdfdf;border-radius: 2px;"
                                                                    src="{{ asset('storage/slider/slides/images/' . $slide->logo) }}"
                                                                    alt="">
                                                            @endif
                                                            <span class="slider-store-name">{{ $slide->store->name }}</span>
                                                        </div>
                                                        <div class="block-slideshow__slide-title"
                                                            style="padding:30px 20px 0px 20px; margin:0">

                                                            @if ($slide->store->custom_cashback_percentage)
                                                                @if ($slide->store->cashback->type == 'fixed')
                                                                    {{ $slide->store->cashback->currency }}
                                                                @endif
                                                                {{ ($slide->store->custom_cashback_percentage / 100) * $slide->store->cashback->sale_commission }}
                                                                @if ($slide->store->cashback->type == 'percentage')
                                                                    %
                                                                @endif
                                                            @else
                                                                @if ($slide->store->cashback->type == 'fixed')
                                                                    {{ $slide->store->cashback->currency }}
                                                                @endif
                                                                {{ (SiteSetting()['cashback_percentage'] / 100) * $slide->store->cashback->sale_commission }}
                                                                @if ($slide->store->cashback->type == 'percentage')
                                                                    %
                                                                @endif
                                                                Cashback
                                                            @endif
                                                        </div>
                                                        <div class="block-slideshow__slide-text"
                                                            style="padding:10px 20px 30px 20px; ">{{ $slide->description }}
                                                        </div>
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

    @guest
        <!-- .block-slideshow -->
        <div class="block home-header block--highlighted pt-5"
            style="background: linear-gradient(rgba(0,0,0,.4), rgba(0,0,0,.4)), url('{{ asset('frontend/images/home_background.jpg') }}'); z-index:-1; background-repeat: no-repeat; background-size: 100% 100%;">
            <div class="container">
                <div class="row">
                    @guest
                        <div class="col-md-6 align-self-center" style="z-index:1; color:white">
                            <h1>Get cashback shopping at 500+ popular brands</h1>
                            <p>Join for free with over 15 million members saving hundreds of pounds each year from all the top 5,000
                                online retailers.</p>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-5 d-flex flex-column mt-4">
                            <div class="card flex-grow-1 mb-0  shadow">
                                <div class="card-body">
                                    <h3 class="card-title">Sign up for free</h3>
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf
                                        <input type="hidden" class="form-control form-control-lg" id="firstname"
                                            placeholder="Enter your first name" name="firstname" value="unnamed">
                                        <input type="hidden" class="form-control form-control-lg" id="lastname"
                                            placeholder="Enter your last name" name="lastname" value="unnamed">

                                        <div class="form-group">
                                            <label>Email address</label>
                                            <input type="email" class="form-control" placeholder="Enter email" name="email"
                                                required>
                                            @error('email')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" placeholder="Password" name="password"
                                                required>
                                            @error('password')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Repeat Password</label>
                                            <input type="password" class="form-control" placeholder="Password"
                                                name="password_confirmation" required>
                                        </div>
                                        <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                            <div class="col-md-6">
                                                {!! app('captcha')->display() !!}
                                            </div>
                                                @if ($errors->has('g-recaptcha-response'))
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        {{ $errors->first('g-recaptcha-response') }}
                                                    </span>
                                                @endif
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-1">Join now for free</button>
                                    </form>
                                    @if (isFacebookEnabled() || isGoogleEnabled())
                                        <div>
                                            <hr />
                                            @if (isFacebookEnabled())
                                                <a class="btn btn-primary border-0 fb-button"
                                                    style="background-color: #3b5998; border-radius: 2px"
                                                    href="{{ url('/login/facebook') }}" role="button">
                                                    <i class="fab fa-facebook-f"></i> Join with Facebook</a>
                                            @endif
                                            @if (isGoogleEnabled())
                                                <a class="btn btn-primary border-0 ml-0 g-button"
                                                    style="background-color: #dd4b39; border-radius: 2px"
                                                    href="{{ url('/login/google') }}" role="button">
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
                            <p>Join for free with over 15 million members saving hundreds of pounds each year from all the top 5,000
                                online retailers.</p>
                            <a href="{{ route('offers') }}"class="btn btn-primary">Browse Offers<a>
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
                        <img src="{{ asset('frontend/images/pages/shopping.png') }}" alt="" width="100%">
                        <h5>Quick and easy</h5>
                        <p>So you can get on with your shopping.</p>
                    </div>

                    <div class="col-md-4 p-lg-5">
                        <img src="{{ asset('frontend/images/pages/add_up.png') }}" alt="" width="100%">
                        <h5>It all adds up</h5>
                        <p>Members earn on average £345 cashback a year.</p>
                    </div>
                    <div class="col-md-4 p-lg-5">
                        <img src="{{ asset('frontend/images/pages/payout.png') }}" alt="" width="100%">
                        <h5>Payout how you want</h5>
                        <p>Get your money directly to your bank account, PayPal, or Gift Cards.</p>
                    </div>
                </div>
            </div>
        </div>
    @endguest

    @if ($testimonials)
        @include('layouts.frontend.includes.sections.testimonial')
    @endif

    <!-- Cashbacks -->
    @if (count($stores))
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
                                <div class="products-view__list products-list" data-layout="grid-5-full"
                                    data-with-features="false" data-mobile-grid-columns="2">
                                    <div class="products-list__body">
                                        @foreach ($stores->take(15) as $store)
                                            @include('layouts.frontend.includes.stores')
                                        @endforeach
                                    </div>
                                </div>
                                <a href="{{ url('/pages/offers') }}" class="btn btn-primary mt-5 mx-auto d-table">More
                                    Cashback</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                                <a href="{{ route('cashabck', $category->slug) }}">
                                    @if ($category->logo_type == 'upload')
                                        @if (!file_exists(asset('storage/categories/images/' . $category->logo_upload)))
                                            <img src="{{ asset('frontend/images/categories/images/' . $category->logo_upload) }}"
                                                alt="">
                                        @else
                                            <img src="{{ asset('storage/categories/images/' . $category->logo_upload) }}"
                                                alt="{{ $category->name }}">
                                        @endif
                                    @elseif($category->logo_type == 'link')
                                        <img src="{{ $category->logo_link }}" alt="">
                                    @endif
                                </a>
                            </div>
                            <div class="category-card__content">
                                <div class="category-card__name">
                                    <a href="{{ route('cashabck', $category->slug) }}">{{ $category->name }}</a>
                                </div>
                                @if (count($category->childs))
                                    <ul class="category-card__links">
                                        @foreach ($category->childs->take(5) as $item)
                                            <li><a href="{{ route('cashabck', $item->slug) }}">{{ $item->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- .block-categories / end -->
@endsection
