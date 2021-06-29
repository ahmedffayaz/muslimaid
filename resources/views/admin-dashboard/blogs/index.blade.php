@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Blog Posts</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have total {{$blogs->total()}} blogs.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt"><a href="{{route('admin.blogs.create')}}" class="btn btn-primary btn-sm" ><em class="icon ni ni-plus"></em><span>Add Blog</span></a></li>
                                      
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                {{-- <div class="card card-preview mb-4">
                    <div class="card-inner">
                        <div id="accordion-1" class="accordion accordion-s2">
                            <div class="accordion-item">
                                <a href="#" class="accordion-head collapsed" data-toggle="collapse" data-target="#accordion-item-1-1">
                                    <h6 class="title">Search</h6>
                                    <span class="accordion-icon"></span>
                                </a>
                                <div class="accordion-body collapse" id="accordion-item-1-1" data-parent="#accordion-1">
                                    <div class="accordion-inner">
                                        <div><form action="{{route('admin.stores.search_stores')}}" class="form-validate is-alter search_form" method="POST">
                                            @csrf
                                            <div class="row g-4">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <div class="form-control-wrap ">
                                                            <label class="form-label" for="reviewer">Reviewer Name</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="reviewer" value="" name="reviewer">
                                                            </div>
                                                                
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            
                                                    
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="status">Status</label>
                                                        <div class="form-control-wrap ">
                                                            
                                                            <select class="form-control form-select" name="status" required>
                                                                <option value="-1">Any</option>
                                                                <option value="active">Active</option>
                                                                <option value="pending">Pending</option>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                @include('flash::message')
                <div class="nk-block">
                    <div class="card card-stretch">
                        <div class="card-inner-group">
                            
                            <div class="card-inner px-0">
                                <div class="nk-tb-list nk-tb-ulist" id="table-data">
                                    
                                    @include('admin-dashboard.blogs.index_data')                                   
                                    
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
            
                pageurl = "{{route('admin.reviews.fetch')}}?page="
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
            var reviewer = $("input[name=reviewer]").val();
            var store_id = $("select[name=store_id]").val();
            
            var status = $("select[name=status]").val();
            $.ajax({
                url:'{{route("admin.reviews.search_reviews")}}?page='+page,
                method:"POST",
                data:{_token:_token,reviewer:reviewer,store_id:store_id,status:status},
                success:function(data)
                {
                $('#table-data').html(data);
                $('html, body').animate({ scrollTop: 0 }, 'slow');
                }
            });
            }       
        });
    });

    $(document).ready(function(){
    
        $(document).on('submit', '.search_form', function(event){
        event.preventDefault(); 
            
        var _token = $("input[name=_token]").val();
        var reviewer = $("input[name=reviewer]").val();
        var store_id = $("select[name=store_id]").val();
        
        var status = $("select[name=status]").val();
        $.ajax({
        url:'{{route("admin.reviews.search_reviews")}}',
            method:"POST",
            data:{_token:_token,reviewer:reviewer,store_id:store_id,status:status},
            success:function(data)
            {
            $('#table-data').html(data);
            $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
        
        });
    
    });
    
</script> 
<script>
    $(document).ready(function(){
       
       $(document).on('click', '.delete', function(event){
            var form_id = $(this).attr('form_id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then(function (result) {
                if (result.value) {
                $('#'+form_id).submit();
                }
           });
           event.preventDefault(); 
        });
   });
</script> 
@endpush