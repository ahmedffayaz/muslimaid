@extends('layouts.frontend.app')
@section('content')
<style>
    .about-us__body{
        margin-top: 0px;
    }
</style>
<div class="container">
    <div class="page-header__breadcrumb ">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{url('/')}}">Home</a>
                    <svg class="breadcrumb-arrow" width="6px" height="9px">
                        <use xlink:href="{{asset('frontend/images/sprite.svg')}}#arrow-rounded-right-6x9"></use>
                    </svg>
                </li>
                <li class="breadcrumb-item active" aria-current="page">About Us</li>
            </ol>
        </nav>
    </div>
</div>
<div class="container">
    @include('layouts.frontend.includes.banners.pages_banner')
    <div class="row">
        <div class="col-12">
            <div class="category-text panel  mb-4 mt-4 pt-3">
                <p class="mt-1">{{$page->description}}</p>
            </div>
        </div>
    </div>
</div>
<div class="block about-us">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="about-us__body">
                    <h1 class="about-us__title">{{$page->title}}</h1>
                    <div class="about-us__text typography">
                        <p>Cashback is a social enterprise that aims to promote and support Black-owned businesses and charities through the provision of cashback to our registered members with the overall goal of promoting a fairer society.</p>

                        <p>Founded in 2020, the need for financial and socio-economic empowerment within the Black community was never more apparent – both globally and in the United Kingdom. Cashback aims to benefit both the Black community and society as a whole by decreasing the racial wealth gap through the promotion of Black-owned businesses and the provision of funds back to consumers of these businesses through cashback.</p>

                        <p>Whether you’re looking for local eating spots for a dine-in or a take-away, the latest and most fashionable offerings from clothes and accessories designers, beauty and wellness brands to leave you looking and feeling your finest or any other goods and services providers you would ever need;</p>

                        <p>Cashback is here to support you supporting Black-owned businesses.</p>
                    </div>
                    {{-- <div class="about-us__team">
                        <h2 class="about-us__team-title">Meat Our Team</h2>
                        <div class="about-us__team-subtitle text-muted">Want to work in our friendly team?<br><a href="contact-us.html">Contact us</a> and we will consider your candidacy.</div>
                        <div class="about-us__teammates teammates">
                            <div class="owl-carousel">
                                <div class="teammates__item teammate">
                                    <div class="teammate__avatar">
                                        <img src="{{asset('frontend/images/teammates/teammate-1.jpg')}}" alt="">
                                    </div>
                                    <div class="teammate__name">Michael Russo</div>
                                    <div class="teammate__position text-muted">Chief Executive Officer</div>
                                </div>
                                <div class="teammates__item teammate">
                                    <div class="teammate__avatar">
                                        <img src="{{asset('frontend/images/teammates/teammate-2.jpg')}}" alt="">
                                    </div>
                                    <div class="teammate__name">Katherine Miller</div>
                                    <div class="teammate__position text-muted">Marketing Officer</div>
                                </div>
                                <div class="teammates__item teammate">
                                    <div class="teammate__avatar">
                                        <img src="{{asset('frontend/images/teammates/teammate-3.jpg')}}" alt="">
                                    </div>
                                    <div class="teammate__name">Anthony Harris</div>
                                    <div class="teammate__position text-muted">Finance Director</div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
