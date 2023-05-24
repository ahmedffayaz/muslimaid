@extends('layouts.admin-dashboard.app')
@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Countries</h3>
                                <div class="nk-block-des text-soft">
                                    <p>You have total {{ $countries->total() }} countries.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="card card-preview mb-4">
                        <div class="card-inner">
                            <form action="" class="form-validate is-alter search_form" method="POST">
                                @csrf
                                <div class="row g-4 justify-content-md-center">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="name">Country</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="name" value="" name="name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="region_id">Region</label>
                                            <div class="form-control-wrap ">
                                                <select class="form-select form-control" data-search="on" id="region_id" name="region_id">
                                                    <option value="0">Region</option>
                                                    @foreach ($regions as $region)
                                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="currency_id">Currency</label>
                                            <div class="form-control-wrap ">
                                                <select class="form-select form-control" data-search="on" id="currency_id" name="currency_id">
                                                    <option value="0">All Currency</option>
                                                    @foreach ($currencies as $currency)
                                                        <option value="{{ $currency->id }}">{{ $currency->name }}</option>
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
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
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

                    <div class="nk-block">
                        <div class="card card-stretch">
                            <div class="card-inner-group">

                                <div class="card-inner px-0 table-responsive">
                                    <div class="nk-tb-list nk-tb-ulist" id="table-data">
                                        @include('admin-dashboard.countries.index_data')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    @include('layouts.admin-dashboard.includes.modal_lg')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // Edit form
            $(document).on('click', '.edit-form', function(event) {
                event.preventDefault();
                let id = $(this).data('id');
                let url = "{{ route(getAdminPrefix() . '.countries.edit', ':id') }}";
                // Replace id
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('.modal-title').text('Edit Country');
                        $('.modal-body').html(response);
                        $('#save-btn').text('Update');
                        $('.modal').modal('show');
                        $('.form-select').select2({
                            minimumResultsForSearch: -1
                        });
                        store();
                    }
                });
            });

            // Save form data
            function store() {
                $('#save_modal_form').on('submit', function(event) {
                    event.preventDefault();
                    let id = $('#id').val();
                    let method = 'POST';
                    let formdata = new FormData(this);

                    if (id) {
                        url = "{{ route(getAdminPrefix() . '.countries.update', ':id') }}";
                        url = url.replace(':id', id);
                        formdata.append('_method', 'PUT');
                    }

                    $.ajax({
                        url: url,
                        type: method,
                        data: formdata,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $('#modal').modal('hide');
                            $('#table-data').load(location.href + ' #table-data');
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(response.success, 'success');

                            })(NioApp, jQuery);
                        },
                        error: function(error) {
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
                                    NioApp.Toast(Object.values(error.responseJSON.errors)[0], 'error');

                                })(NioApp, jQuery);
                            }
                        }
                    });
                });
            }
        });
    </script>
    <script>
        $('#upload_type').on('change', function(event) {
            let option_selected = $("option:selected", this);
            let value_selected = this.value;
            let html = '';

            if (value_selected == 'upload') {
                html = `<label class="form-label" for="type_value">Upload Type</label>
                        <div class="form-control-wrap">
                            <div class="input-group">
                                <input type="file" class="form-control" name="type_value" id="type_value">
                            </div>
                        </div>`;
            } else if (value_selected == 'url') {
                html = `<label class="form-label" for="type_value">Upload Type</label>
                        <div class="form-control-wrap">
                            <div class="input-group">
                                <input type="text" class="form-control" name="type_value" placeholder="URL Here" id="type_value">
                            </div>
                        </div>`;
            } else if (value_selected == 'code') {
                html = `<label class="form-label" for="type_value">Upload Type</label>
                        <div class="form-control-wrap">
                            <div class="input-group">
                                <textarea class="form-control" rows="3" name="type_value" placeholder="HTML Code Here" id="type_value"></textarea>
                            </div>
                        </div>`;
            }

            $('#type').html(html);
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
                var name = $("input[name=name").val();
                var region_id = $("select[name=region_id]").val();
                var currency_id = $("select[name=currency_id").val();
                var status = $("select[name=status").val();
                $.ajax({
                    url: '{{ route(getAdminPrefix() . '.countries.search') }}',
                    method: "POST",
                    data: {
                        _token: _token,
                        name: name,
                        region_id: region_id,
                        currency_id: currency_id,
                        status: status
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
