@extends('layouts.admin-dashboard.app')
@section('pageTitle', 'Banners')
@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Banners</h3>
                                <div class="nk-block-des text-soft">
                                    <p>You have total {{ count($banners) }} banners.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        @if (getImporterYMLSettings(config('app.banners_yaml_path')))
                                            @can('add banners')
                                                <ul class="nk-block-tools g-3">
                                                    <li class="nk-block-tools-opt">
                                                        <a href="javascript:void;" class="btn btn-primary btn-sm" id="create-banner">
                                                            <em class="icon ni ni-plus"></em>
                                                            <span>Add Banner</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            @endcan
                                        @endif
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="card card-preview mb-4">
                        <div class="card-inner">
                            <form action="#" class="is-alter search_form" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label" for="title">Search for Title</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="title" value="" name="title">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label" for="status">Status</label>
                                            <div class="form-control-wrap ">
                                                <select class="form-select form-control" data-search="on" id="status" name="status">
                                                    <option value="-1">Any</option>
                                                    <option value="active">Active</option>
                                                    <option value="pending">pending</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4 align-self-end ml-auto">
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
                                @include('admin-dashboard.banners.index_data')
                            </div><!-- .card-inner-group -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    <x-admin-dashboard.modal modalSize="modal-lg" headerAlignment="align-center" formWrapperClass="" />
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Show create modal
            $(document).on('click', '#create-banner', function(event) {
                event.preventDefault();
                $.ajax({
                    url: "{{ route(getAdminPrefix() . '.banners.create') }}",
                    type: 'GET',
                    success: function(response) {
                        $('.title').text('Add Banner');
                        $('#form-wrapper').html(response);
                        $('#save-btn').text('Create');
                        $('#modal').modal('show');
                        initializeSelect2($(this));
                        bannerType();
                        store();
                    },
                    error: function(error) {
                        console.log('Something went wrong.');
                    }
                });
            });

            // Show edit modal
            $(document).on('click', '.banner-edit', function(event) {
                event.preventDefault();
                $.ajax({
                    url: $(this).attr('href'),
                    type: 'GET',
                    success: function(response) {
                        $('.title').text('Edit Banner');
                        $('#form-wrapper').html(response);
                        $('#save-btn').text('Update');
                        $('#modal').modal('show');
                        initializeSelect2($(this));
                        bannerType();
                        store();
                    },
                    error: function(error) {
                        console.log('Something went wrong.');
                    }
                });
            });

            $(document).on('click', '.banner-delete', function(event) {
                event.preventDefault();
                id = $(this).data('id');
                url = $(this).data('url');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                '_token': "{{ csrf_token() }}",
                                'id': id
                            },
                            success: function(response) {
                                $('#table-data').load(location.href + ' #table-data');
                                setTimeout(() => {
                                    (function(NioApp, $) {
                                        'use strict';
                                        toastr.clear();
                                        NioApp.Toast(response.success, 'success');
                                    })(NioApp, jQuery);
                                }, 1000);
                            },
                            error: function(error) {
                                (function(NioApp, $) {
                                    'use strict';
                                    toastr.clear();
                                    NioApp.Toast(error.responseJSON.error, 'error');
                                })(NioApp, jQuery);
                            }
                        });
                    } else {
                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            NioApp.Toast('Something went wrong, try again', 'error');
                        })(NioApp, jQuery);
                    }
                });
            });
        });

        function store() {
            $('.banner-form').on('submit', function(event) {
                event.preventDefault();
                let form = $(this);

                if (!form.valid())
                    return false;

                let btn = $('#save-btn');

                // Add spinner and disable button
                addSpinnerBtn(btn);

                let url = $(this).attr('action');

                let method = 'POST';
                let formData = new FormData(this);
                let id = $('#id').val();

                if (id) {
                    url = $(this).attr('action');
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

                        // Remove spinner and enable button
                        removeSpinnerBtn(btn);

                        $('#table-data').load(location.href + ' #table-data');

                        setTimeout(() => {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(response.success, 'success');
                            })(NioApp, jQuery);
                        }, 1000);
                    },
                    error: function(error) {
                        // Remove spinner and enable button
                        removeSpinnerBtn(btn);

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

        function initializeSelect2() {
            $('.select2').select2({
                placeholder: function() {
                    $(this).data('placeholder');
                }
            });
        }

        function bannerType() {
            $(document).ready(function() {
                if ($('#banner_type').val() == 'upload') {
                    $('.banner_upload').show();
                    $('.banner_link').hide();
                    $('#banner_link').removeAttr('required').val('');
                } else if ($('#banner_type').val() == 'link') {
                    $('.banner_link').show();
                    $('#banner_link').attr('required', 'required');
                    $('.banner_upload').hide();
                }
            });
            $(document.body).on("change", "#banner_type", function() {
                if (this.value == 'upload') {
                    $('.banner_upload').show();
                    $('.banner_link').hide();
                    $('#banner_link').removeAttr('required').val('');
                } else if (this.value == 'link') {
                    $('.banner_link').show();
                    $('#banner_link').attr('required', 'required');
                    $('.banner_upload').hide();
                }
            });
        }
    </script>
@endpush
