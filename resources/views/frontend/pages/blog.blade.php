@extends('layouts.frontend.app')
@section('content')
    <style>
        .container-fluid {
            width: 1250px;
            margin-left: -80px;
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
                        <li class="breadcrumb-item active" aria-current="page">Blog</li>
                    </ol>
                </nav>
            </div>
            <div class="container p-0 my-2">
                @php
                    $page = new \stdClass();
                    $page->title = 'Blog';
                    $page->banner_image = null;
                @endphp
                @include('layouts.frontend.includes.banners.pages_banner')
            </div>
            <div class="page-header__title">
                <h1>Blog</h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="block">
                    <div class="posts-view">
                        <div class="posts-view__list posts-list posts-list--layout--list">
                            <div class="posts-list__body">
                                @foreach ($blogs as $blog)
                                    <div class="posts-list__item">
                                        <div class="post-card post-card--layout--list post-card--size--nl">
                                            <div class="post-card__image">
                                                <a href="{{ route('post', $blog->slug) }}">
                                                    <img @if ($blog->featured_image && isFileExist($blog->featured_image)) src="{{ $blog->featured_image }}"
                                                        @else src="{{ asset('frontend/images/posts/post-featured.jpg') }}" @endif
                                                        alt="" height="200px" width="100%">
                                                </a>
                                            </div>
                                            <div class="post-card__info">
                                                <div class="post-card__category">
                                                </div>
                                                <div class="post-card__name">
                                                    <a href="{{ route('post', $blog->slug) }}">{{ $blog->title }}</a>
                                                </div>
                                                <div class="post-card__date">
                                                    {{ Carbon\Carbon::parse($blog->created_at)->isoFormat('Do MMMM YYYY') }}
                                                </div>
                                                <div class="post-card__content">
                                                    {{ $blog->exerpt }}
                                                </div>
                                                <div class="post-card__read-more">
                                                    <a href="{{ route('post', $blog->slug) }}" class="btn btn-secondary btn-sm">Read More</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="block block-sidebar block-sidebar--position--end">
                    @if (count($blogs))
                        <div class="block-sidebar__item">
                            <div class="widget-posts widget">
                                <h4 class="widget__title">Latest Posts</h4>
                                <div class="widget-posts__list">
                                    @foreach ($blogs->take(3) as $blog)
                                        <div class="widget-posts__item">
                                            <div class="widget-posts__image">
                                                <a href="{{ route('post', $blog->slug) }}">
                                                    <img @if ($blog->featured_image && isFileExist($blog->featured_image))
                                                            src="{{ $blog->featured_image }}"
                                                        @else src="{{ asset('frontend/images/posts/post-featured.jpg') }}" @endif
                                                        alt="" height="90px" width="90px">
                                                </a>
                                            </div>
                                            <div class="widget-posts__info">
                                                <div class="widget-posts__name">
                                                    <a href="{{ route('post', $blog->slug) }}">{{ $blog->title }}</a>
                                                </div>
                                                <div class="widget-posts__date">
                                                    {{ Carbon\Carbon::parse($blog->created_at)->isoFormat('Do MMMM YYYY') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
