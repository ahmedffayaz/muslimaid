@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{$network->name}} Categories</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have total {{$categories->total()}} categories.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li><a href="{{route('admin.networks.categories.export', $network)}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>

                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div><form action="" class="form-validate is-alter search_form card p-4 mb-4" method="POST">
                    @csrf
                    <div class="row g-4">
                       
                        <div class="col-lg-3">
                            <div class="form-group">
                                <div class="form-control-wrap ">
                                    <label class="form-label" for="title">Category Title</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="title" value="" name="title">
                                    </div>
                                        
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-label" for="parent_id">Parent</label>
                                <div class="form-control-wrap ">
                                    <select class="form-select form-control" data-search="on" id="parent_id" name="parent_id">
                                        <option value="0">All</option>
                                        @foreach ($network_categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-label" for="mapped_id">Mapped to</label>
                                <div class="form-control-wrap ">
                                    <select class="form-select form-control" data-search="on" id="mapped_id" name="mapped_id">
                                        <option value="-1">All</option>
                                        <option value="0">Unmapped</option>
                                        @foreach ($store_categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> 
                                      
                        <div class="col-3 align-self-end ml-auto">
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
                           
                            <div class="card-inner px-0">
                                <div class="nk-tb-list nk-tb-ulist" id="table-data">
                                    
                                    @include('admin-dashboard.imported-categories.index_data')                                   
                                    
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
            
             pageurl = "{{route('admin.importedcategories.fetch')}}?page="
             var _token = $("input[name=_token]").val();
             var network_id = "{{$network->id}}"
                $.ajax({

                    url:pageurl+page,
                    method:"POST",
                    data:{_token:_token,network_id:network_id, page:page},
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
            var mapped_id = $("select[name=mapped_id]").val();
            $.ajax({
              url:'{{route("admin.importedcategories.search_importedcategories")}}?page='+page,
              method:"POST",
              data:{_token:_token,parent_id:parent_id,title:title,mapped_id:mapped_id},
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
            var mapped_id = $("select[name=mapped_id]").val();
            $.ajax({
              url:'{{route("admin.importedcategories.search_importedcategories")}}',
              method:"POST",
              data:{_token:_token,parent_id:parent_id,title:title,mapped_id:mapped_id},
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