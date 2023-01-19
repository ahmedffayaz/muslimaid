@extends('frontend.layouts.app')
@section('content')
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
                <li class="breadcrumb-item active" aria-current="page">{{$page->title}}</li>
            </ol>
        </nav>
    </div>
</div>
<div class="block block-product-columns mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 mt-5">
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
@endsection
