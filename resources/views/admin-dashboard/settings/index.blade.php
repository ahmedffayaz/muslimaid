@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Settings</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have total {{$settings->total()}} settings.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li><a href="{{route(getAdminPrefix() . '.settings.create')}}" class="btn btn-primary btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-plus"></em><span>Add Setting</span></a></li>
                                        <li><a href="{{route(getAdminPrefix() . '.settings.export', $settings)}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>

                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="card card-preview mb-4">
                    <div class="card-inner">
                        <div id="accordion-1" class="accordion accordion-s2">
                            <div class="accordion-item">
                                <a href="#" class="accordion-head collapsed" data-toggle="collapse" data-target="#accordion-item-1-1">
                                    <h6 class="title">Search</h6>
                                    <span class="accordion-icon"></span>
                                </a>
                                <div class="accordion-body collapse" id="accordion-item-1-1" data-parent="#accordion-1">
                                    <div class="accordion-inner">
                                        <div><form action="{{route(getAdminPrefix() . '.stores.search_stores')}}" class="form-validate is-alter search_form" method="POST">
                                            @csrf
                                            <div class="row g-4">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <div class="form-control-wrap ">
                                                            <label class="form-label" for="title">Title</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="title" value="" name="title">
                                                            </div>
                                                                
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <div class="form-control-wrap ">
                                                            <label class="form-label" for="rekey">Key Identifier</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="key" value="" name="key">
                                                            </div>
                                                                
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                                
                                                <div class="col-4 align-self-end">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-success btn-block">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('flash::message')
                <div class="nk-block">
                    <div class="card card-stretch">
                        <div class="card-inner-group">
                            
                            <div class="card-inner px-0 table-responsive">
                                <div class="nk-tb-list nk-tb-ulist" id="table-data">
                                    
                                    @include('admin-dashboard.settings.index_data')                                   
                                    
                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                           
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div> 
@endsection
@push('scripts')
    <script>
    $(document).ready(function(){
     $(document).on('click', '.pagination a', function(event){
        event.preventDefault(); 
        var route = $('.pagination').attr('route');
        var page = $(this).attr('href').split('page=')[1];
        
         if(route=='index'){
            
             pageurl = "{{route(getAdminPrefix() . '.settings.fetch')}}?page="
             var _token = $("input[name=_token]").val();
            $.ajax({

                url:pageurl+page,
                method:"POST",
                data:{_token:_token, page:page},
                success:function(data)
                {
                    $('#table-data').html(data);
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                }
                });
         } 

         if(route=='search'){
              
              
            var _token = $("input[name=_token]").val();
            var title = $("input[name=title]").val();
            var key = $("select[name=key]").val();
           
            var status = $("select[name=status]").val();
            $.ajax({
              url:'{{route("admin.settings.search_settings")}}?page='+page,
              method:"POST",
              data:{_token:_token,title:title,key:key},
              success:function(data)
              {
               $('#table-data').html(data);
               $('html, body').animate({ scrollTop: 0 }, 'slow');
              }
            });
         }       
     });
    });
    </script> 
    <script>
        $(document).ready(function(){
        
         $(document).on('submit', '.search_form', function(event){
            event.preventDefault(); 
              
            var _token = $("input[name=_token]").val();
            var title = $("input[name=title]").val();
            var key = $("input[name=key]").val();
           
            var status = $("select[name=status]").val();
            $.ajax({
              url:'{{route("admin.settings.search_settings")}}',
              method:"POST",
              data:{_token:_token,title:title,key:key},
              success:function(data)
              {
               $('#table-data').html(data);
               $('html, body').animate({ scrollTop: 0 }, 'slow');
              }
            });
            
         });
        
        });
        
        </script>  
@endpush