@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Users Lists</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have total {{$users->total()}} users.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li><a href="{{route('admin.users.create')}}" class="btn btn-primary btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-plus"></em><span>Add user</span></a></li>
                                       <li><a href="{{route('admin.users.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                @include('flash::message')
                <div><form action="{{route('admin.stores.search_stores')}}" class="form-validate is-alter search_form card p-4 mb-4" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-label" for="pay-amount-1">Name</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="pay-amount-1" value="" name="name">
                                </div>
                            </div>
                        </div>
                       
                      
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-label" for="type">Regestration Type</label>
                                <div class="form-control-wrap ">
                                    
                                        <select class="form-select form-control" id="type" name="type">
                                            <option value="-1">Any</option>
                                            
                                            
                                            <option value="sign up">Sign up</option>
                                            <option value="social">Social</option>
                                            
                                        </select>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-label" for="status">Status</label>
                                <div class="form-control-wrap ">
                                    
                                        <select class="form-select form-control" id="status" name="status">
                                            <option value="-1">Any</option>
                                            
                                            
                                            <option value="1">Active</option>
                                            <option value="0">In-active</option>
                                            
                                        </select>
                                   
                                </div>
                            </div>
                        </div>
                       
                        
                        
                                               
                        <div class="col-3 align-self-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block">Search</button>
                            </div>
                        </div>
                    </div>
                </form></div>
                <div class="nk-block">
                    <div class="card card-stretch">
                        <div class="card-inner-group">
                            
                            <div class="card-inner px-0">
                                <div class="nk-tb-list nk-tb-ulist" id="table-data">
                                    
                                    @include('admin-dashboard.users.index_data')                                   
                                    
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
            
             pageurl = "{{route('admin.users.fetch')}}?page="
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
            var type = $("select[name=type]").val();
            var status = $("select[name=status").val();
            var name = $("input[name=name]").val();
            $.ajax({
              url:'{{route("admin.users.search_users")}}?page='+page,
              method:"POST",
              data:{_token:_token,type:type,name:name,status:status},
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
            var type = $("select[name=type]").val();
            var status = $("select[name=status").val();
            var name = $("input[name=name]").val();
            $.ajax({
              url:'{{route("admin.users.search_users")}}',
              method:"POST",
              data:{_token:_token,type:type,name:name,status:status},
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