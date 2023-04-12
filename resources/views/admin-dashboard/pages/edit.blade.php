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
                                    <div class="nk-block-des">
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-inner">
                                    <div class="card-head">
                                    </div>
                                    <form action="{{ route('admin.pages.update', $page) }}" class="form-validate pages-form" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-4">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Title</label>
                                                    <div class="form-control-wrap">
                                                        <input id="page-title" type="text" class="form-control " name="title" placeholder="Title" value="{{ $page->title }}"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="default-06">Status</label>
                                                    <div class="form-control-wrap ">
                                                        <div class="">
                                                            @if ($page->type == 'system')
                                                                <select class="form-control form-select disabled " name="status" required>
                                                                    <option @if ($page->status == 'active') selected @endif value="active">Active</option>
                                                                </select>
                                                            @else
                                                                <select class="form-control form-select" name="status" required>
                                                                    <option @if ($page->status == 'active') selected @endif value="active">Active</option>
                                                                    <option @if ($page->status == 'inactive') selected @endif value="inactive">In-active</option>
                                                                </select>
                                                            @endif
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
                                                    <div class="uk-margin">
                                                        <textarea name="content" id="content" hidden>{{ $page->lb_raw_content }}</textarea>
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
                                                            @if ($page->banner_image)
                                                                <img id="banner_image-preview" src="{{ asset($page->banner_image) }}" style="max-height: 60px; max-width: 60px;"
                                                                    alt="">
                                                            @else
                                                                <img id="banner_image-preview" src="" alt="logo" class="d-none"
                                                                    style="max-height: 60px; max-width: 60px;" />
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Page Short Description</label>
                                                    <textarea class="form-control" name="short_description" placeholder="Page Short Description">{{ $page->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Meta Description</label>
                                                    <textarea class="form-control" name="meta_description" placeholder="Meta Description">{{ $page->meta_description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Meta Keywords</label>
                                                    <div class="form-control-wrap">
                                                        <input id="blog-title" type="text" class="form-control" name="meta_keyword" placeholder="Meta keyword"
                                                            value="{{ $page->meta_keyword }}">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <button class="btn btn-primary" type="submit">Save</button>

                                                    </div>
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
                url: `{{ route('admin.pages.view-short-codes') }}`,
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
                }
            },
            submitHandler: function(form) {
                if ($(form).valid()) {
                    var _token = $("input[name=_token]").val();
                    var form_action = $(this).attr('action');
                    var formdata = new FormData(this);
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
