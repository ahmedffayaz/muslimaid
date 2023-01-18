@extends('layouts.frontend.app')
@section('content')
<style>
    a:link {
       color: rgb(0, 0, 0);
       background-color: transparent;
       text-decoration: none;
    }
    a:visited {
       color: rgb(0, 0, 0);
       background-color: transparent;
       text-decoration: none;
    }
    
</style>
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
                    <li class="breadcrumb-item active" aria-current="page">Categories</li>
                </ol>
            </nav>
            <div class="block block-product-columns pt-5 mt-5">
                <div class="container">
                    <div class="row"> 
                        @foreach ($categories as $category)
                             @if($category->status ===1)
                                  @if($category->childs->count())                                     
                                        <div class="col-lg-4 mt-5">
                                            <div class="block-header">
                                                <h3 class="block-header__title"><a href="{{route('categories.show',$category->slug)}}">{{$category->name}}</a></h3>
                                                <div class="block-header__divider"></div>
                                            </div>
                                            <div class="block-product-columns__column">
                                                @foreach ($category->childs as $subcategory)
                                                    @if($subcategory->status ===1)
                                                        <ul class="block-header__groups-list">
                                                            <li><a href="{{route('categories.show',$subcategory->slug)}}" type="button" class="block-header__group color-primary">{{ $subcategory->name }}</a></li>
                                                        </ul>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                  @endif
                             @endif
                        @endforeach
                    </div>
                </div>
            </div>   
        </div>
    </div>
</div>
@endsection