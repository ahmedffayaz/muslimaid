@extends('layouts.admin-dashboard.app')
@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Cashbacks</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Total {{ $coms->total() }} cashbacks.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu">
                                        <em class="icon ni ni-menu-alt-r"></em>
                                    </a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <div class="drodown d-inline">
                                                    <a href="#" class="dropdown-toggle btn btn-primary btn-sm" data-toggle="dropdown">
                                                        <em class="icon ni ni-plus mr-1"></em>
                                                        <span>Add Cashback</span>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <ul class="link-list-opt no-bdr d-block">
                                                            <a href="#" id="show-cashback-modal">
                                                                <em class="icon ni ni-sign-gbp"></em>
                                                                <span>Add Cashback</span>
                                                            </a>
                                                            <a href="{{ route('admin.commissions.create_multiple') }}">
                                                                <em class="icon ni ni-sign-gbp"></em>
                                                                <span>Add Multiple Cashback</span>
                                                            </a>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.commissions.export') }}" data-toggle="tooltip" data-placement="top" title="Export cashbacks to CSV"
                                                    id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light">
                                                    <em class="icon ni ni-download-cloud"></em><span>Export</span>
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
                            <form action="{{ route('admin.stores.search_stores') }}" class="form-validate is-alter search_form" method="POST">
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
                                    <div class="col-lg-2 col-md-6">
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
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="status">Status</label>
                                            <div class="form-control-wrap ">
                                                <select class="form-control form-select" name="status_id" required>
                                                    <option value="-1">Any</option>
                                                    @foreach ($statuses as $status)
                                                        <option value="{{ $status->id }}">{{ $status->status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 align-self-end col-md-12">
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
                            <div class="card-inner-group">
                                @include('flash::message')
                                <div class="card-inner px-0">
                                    <div class="nk-tb-list nk-tb-ulist" id="table-data">
                                        @include('admin-dashboard.commissions.index_data')
                                    </div><!-- .nk-tb-list -->
                                </div><!-- .card-inner -->
                            </div><!-- .card-inner-group -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    <!-- @@ Cashback history Modal @e -->
    <div class="modal fade" tabindex="-1" role="dialog" id="history-modal">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header align-center">
                    <div class="nk-file-title">
                        <div class="nk-file-name">
                            <div class="nk-file-name-text"><span class="title">Cashback Status History</span></div>
                        </div>
                    </div>
                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                </div>
                <div id="history" class=" p-4">
                </div>
            </div><!-- .modal-content -->
        </div><!-- .modla-dialog -->
    </div><!-- .modal -->
    @include('layouts.admin-dashboard.includes.modal_lg')
@endsection
@push('scripts')
    <script>
        function initializeSelect2() {
            $('.select-2').select2({
                placeholder: function() {
                    $(this).data('placeholder');
                }
            });
        }
        $(document).ready(function() {
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var route = $('.pagination').attr('route');
                var page = $(this).attr('href').split('page=')[1];

                if (route == 'index') {

                    $('#table-data').html(`<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
            </div></div>`);

                    pageurl = "{{ route('admin.commissions.fetch') }}?page="
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
                    var name = $("input[name=name]").val();
                    var network_id = $("select[name=network_id]").val();
                    var store_id = $("select[name=store_id]").val();
                    var click_id = $("select[name=click_id]").val();
                    var status_id = $("select[name=status_id]").val();
                    var user_id = $("select[name=user_id]").val();
                    $.ajax({
                        url: '{{ route('admin.commissions.search_commissions') }}?page=' + page,
                        method: "POST",
                        data: {
                            _token: _token,
                            name: name,
                            network_id: network_id,
                            store_id: store_id,
                            click_id: click_id,
                            status_id: status_id,
                            user_id: user_id
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
                var name = $("input[name=name]").val();
                var network_id = $("select[name=network_id]").val();
                var click_id = $("input[name=click_id]").val();
                var status_id = $("select[name=status_id]").val();
                var user = $("input[name=user]").val();
                $.ajax({
                    url: '{{ route('admin.commissions.search_commissions') }}',
                    method: "POST",
                    data: {
                        _token: _token,
                        name: name,
                        network_id: network_id,
                        click_id: click_id,
                        status_id: status_id,
                        user: user
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
        $(document).ready(function() {
            $(document).on('click', '.cashback-edit', function(event) {
                event.preventDefault();
                $('#cashback').html(`<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
            </div></div>`);

                var id = $(this).attr('cashback-id');
                var pageurl = $(this).attr('href');
                var _token = $("input[name=_token]").val();
                $.ajax({
                    url: pageurl,
                    method: "GET",
                    data: {
                        _token: _token
                    },
                    success: function(data) {
                        $('#cashback-modal').modal('show');
                        $('#cashback').html(data);
                        initializeSelect2()
                    }
                });
            });

            $(document).on('click', '.cashback-history', function(event) {
                event.preventDefault();
                var pageurl = $(this).attr('href');
                var _token = $("input[name=_token]").val();
                $.ajax({
                    url: pageurl,
                    method: "GET",
                    data: {
                        _token: _token
                    },
                    success: function(data) {
                        $('#history-modal').modal('show');
                        $('#history').html(data);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('submit', '.update_cashback_form', function(e) {
                e.preventDefault();
                var page = $('.pagination li.active span').html();
                var pageurl = "{{ route('admin.commissions.fetch') }}?page="
                var _token = $("input[name=_token]").val();
                var form_action = $(this).attr('action');
                var formdata = new FormData(this);
                $.ajax({
                    url: form_action,
                    method: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $('#cashback-modal').modal('hide');
                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            NioApp.Toast(data.message, data.updated);
                        })(NioApp, jQuery);

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
                });
            });

            // Show modal
            $('#show-cashback-modal').on('click', function(event) {
                event.preventDefault();
                let url = "{{ route('admin.commissions.create') }}";
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('.modal-title').text('Add Cashback');
                        $('.modal-body').html(response);
                        $('#save-btn').text('Create');
                        $('#modal').modal('show');
                        initializeSelect2();
                        store();
                        validation();
                    }
                });
            });

            // Edit Cashback
            $(document).on("click", ".cashback-edit", function(event) {
                event.preventDefault();
                $.ajax({
                    url: $(this).attr('href'),
                    type: 'GET',
                    success: function(response) {
                        $('.modal-title').text('Edit Cashback');
                        $('.modal-body').html(response);
                        $('#save-btn').text('Update');
                        $('#modal').modal('show');
                        initializeSelect2();
                        store();
                        validation();
                    }
                });
            })

            // Store cashback
            function store() {
                $(document).ready(function() {
                    $('#update_cashback_form').on('submit', function(event) {
                        event.preventDefault();
                        let btn = $('#save-btn')
                        btn.attr('disabled', 'disabled').append(
                            '<span class="spinner-border spinner-border-sm ml-1" role="status" aria-hidden="true"></span>');
                        let url = $(this).attr('action');
                        let id = $('#id').val();
                        let method = 'POST';
                        let formData = new FormData(this);
                        if (id) {
                            formData.append('_method', 'PUT');
                        }
                        $.ajax({
                            url: url,
                            type: method,
                            processData: false,
                            contentType: false,
                            data: formData,
                            success: function(response) {
                                $('#modal').modal('hide');
                                $('#update_cashback_form').trigger('reset');
                                btn.removeAttr('disabled', 'disabled').button('refresh');
                                btn.children().remove('span.spinner-border.spinner-border-sm.ml-1').button('refresh');
                                $('#table-data').load(location.href + ' #table-data');
                                (function(NioApp, $) {
                                    'use strict';
                                    toastr.clear();
                                    NioApp.Toast(response.message, 'success');
                                })(NioApp, jQuery);
                            },
                            error: function(error) {
                                btn.removeAttr('disabled', 'disabled').button('refresh');
                                btn.children().remove('span.spinner-border.spinner-border-sm.ml-1').button('refresh');
                                if (error.responseJSON.error) {
                                    (function(NioApp, $) {
                                        'use strict';
                                        toastr.clear();
                                        NioApp.Toast(error.responseJSON.error, 'error');
                                    })(NioApp, jQuery);
                                } else {
                                    (function(NioApp, $) {
                                        'use strict';
                                        toastr.clear();
                                        NioApp.Toast(Object.values(error.responseJSON.errors)[
                                            0], 'error');
                                    })(NioApp, jQuery);
                                }
                            }
                        });
                    });
                });
            }

            // Re-initialize Select2
            function initializeSelect2() {
                $('.form-select').select2({
                    placeholder: function() {
                        $(this).data('placeholder');
                    }
                });
            }

            function validation() {
                $('.forms-validate').validate({
                    errorClass: 'invalid-feedback d-block',
                    rules: {
                        exit_click_id: {
                            required: true
                        },
                        order_value: {
                            required: true
                        },
                        network_commission: {
                            required: true
                        },

                    },
                    submitHandler: function(form) {
                        if ($(form).valid())
                            form.submit();
                        return false;
                    }
                });
            }

        });
    </script>
@endpush
