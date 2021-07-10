@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{$slider->name}} Slides</h3>
                            <div class="nk-block-des text-soft">
                                {{-- <p>You have total 95 projects.</p> --}}
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt"><a href="#" data-toggle="modal" data-target="#slide-modal" class="btn btn-primary"><em class="icon ni ni-plus"></em><span>Add Slide</span></a></li>
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                @include('flash::message')
                <div class="nk-block">
                    <div class="row g-gs"  id="sortable">
                       @foreach ($slider->slides as $slide)
                       <div class="col-sm-6 col-lg-4 col-xxl-3" id="slide_{{$slide->id}}" style="    cursor: move;">
                        <div class="card h-100">
                            @if($slide->banner == 'default1.png' || $slide->banner == 'default2.png' || $slide->banner == 'default3.png')
                            <img src="{{asset('frontend/images/slides/'.$slide->banner)}}" class="card-img-top" alt="" style="height:200px">
                            @else
                            <img src="{{asset('storage/slider/slides/images/'.$slide->banner)}}" class="card-img-top" alt="" style="height:200px">

                            @endif
                            <div class="card-inner">
                                <div class="project">
                                    <div class="project-head">
                                        <span  class="project-title">
                                            <div class="project-info">
                                                <h6 class="title mb-2">{{$slide->name}}</h6>
                                                @if($slide->logo == 'default1.png' || $slide->logo == 'default2.png' || $slide->logo == 'default3.png')
                                                <img src="{{asset('frontend/images/slides/logo/'.$slide->logo)}}" class="float-right" alt="" style="max-height: 50px">    
                                                @else 
                                                <img src="{{asset('storage/slider/slides/images/'.$slide->logo)}}" class="float-right" alt="" style="max-height: 50px">    
                                                @endif                                          
                                            </div>
                                        </span>
                                    </div>
                                    <div class="project-details">
                                        <p>{{$slide->description}}</p>
                                        <p>@if($slide->store->cashback->type=='fixed'){{$slide->store->cashback->currency}} @endif{{$slide->store->cashback->sale_commission}}@if($slide->store->cashback->type=='percentage')%@endif Cashback</p>
                                    </div>
                                   
                                    <div class="project-meta">
                                        <div class="project-progress-task"><a href="{{route('admin.stores.show_store')}}?slug={{$slide->store->slug}}"><em class="icon ni ni-cart-fill"></em><span>{{$slide->store->id}} - {{$slide->store->name}}</span></a></div>
                                        <div class="float-right">
                                            <a class="btn btn-primary btn-sm edit-slide"  href="{{route('admin.slides.edit',$slide)}}"><em class="icon ni ni-edit"></em></a>
                                            @If(count($slider->slides)>1)
                                            <a class="btn btn-danger btn-sm text-white"   onclick="$('#delete-slide-{{$slide->id}}').submit();"  style="cursor: pointer"><em class="icon ni ni-trash"></em></a>
                                            <form action="{{ route('admin.slides.destroy', $slide) }}" id="delete-slide-{{$slide->id}}" method="POST" class="m-0">
                                                @method('DELETE')
                                                @csrf
                                                
                                            </form>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                       @endforeach
                       
                        
                        
                       
                    </div>
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
<!-- @@ Create Slide Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="slide-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
              
                   
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Add new slide</span></div>

                        {{-- <div class="nk-file-name-sub">Project</div> --}}
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="" class=" p-4">
                <form action="{{route('admin.slides.store')}}" class="gy-3 form-validate is-alter category_form" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="slider_id" value="{{$slider->id}}">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="full-name-1">Slide name</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="full-name-1" name="name" value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="default-06">Store</label>
                                <div class="form-control-wrap ">
                                    <div class="">
                                        <select class="form- form-select" id="default-06" name="store_id" required data-search="on">
                                            
                                            @foreach ($stores as $store)
                                            <option value="{{$store->id}}">{{$store->id}} - {{$store->name}}</option>
                                                
                                            @endforeach
                                                
                                           
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <label class="form-label" for="phone-no-1">Description</label>
                                <textarea name="description" class="form-control" rows="5" required></textarea>
                                                               
                            </div>
                        </div>
                        
                        <div class="col-lg-6 logo_upload">
                            <div class="form-group">
                                <label class="form-label" for="logo">Logo</label>
                                <div class="form-control-wrap">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name='logo' id="logo" required>
                                        <label class="custom-file-label" for="logo">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 banner_upload">
                            <div class="form-group">
                                <label class="form-label" for="banner">Banner</label>
                                <div class="form-control-wrap">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="banner" id="banner" required>
                                        <label class="custom-file-label" for="banner">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->
<!-- @@ Edit Slide Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="edit-slide-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
              
                   
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Add new slide</span></div>

                        {{-- <div class="nk-file-name-sub">Project</div> --}}
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="edit-slide-form" class=" p-4">
                

            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->

@endsection
@push('scripts')
<script>$(document).ready(function(){
    $(document).on('click', '.edit-slide', function(event){
       event.preventDefault(); 

           pageurl = $(this).attr('href');
           var _token = $("input[name=_token]").val();
           $.ajax({

               url:pageurl,
               method:"GET",
               data:{_token:_token},
               success:function(data)
               {
                   $('#edit-slide-modal').modal('show');
                   $('#edit-slide-form').html(data);
                   initializeSelect2();
                  
               }
               });
              
    });
   });
   function initializeSelect2() {
        $('.select-2').select2({
            placeholder: function(){
                $(this).data('placeholder');
            }
        });
    }
   </script>   
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <script>
   $( function() {
     $( "#sortable" ).sortable({
    update: function (event, ui) {
        var data = $(this).sortable('serialize');
        console.log(data);


        // POST to server using $.post or $.ajax
        $.ajax({
            data: data,
            type: 'POST',
            url: '{{route("admin.sort_slides")}}',
            success:function(data)
              {
                    (function(NioApp, $){
                    'use strict';
                    toastr.clear();
                    NioApp.Toast(data.message, data.updated);
                })(NioApp, jQuery);
                  
            },
        });
    }
});
     $( "#sortable" ).disableSelection();
   } );
   </script>
@endpush