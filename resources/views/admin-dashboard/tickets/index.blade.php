@extends('layouts.admin-dashboard.app')
@section('pageTitle', 'Tickets')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Tickets</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have total {{$tickets->total()}} tickets.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        {{-- <li class="nk-block-tools-opt"><a href="{{route(getAdminPrefix() . '.ticketCategory.index')}}" class="btn btn-primary btn-sm"><em class="icon ni ni-plus"></em><span>Manage Ticket Category</span></a></li> --}}
                                        {{-- <li><a href="{{route(getAdminPrefix() . '.stores.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li> --}}
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="card card-preview mb-4">
                    <div class="card-inner">
                        <form action="{{route(getAdminPrefix() . '.tickets.search')}}" class="form-validate is-alter search_form" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label" for="ticket_id">Ticket ID</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="ticket_id" value="" name="ticket_id">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label" for="title">Title</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="title" value="" name="title">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label" for="user_id">User</label>
                                        <div class="form-control-wrap ">
                                            <select class="form-select form-control" data-search="on" id="user_id" name="user_id">
                                                <option value="0">Any</option>
                                                @foreach ($users as $user)
                                                <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label" for="status">Status</label>
                                        <div class="form-control-wrap ">
                                            <select class="form-select form-control" data-search="on" id="status" name="status">
                                                <option value="-1">Any</option>
                                                <option value="open">Open</option>
                                                <option value="closed">Closed</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label" for="category">Category</label>
                                        <div class="form-control-wrap ">
                                            <select class="form-select form-control" data-search="on" id="category" name="category">
                                                <option value="0">Any</option>
                                                @foreach ($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2 align-self-end ml-auto">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-block">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                @include('flash::message')
                <div class="nk-block">
                    <div class="card card-stretch">
                        <div class="card-inner-group" id="table-data">
                            @include('admin-dashboard.tickets.index_data')
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

             pageurl = "{{route(getAdminPrefix() . '.tickets.fetch')}}?page="
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
                $('#reload').ajax().reload();
                });
         }

         if(route=='search'){

            $('#table-data').html(`<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
            </div></div>`);

            var _token = $("input[name=_token]").val();
            var user_id = $("select[name=user_id]").val();
            var ticket_id = $("input[name=ticket_id]").val();
            var status = $("select[name=status").val();
            var category = $("select[name=category").val();
            var priority = $("select[name=priority").val();
            var title = $("input[name=title]").val();
            $.ajax({
              url:'{{route(getAdminPrefix() . ".tickets.search")}}?page='+page,
              method:"POST",
              data:{_token:_token,user_id:user_id,ticket_id:ticket_id,category:category,status:status,priority:priority,page:page},
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
            var user_id = $("select[name=user_id]").val();
            var ticket_id = $("input[name=ticket_id]").val();
            var status = $("select[name=status").val();
            var category = $("select[name=category").val();
            var priority = $("select[name=priority").val();
            var title = $("input[name=title]").val();
            $.ajax({
              url:'{{route(getAdminPrefix() . ".tickets.search")}}',
              method:"POST",
              data:{_token:_token,user_id:user_id,ticket_id:ticket_id,category:category,status:status},
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
