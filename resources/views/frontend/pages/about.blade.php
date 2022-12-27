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
<div class="container p-2 my-2">
    <div class="row">
        <div class="col-12">
            <div class="block-finder__body">
               <img class="banner__size" src="{{$page->banner_image}}" alt="About-us Image Missing">
                 <div class="block-finder__header">
                    <div class="block-finder__title">{{$page->title}}</div>
                    <div class="block-finder__subtitle"></div>
                 </div>
            </div>
            <div class="category-text panel  mb-3 mt-4 pt-3">
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
                    <h1 class="about-us__title">About Us</h1>
                    <div class="about-us__text typography">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras lacus metus, convallis ut leo nec, tincidunt
                            eleifend justo. Ut felis orci, hendrerit a pulvinar et, gravida ac lorem. Sed vitae molestie sapien, at
                            sollicitudin tortor.
                        </p>
                        <p>
                            Duis id volutpat libero, id vestibulum purus.Donec euismod accumsan felis,egestas lobortis velit tempor vitae.
                            Integer eget velit fermentum, dignissim odio non, bibendum velit.
                        </p>
                    </div>
                    <div class="about-us__team">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection