@extends('frontend.layouts.app')
@section('content')

@if(!isset($page->title)){{abort(404)}} @endif
@if($page->status == 0) {{abort(404)}} @endif
<div class="block block-product-columns">
    <div class="container">
        <div class="row">
            <div class="col-12">
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


