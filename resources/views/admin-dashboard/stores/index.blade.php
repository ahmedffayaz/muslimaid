@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Stores</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have total {{$stores->total()}} stores.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt">
                                            @if(app()->environment('local'))
                                            <button class="btn btn-warning btn-sm" id="fake-data-importer-btn">
                                                <em class="icon ni ni-upload-cloud"></em>
                                                <span>Import Fake Stores</span>
                                            </button>
                                            @endif
                                            <form action="{{ route('admin.stores.import-fake-data') }}" id="fake-data-importer-form" method="post">@csrf</form>
                                        </li>
                                        <li class="nk-block-tools-opt">
                                            <a href="{{route('admin.stores.create')}}" class="btn btn-primary btn-sm">
                                                <em class="icon ni ni-plus"></em>
                                                <span>Add Store</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{route('admin.stores.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light">
                                                <em class="icon ni ni-download-cloud"></em>
                                                <span>Export</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="card card-preview mb-4">
                    <div class="card-inner">
                        <form action="{{route('admin.stores.search_stores')}}" class="form-validate is-alter search_form" method="POST">
                            @csrf
                            <div class="row g-4 justify-content-md-center">
                                <div class="col-lg-2 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="store_id">Store ID/Name</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="store" value="" name="store">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="network_id">Network</label>
                                        <div class="form-control-wrap ">
                                            <select class="form-select form-control" data-search="on" id="network_id" name="network_id">
                                                <option value="0">All</option>
                                                @foreach ($networks as $network)
                                                <option value="{{$network->id}}">{{$network->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="status">Status</label>
                                        <div class="form-control-wrap ">
                                            <select class="form-select form-control" data-search="on" id="status" name="status">
                                                <option value="-1">Any</option>
                                                <option value="pending review">Pending Review</option>
                                                <option value="active">Active</option>
                                                <option value="disabled">Disabled</option>
                                                <option value="closed">Closed at Network</option>
                                                <option value="error">Error</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="status">Cashback/Categories Override</label>
                                        <div class="form-control-wrap ">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1" name="overridden">
                                                <label class="custom-control-label" for="customCheck1">Overridden</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 align-self-end">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-block">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
                @include('flash::message')
                <div class="nk-block" id="table-data">
                    @include('admin-dashboard.stores.index_data')

                </div><!-- .nk-block -->
                {{-- <div class="nk-block">
                    <div class="card card-stretch">
                        <div class="card-inner-group">
                           
                            <div class="card-inner px-0">
                                <div class="nk-tb-list nk-tb-ulist" id="table-data">
                                    
                                    @include('admin-dashboard.stores.index_data')                                   
                                    
                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                           
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block --> --}}
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            var route = $('.pagination').attr('route');
            var page = $(this).attr('href').split('page=')[1];

            if (route == 'index') {
                $('#table-data').html(`<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
            </div></div>`);

                pageurl = "{{route('admin.stores.fetch')}}?page="
                var _token = $("input[name=_token]").val();

                $.ajax({

                    url: pageurl + page,
                    method: "POST",
                    data: {
                        _token: _token,
                        page: page
                    },
                    success: function(data) {
                        $('#table-data').html(data);
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                    }
                });
            }

            if (route == 'search') {
                $('#table-data').html(`<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div></div>`);

                var _token = $("input[name=_token]").val();
                var network_id = $("select[name=network_id]").val();
                var store_id = $("input[name=store_id]").val();
                var status = $("select[name=status").val();
                var store_name = $("input[name=store_name]").val();
                $.ajax({
                    url: '{{route("admin.stores.search_stores")}}?page=' + page,
                    method: "POST",
                    data: {
                        _token: _token,
                        network_id: network_id,
                        store_id: store_id,
                        store_name: store_name,
                        status: status,
                        page: page
                    },
                    success: function(data) {
                        $('#table-data').html(data);
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                    }
                });
            }
        });
    });
</script>
<script>
    $(document).ready(function() {

        $(document).on('submit', '.search_form', function(event) {
            $('#table-data').html(`<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
            </div></div>`);
            event.preventDefault();

            var _token = $("input[name=_token]").val();
            var store = $("input[name=store]").val();
            var network_id = $("select[name=network_id]").val();
            var status = $("select[name=status").val();
            var store_name = $("input[name=store_name]").val();
            var overridden = 0;
            if ($("input[name=overridden]").prop("checked")) {
                var overridden = 1;
            }

            $.ajax({
                url: '{{route("admin.stores.search_stores")}}',
                method: "POST",
                data: {
                    _token: _token,
                    network_id: network_id,
                    store: store,
                    store_name: store_name,
                    status: status,
                    overridden: overridden
                },
                success: function(data) {
                    $('#table-data').html(data);
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                }
            });

        });

    });
</script>
<script>
    $(document).ready(function() {

        $(document).on('click', '.delete-store', function(event) {
            var form_id = $(this).attr('form_id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then(function(result) {
                if (result.value) {
                    $('#' + form_id).submit();
                }
            });
            event.preventDefault();
        });

        $('#fake-data-importer-btn').on('click', function(e) {
            let _self = $(this);

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ok!'
            }).then(function(result) {
                if (result.value) {
                    _self
                        .attr('disabled', 'disabled')
                        .append('<span class="spinner-border spinner-border-sm ml-1" role="status" aria-hidden="true"></span>');

                    $('#fake-data-importer-form').submit();
                }
            });
        });
    });
</script>
@endpush