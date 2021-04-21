@extends('layouts.admin-dashboard.app')
@section('content')
   

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Categories</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have total {{count($categories)}} categories.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt"><a href="{{route('admin.categories.create')}}" class="btn btn-primary btn-sm"><em class="icon ni ni-plus"></em><span>Add Category</span></a></li>
                                     
                                        <li><a href="{{route('admin.categories.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                       
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div><form action="" class="form-validate is-alter search_form card p-4 mb-4" method="POST">
                    @csrf
                    <div class="row g-4">
                       
                        <div class="col-lg-4">
                            <div class="form-group">
                                <div class="form-control-wrap ">
                                    <label class="form-label" for="title">Category Title</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="title" value="" name="title">
                                    </div>
                                        
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-label" for="parent_id">Parent</label>
                                <div class="form-control-wrap ">
                                    <select class="form-select form-control" data-search="on" id="parent_id" name="parent_id">
                                        <option value="0">All</option>
                                        @foreach ($store_categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="col-4 align-self-end ml-auto">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block">Search</button>
                            </div>
                        </div>
                    </div>
                </form></div>
                @include('flash::message')
                <div class="nk-block">
                    <div class="card card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner position-relative card-tools-toggle">
                                <div class="card-title-group">
                                    <div class="card-tools">
                                        
                                    </div><!-- .card-tools -->
                                    <div class="card-tools mr-n1">
                                        <ul class="btn-toolbar gx-1">
                                            <li>
                                                <a href="#" class="btn btn-icon search-toggle toggle-search" data-target="search"><em class="icon ni ni-search"></em></a>
                                            </li><!-- li -->
                                            {{-- <li class="btn-toolbar-sep"></li><!-- li --> --}}
                                            
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->
                                <div class="card-search search-wrap" data-search="search">
                                    <div class="card-body">
                                        <div class="search-content">
                                            <a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                            <input type="text" class="form-control border-transparent form-focus-none" placeholder="Search by user or email">
                                            <button class="search-submit btn btn-icon"><em class="icon ni ni-search"></em></button>
                                        </div>
                                    </div>
                                </div><!-- .card-search -->
                            </div><!-- .card-inner -->
                            <div class="card-inner p-0">
                                <div class="nk-tb-list nk-tb-ulist" id="table-data">
                                    
                                    @include('admin-dashboard.categories.index_data')                                   
                                    
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
            
             pageurl = "{{route('admin.categories.fetch')}}?page="
             var _token = $("input[name=_token]").val();
             
                $.ajax({

                    url:pageurl+page,
                    method:"POST",
                    data:{_token:_token,page:page},
                    success:function(data)
                    {
                        $('#table-data').html(data);
                        $('html, body').animate({ scrollTop: 0 }, 'slow');
                    }
                    });
         } 

         if(route=='search'){
              
              
            var _token = $("input[name=_token]").val();
            var parent_id = $("select[name=parent_id]").val();
            var title = $("input[name=title]").val();
            $.ajax({
              url:'{{route("admin.categories.search_categories")}}?page='+page,
              method:"POST",
              data:{_token:_token,parent_id:parent_id,title:title},
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
            var parent_id = $("select[name=parent_id]").val();
            var title = $("input[name=title]").val();
            $.ajax({
              url:'{{route("admin.categories.search_categories")}}',
              method:"POST",
              data:{_token:_token,parent_id:parent_id,title:title},
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