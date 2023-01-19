@extends('frontend.layouts.app')
@section('content')
<div class="page-header">
    <div class="page-header__container container">
        <div class="page-header__breadcrumb pb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">Home</a>
                        <svg class="breadcrumb-arrow" width="6px" height="9px">
                            <use xlink:href="{{ asset('frontend/images/sprite.svg') }}#arrow-rounded-right-6x9"></use>
                        </svg>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('categories.index') }}">Categories</a>
                        <svg class="breadcrumb-arrow" width="6px" height="9px">
                            <use xlink:href="{{ asset('frontend/images/sprite.svg') }}#arrow-rounded-right-6x9"></use>
                        </svg>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $parentSlug }}
                        @if($chlidSlug)
                        <svg class="breadcrumb-arrow" width="6px" height="9px">
                            <use xlink:href="{{ asset('frontend/images/sprite.svg') }}#arrow-rounded-right-6x9"></use>
                        </svg>
                        @endif
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $chlidSlug }}</li>  
                </ol>
            </nav>
        </div>
        {{-- Banner image code here --}}
        <div class="page-header__title">
            <div class="row">
                <div class="col-md-12 pl-0">
                    <h1 class="col-md-5 float-left">{{ $chlidSlug == '' ? $parentSlug : $chlidSlug }}</h1>
                </div>
            </div>
        </div>
        {{-- other code here --}}
       
    </div>
</div>
@endsection