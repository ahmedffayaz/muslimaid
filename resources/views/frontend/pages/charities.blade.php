@extends('layouts.frontend.app')
@section('content')
<style>
    .product-card__rating{
        white-space:normal !important ;
    }
    b, strong{
        display: contents !important;
    }
    .product-card__name{
        font-weight: bold;
    }
</style>
    <div class="page-header">
        <div class="page-header__container container">
            <div class="page-header__breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}">Home</a>
                            <svg class="breadcrumb-arrow" width="6px" height="9px">              
                                <use xlink:href="{{asset('frontend/images/sprite.svg')}}#arrow-rounded-right-6x9"></use>
                            </svg>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Charities</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="container p-2 my-2">
        @include('layouts.frontend.includes.banners.pages_banner')
    </div>
@if(!isset($page->title)){{abort(404)}} @endif
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
<div class="block block-product-columns d-lg-block d-none">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="block-header">
                    <h3 class="block-header__title">Charities We Support</h3>
                    <div class="block-header__divider"></div>
                </div>
                <div class="row">
                    @foreach ($HomePageCharities as $charity)
                        <div class="col-md-4">
                            <div class="block-product-columns__column pt-2">
                                <div class="block-product-columns__item" >
                                    <div class="product-card product-card--hidden-actions product-card--layout--horizontal product-card__quickvieww" 
                                                       type="button" toggle="model" data-target="#quickview-modal" data-id="{{$charity->id}}" style="min-height: 150px">
                                        <div class="product-card__image product-image">
                                            <a href="product.html" class="product-image__body">
                                                <img class="product-image__img" src="{{asset('storage/charities/images/'.$charity->logo_upload)}}" alt="">
                                            </a>
                                        </div>
                                        <div class="product-card__info">
                                            <div class="product-card__name">
                                                <p>{{$charity->title}}</p>
                                            </div>
                                            <div class="product-card__rating">
                                               {{ mb_substr(strip_tags($charity->description), 0, 40, 'UTF-8')}}...
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach 
                </div>
            </div>
            <div id="quickview-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content" id="quickview-modal-content">   
                    </div>
                </div>
            </div>
                           
        </div>
        <div class="nk-block-between-md g-3 card-inner float-right">
            <div class="pagination g" >
                {!! $HomePageCharities->links()!!}                                               
            </div>    
        </div><!-- .nk-block-between -->     
    </div> 
</div>
@endsection
@push('scripts')
<script>
       
    $('.product-card__quickvieww').on('click', function() {
        var id = $(this).attr('data-id');
        const quickview = {
        cancelPreviousModal: function() {},
        clickHandler: function() {
            const modal = $('#quickview-modal');
            const button = $(this);
            let xhr = null;
            const timeout = setTimeout(function() {
                var _token = $("input[name=_token]").val();
                xhr = $.ajax({
                   url: '{{ route('showCharity') }}',
                   method:"POST",
                   data: {
                        id: id,
                        _token:_token
                    },
                    success: function(data) {
                        quickview.cancelPreviousModal = function() {};
                        modal.find('.modal-content').html(data);
                        $(document).on('click', '.quickview__close', function() {
                            modal.modal('hide');
                        });
                        
                        $("#quickview-modal-content").html(data).show();
                        $('#quickview-modal').modal('show');
                    }
                });
            });
            quickview.cancelPreviousModal = function() {
                if (xhr) {
                    xhr.abort();
                }

                // timeout ONLY_FOR_DEMO!
                clearTimeout(timeout);
            };
        }
    };
   
   
    $(function () {
        const modal = $('#quickview-modal');

        modal.on('shown.bs.modal', function() {
            $('.input-number', modal).customNumber();
        });      
    });
           quickview.clickHandler.apply(this, arguments);  
       });
</script>
@endpush

