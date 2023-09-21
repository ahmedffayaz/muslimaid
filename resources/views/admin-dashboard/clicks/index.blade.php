@extends('layouts.admin-dashboard.app')
@push('styles')
    <style>
        .nk-tb-list {
            display: block !important;
            width: fit-content !important;
            min-width: 100%;
        }
    </style>
@endpush
@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Exit Clicks</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Total {{ $clicks->total() }} exit clicks.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">

                                            <li><a href="{{ route(getAdminPrefix() . '.clicks.export') }}" id="export" class="btn btn-success btn-sm"
                                                    class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                        </ul>
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="card card-preview mb-4">
                        <div class="card-inner">
                            <form action="{{ route(getAdminPrefix() . '.stores.search_stores') }}" class="form-validate is-alter search_form" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-lg-3 col-md-9">
                                        <div class="form-group">
                                            <label class="form-label" for="click_id">Click ID/ User ID/ Store ID</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="click_id" value="" name="click_id">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-9">
                                        <div class="form-group">
                                            <label class="form-label" for="user">User Name/ Store Name</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="user" value="" name="user">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label" for="network_id">Network</label>
                                            <div class="form-control-wrap ">
                                                <select class="form-select form-control" data-search="on" id="network_id" name="network_id">
                                                    <option value="0">All</option>
                                                    @foreach ($networks as $network)
                                                        <option value="{{ $network->id }}">{{ $network->name }}</option>
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
                            </form>
                        </div>
                    </div>
                    @include('flash::message')
                    <div class="nk-block">
                        <div class="card card-stretch">
                            <div class="card-inner-group" id="table-data">
                                @include('admin-dashboard.clicks.index_data')
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
        $(document).ready(function() {
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var route = $('.pagination').attr('route');
                var page = $(this).attr('href').split('page=')[1];

                if (route == 'index') {

                    $('#table-data').html(`<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
            </div></div>`);

                    pageurl = "{{ route(getAdminPrefix() . '.clicks.fetch') }}?page="
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

                    $('#table-data').html(`<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
            </div></div>`);

                    var _token = $("input[name=_token]").val();
                    var network_id = $("select[name=network_id]").val();
                    var store = $("input[name=store]").val();
                    var user = $("input[name=user]").val();
                    var click_id = $("input[name=click_id]").val();
                    $.ajax({
                        url: '{{ route(getAdminPrefix() . '.clicks.search_clicks') }}?page=' + page,
                        method: "POST",
                        data: {
                            _token: _token,
                            network_id: network_id,
                            store: store,
                            user: user,
                            click_id: click_id
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
                event.preventDefault();
                $('#table-data').html(`<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
            </div></div>`);

                var _token = $("input[name=_token]").val();
                var network_id = $("select[name=network_id]").val();
                var user = $("input[name=user]").val();
                var click_id = $("input[name=click_id]").val();
                $.ajax({
                    url: '{{ route(getAdminPrefix() . '.clicks.search_clicks') }}',
                    method: "POST",
                    data: {
                        _token: _token,
                        network_id: network_id,
                        user: user,
                        click_id: click_id
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
@endpush
