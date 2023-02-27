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
            
        $('#table-data').html(`<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
            </div></div>`);

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
                
                
        $('#table-data').html(`<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
            </div></div>`);

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
            
        $('#table-data').html(`<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
            </div></div>`);

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