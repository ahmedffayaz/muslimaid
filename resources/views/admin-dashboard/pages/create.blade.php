@extends('layouts.admin-dashboard.app')

@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="components-preview mx-auto">
                        <div class="nk-block nk-block-lg">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <div class="nk-block-des"></div>
                                </div>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="card">
                                <div class="card-inner">
                                    <div class="card-head">
                                    </div>
                                    <form action="{{ route(getAdminPrefix() . '.pages.store') }}" class="form-validate pages-form" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <div class="row g-4">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="title">Title</label>
                                                    <div class="form-control-wrap">
                                                        <input id="title" type="text" class="form-control " name="title" placeholder="Title" value="{{ old('title') }}"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="status">Status</label>
                                                    <div class="form-control-wrap ">
                                                        <div class="">
                                                            <select class="form-control form-select" name="status" required>
                                                                <option selected value="active">Active</option>
                                                                <option value="inactive">In-active</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label class="form-label">&nbsp;</label>
                                                    <button type="button" class="btn btn-primary form-control" id="view-shortcodes-btn">
                                                        <em class="icon ni ni-eye"></em>
                                                        <span>View Available Short Codes</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <fieldset class="uk-fieldset">
                                                    <div class="laraberg-sidebar">
                                                        <textarea name="excerpt" placeholder="Excerpt"></textarea>
                                                    </div>
                                                    <div class="uk-margin">
                                                        <textarea name="content" id="content" hidden></textarea>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="banner_image">Banner <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="banner_image" id="banner_image" onchange="BannerReadURL(this);">
                                                            <label class="custom-file-label" for="banner_image">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <div class="preview-wrapper">
                                                            <img id="banner_image-preview" src="" alt="logo" class="d-none"
                                                                style="max-height: 60px; max-width: 60px;" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Page Short Description</label>
                                                    <textarea class="form-control" name="short_description" placeholder="Page Short Description" value="{{ old('short_description') }}"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Meta Description</label>
                                                    <textarea class="form-control" name="meta_description" placeholder="Meta Description" value="{{ old('meta_description') }}"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Meta Keywords</label>
                                                    <div class="form-control-wrap">
                                                        <input id="seo-keyword" type="text" class="form-control" name="meta_keyword" placeholder="Meta keyword"
                                                            value="{{ old('meta_keyword') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Meta Title</label>
                                                    <div class="form-control-wrap">
                                                        <input id="seo-title" type="text" class="form-control" name="meta_title" placeholder="Meta Title"
                                                            value="{{ old('meta_title') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <button class="btn btn-primary" type="submit">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- .nk-block -->
                    </div><!-- .components-preview -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="view-shortcodes-modal" tabindex="-1" role="dialog" aria-labelledby="view-shortcodes-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            Laraberg.init('content', {
                height: '600px',
                laravelFilemanager: true,
                sidebar: true
            })
        });

        let viewShortcodesModal = $('#view-shortcodes-modal');

        $(document).on('click', '#view-shortcodes-btn', function(e) {
            e.preventDefault();

            let _self = $(this);
            let btnHtml = _self.html();

            _self
                .attr('disabled', 'disabled')
                .append('<span class="spinner-border spinner-border-sm ml-1" role="status" aria-hidden="true"></span>');

            $.ajax({
                url: `{{ route(getAdminPrefix() . '.pages.view-short-codes') }}`,
                method: 'post',
                data: {
                    _token: $('input[name=_token]').val(),
                },
                success: function(data) {
                    viewShortcodesModal.find('.modal-content').html(data);
                    viewShortcodesModal.modal('show');

                    _self.removeAttr('disabled').html(btnHtml);
                }
            });
        });
    </script>
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script>
    <style>
        .popover {
            top: auto;
            left: auto;
        }
    </style>
    <script>
        $('.form-validate').validate({
            rules: {
                title: {
                    required: true
                },
                banner_image: {
                    required: true
                }
            },
            submitHandler: function(form) {
                if ($(form).valid()) {
                    var _token = $("input[name=_token]").val();
                    var form_action = $(form).attr('action');
                    var formdata = new FormData(form);
                    $(form).find('input[type="file"]').each(function() {
                        var fileInput = $(this)[0];
                        if (fileInput.files.length > 0) {
                            formdata.append($(this).attr('name'), fileInput.files[0]);
                        }
                    });
                    // Populate hidden form on submit
                    $.ajax({
                        url: form_action,
                        method: "POST",
                        data: formdata,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(data.message, 'success');
                            })(NioApp, jQuery);
                            window.location.href = data.url;
                        },
                        error: function(xhr, status, error) {
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                (function(NioApp, $) {
                                    'use strict';
                                    toastr.clear();
                                    NioApp.Toast(xhr.responseJSON.error, 'error');
                                })(NioApp, jQuery);
                            } else {
                                (function(NioApp, $) {
                                    'use strict';
                                    toastr.clear();
                                    NioApp.Toast(error, 'error');
                                })(NioApp, jQuery);
                            }
                        }
                    });
                }
                return false;
            }
        });
    </script>
    <script>
        function BannerReadURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    if (input.id === "banner_image") {
                        $('#banner_image-preview').attr('src', e.target.result).removeClass('d-none');
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
