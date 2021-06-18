@extends('layouts.frontend.app')
@section('content')

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