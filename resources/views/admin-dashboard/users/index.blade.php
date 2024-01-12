@extends('layouts.admin-dashboard.app')
@section('pageTitle', 'Users Lists')
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
                                        <li><a href="{{route(getAdminPrefix() . '.users.create')}}" class="btn btn-primary btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-plus"></em><span>Add user</span></a></li>
                                       <li><a href="{{route(getAdminPrefix() . '.users.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                @include('flash::message')
                <div class="card card-preview mb-4">
                    <div class="card-inner">
                        <form action="{{route(getAdminPrefix() . '.stores.search_stores')}}" class="form-validate is-alter search_form" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label" for="name">Name/ID</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="name" value="" name="name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label" for="email">Email</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="email" value="" name="email">
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
                        </form>
                    </div>
                </div>
                <div class="nk-block">
                    <div class="card card-stretch">
                        <div class="card-inner-group" id="table-data">
                            @include('admin-dashboard.users.index_data')
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

             pageurl = "{{route(getAdminPrefix() . '.users.fetch')}}?page="
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
            var type = $("select[name=type]").val();
            var status = $("select[name=status").val();
            var name = $("input[name=name]").val();
            $.ajax({
              url:'{{route(getAdminPrefix() . ".users.search_users")}}?page='+page,
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
            $('#table-data').html(`<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
            </div></div>`);

            var _token = $("input[name=_token]").val();
            var status = $("select[name=status").val();
            var name = $("input[name=name]").val();
            var email = $("input[name=email]").val();
            $.ajax({
              url:'{{route(getAdminPrefix() . ".users.search_users")}}',
              method:"POST",
              data:{_token:_token,email:email,name:name,status:status},
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
