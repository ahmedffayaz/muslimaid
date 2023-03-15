@extends('layouts.admin-dashboard.app')

@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">

                    <div class="nk-block-head nk-block-head-sm">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Vouchers</h3>
                                <div class="nk-block-des text-soft">
                                    <p>You have total {{ $vouchers->total() }} vouchers.</p>
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu">
                                        <em class="icon ni ni-menu-alt-r"></em>
                                    </a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <a href="{{ route('admin.vouchers.create') }}" data-toggle="modal" class="btn btn-primary btn-sm add-voucher">
                                                    <em class="icon ni ni-plus"></em>
                                                    <span>Add Voucher</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.vouchers.export') }}" id="export" class="btn btn-success btn-sm"
                                                    class="btn btn-white btn-outline-light">
                                                    <em class="icon ni ni-download-cloud"></em>
                                                    <span>Export</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-preview mb-4">
                        <div class="card-inner">
                            <form action="{{ route('admin.stores.search_stores') }}" class="form-validate is-alter" id="search-form" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-lg-4">
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

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label" for="stores_id">Store</label>
                                            <div class="form-control-wrap ">
                                                <select class="form-select form-control" data-search="on" id="stores_id" name="stores_id">
                                                    <option value="0">All</option>
                                                    @foreach ($stores as $store)
                                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 align-self-end">
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
                            <div class="card-inner-group">
                                <div class="card-inner px-0">
                                    <div class="nk-tb-list nk-tb-ulist" id="table-data">

                                        @include('admin-dashboard.vouchers.index_data')

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Add Voucher Modal -->
    <div class="modal fade" tabindex="-1" id="add-voucher-modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header align-center">
                    <div class="nk-file-title">
                        <div class="nk-file-name">
                            <div class="nk-file-name-text">
                                <span class="title">Add Voucher</span>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                </div>
                <div id="add-voucher-form-placeholder" class=" p-4"></div>
            </div>
        </div>
    </div>

    <!-- Edit Voucher Modal -->
    <div class="modal fade" tabindex="-1" id="edit-voucher-modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header align-center">
                    <div class="nk-file-title">
                        <div class="nk-file-name">
                            <div class="nk-file-name-text"><span class="title">Edit Voucher</span></div>
                        </div>
                    </div>
                    <a href="javascript:void(0);" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                </div>
                <div id="edit-voucher-form-placeholder" class=" p-4"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let tableSpinner = `<div class="text-center">
                                <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>`;

        $(document).on('submit', '#search-form', function(e) {
            e.preventDefault();

            $('#table-data').html(tableSpinner);

            $.ajax({
                url: "{{ route('admin.vouchers.search_vouchers') }}",
                method: "POST",
                data: {
                    _token: $("input[name=_token]").val(),
                    network_id: $("select[name=network_id]").val(),
                    store_id: $("select[name=stores_id]").val(),
                },
                success: function(data) {
                    $('#table-data').html(data);

                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                }
            });
        });

        $(document).on('submit', '#create-edit-voucher-form', function(e) {
            e.preventDefault();
            let form = $(this);
            let submitBtn = form.find('[type="submit"]');
            let submitBtnHtml = submitBtn.html();

            submitBtn.attr('disabled', 'disabled')
                .append('<span class="spinner-border spinner-border-sm ml-1" role="status" aria-hidden="true"></span>');

            $.ajax({
                url: form.attr('action'),
                method: 'post',
                data: form.serialize(),
                success: function(data) {
                    submitBtn.removeAttr('disabled').html(submitBtnHtml);

                    if (data.success) {
                        $('#edit-voucher-modal').modal('hide');
                        $('#add-voucher-modal').modal('hide');
                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            NioApp.Toast(data.message, 'success');
                        })(NioApp, jQuery);

                        fetchVouchers();
                    } else {
                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            NioApp.Toast(data.message, 'error');
                        })(NioApp, jQuery);
                    }
                },
                error: function(data) {
                    submitBtn.removeAttr('disabled').html(submitBtnHtml);

                    (function(NioApp, $) {
                        'use strict';
                        toastr.clear();
                        NioApp.Toast('Something went wrong.', 'error');
                    })(NioApp, jQuery);
                },
                complete: function(data) {
                    // Just in case something breaks
                    submitBtn.removeAttr('disabled').html(submitBtnHtml);
                }
            });
        });

        $(document).on('click', '.add-voucher', function(e) {
            e.preventDefault();

            let modal = $('#add-voucher-modal');
            let formPlaceholder = $('#add-voucher-form-placeholder');

            $.ajax({
                url: $(this).attr('href'),
                method: "GET",
                data: {
                    _token: $("input[name=_token]").val()
                },
                success: function(data) {

                    modal.modal('show');
                    formPlaceholder.html(data);
                    $('.select-2').each(function() {
                        initializeSelect2($(this));
                    });

                    formPlaceholder.find(".promotion_end_date").datepicker();
                    formPlaceholder.find(".promotion_start_date").datepicker();
                    checkVoucherType();
                    attachFormValidator($(document).find('#create-edit-voucher-form'));
                }
            });
        });
        $(document).on('click', '.edit-voucher', function(e) {
            e.preventDefault();

            let modal = $('#edit-voucher-modal');
            let formPlaceholder = $('#edit-voucher-form-placeholder');

            $.ajax({
                url: $(this).attr('href'),
                method: "GET",
                data: {
                    _token: $("input[name=_token]").val()
                },
                success: function(data) {
                    modal.modal('show');
                    formPlaceholder.html(data);
                    $('.select-2').each(function() {
                        initializeSelect2($(this));
                    });

                    formPlaceholder.find(".promotion_end_date").datepicker();
                    formPlaceholder.find(".promotion_start_date").datepicker();
                    checkVoucherType();
                    attachFormValidator($(document).find('#create-edit-voucher-form'));
                }
            });
        });
        $(document).on("change", "#promotion_type", function() {
            checkVoucherType();
        });
        $(document).on('click', '.delete', function(event) {
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
                event.preventDefault();
            });
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();

            var route = $('.pagination').attr('route');
            var page = $(this).attr('href').split('page=')[1];

            if (route == 'index') {
                $('#table-data').html(tableSpinner);

                pageurl = "{{ route('admin.vouchers.fetch') }}?page="
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
                $('#table-data').html(tableSpinner);

                var _token = $("input[name=_token]").val();
                var network_id = $("select[name=network_id]").val();
                var store_id = $("select[name=store_id]").val();

                $.ajax({
                    url: '{{ route('admin.vouchers.search_vouchers') }}?page=' + page,
                    method: "POST",
                    data: {
                        _token: _token,
                        network_id: network_id,
                        store_id: store_id
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

        function fetchVouchers() {
            $('#table-data').html(tableSpinner);

            $.ajax({
                url: "{{ route('admin.vouchers.fetch') }}",
                method: 'POST',
                data: {
                    _token: $('input[name=_token]').val(),
                },
                success: function(data) {
                    $('#table-data').html(data);
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                }
            });
        }

        function initializeSelect2(selectElementObj) {
            selectElementObj.select2({
                placeholder: function() {
                    $(this).data('placeholder');
                }
            });
        }

        function checkVoucherType() {
            if ($('#promotion_type').val() == 'Coupon') {
                $('.coupon-div').show();
                $('#coupon_code').attr('required', 'required');
            } else {
                $('.coupon-div').hide();
                $('#coupon_code').removeAttr('required').val('');
            }
        }

        function attachFormValidator(form) {
            jQuery.validator.addMethod("minValue", function(value, element, param) {
                return this.optional(element) || value >= param;
            }, "Value must be equal to or greater than {0}.");
            form.validate({
                rules: {
                    promotion_start_date: {
                        required: true,
                    },
                    promotion_end_date: {
                        required: true,
                        customdate: true,
                    },
                    click_url: {
                        required: true,
                        url: true
                    },
                    destination: {
                        required: true,
                        url: true
                    },
                    sale_commission: {
                        required: true,
                        minValue: 0.1,
                    }
                },
                messages: {
                    promotion_end_date: {
                        customdate: 'End date must be greater than start date',
                    }
                }
            });

            $.validator.addMethod('customdate', function(value, element) {
                var startDate = new Date($(element).closest('form').find('.promotion_start_date').val());
                var endDate = new Date(value);
                return this.optional(element) || (startDate < endDate);
            });


        }

        attachFormValidator($(document).find('#add-voucher-form'));
    </script>
@endpush
