@extends('layouts.frontend.app')
@section('content')
    <style>
        .img {
            width: 50%;
        }
    </style>
    <div class="page-header">
        <div class="page-header__container container">
            <div class="page-header__breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}">Home</a>
                            <svg class="breadcrumb-arrow" width="6px" height="9px">
                                <use xlink:href="{{ asset('frontend/images/sprite.svg') }}#arrow-rounded-right-6x9"></use>
                            </svg>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ url('/blog') }}">Blog</a>
                            <svg class="breadcrumb-arrow" width="6px" height="9px">
                                <use xlink:href="{{ asset('frontend/images/sprite.svg') }}#arrow-rounded-right-6x9"></use>
                            </svg>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $blog->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="block post post--layout--classic">
                    <div class="post__header post-header post-header--layout--classic">
                        <div class="post-header__categories">
                        </div>
                        <h1 class="post-header__title">{{ $blog->title }}</h1>
                        <div class="post-header__meta">
                            <div class="post-header__meta-item">
                                <a href="">{{ Carbon\Carbon::parse($blog->created_at)->isoFormat('Do MMMM YYYY') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="post__featured img">
                        <a href="">
                            <img @if ($blog->featured_image && isFileExist($blog->featured_image)) src="{{ $blog->featured_image }}"
                                @else src="{{ asset('frontend/images/posts/post-featured.jpg') }}" @endif
                            alt="">
                        </a>
                    </div>
                    <div class="post__content typography text-justify">
                        {!! $blog->lb_content !!}
                    </div>
                    <div class="post__footer">
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="block block-sidebar block-sidebar--position--end">
                    <div class="block-sidebar__item">
                        <div class="widget-posts widget">
                            <h4 class="widget__title">Latest Posts</h4>
                            <div class="widget-posts__list">
                                @foreach ($blogs->take(3) as $related_blog)
                                    @if ($related_blog->id != $blog->id)
                                        <div class="widget-posts__item">
                                            <div class="widget-posts__image">
                                                <a href="{{ route('post', $blog->slug) }}">
                                                    <img @if ($related_blog->featured_image && isFileExist($related_blog->featured_image)) src="{{ $related_blog->featured_image }}"
                                                        @else src="{{ asset('frontend/images/posts/post-featured.jpg') }}" @endif
                                                    alt="" height="90px" width="90px">
                                                </a>
                                            </div>
                                            <div class="widget-posts__info">
                                                <div class="widget-posts__name">
                                                    <a href="{{ route('post', $related_blog->slug) }}">{{ $related_blog->title }}</a>
                                                </div>
                                                <div class="widget-posts__date">
                                                    {{ Carbon\Carbon::parse($related_blog->created_at)->isoFormat('Do MMMM YYYY') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
