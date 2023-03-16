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
                                    <form action="{{ route('admin.pages.store') }}" class="form-validate pages-form" method="POST">
                                        @csrf
                                        <div class="row g-4">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="title">Title</label>
                                                    <div class="form-control-wrap">
                                                        <input id="title" type="text" class="form-control " name="title" placeholder="Title" value="" required>
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
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label">Banner Image</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
                                                                <i class="fa fa-picture-o"></i> Choose
                                                            </a>
                                                        </span>
                                                        <input id="thumbnail" class="form-control" type="text" name="filepath">
                                                    </div>
                                                    <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Page Short Description</label>
                                                    <textarea class="form-control" name="short_description" placeholder="Page Short Description" value=""></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Meta Description</label>
                                                    <textarea class="form-control" name="meta_description" placeholder="Meta Description" value=""></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Meta Keywords</label>
                                                    <div class="form-control-wrap">
                                                        <input id="blog-title" type="text" class="form-control" name="meta_keyword" placeholder="Meta keyword" value="">
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
        $(document).ready(function() {

            // Define function to open filemanager window
            var lfm = function(options, cb) {
                var route_prefix = (options && options.prefix) ? options.prefix : '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                window.SetUrl = cb;
            };

            // Define LFM summernote button
            var LFMButton = function(context) {
                var ui = $.summernote.ui;
                var button = ui.button({
                    contents: '<i class="note-icon-picture"></i> ',
                    tooltip: 'Insert image with filemanager',
                    click: function() {
                        lfm({
                            type: 'image',
                            prefix: '/filemanager'
                        }, function(lfmItems, path) {
                            lfmItems.forEach(function(lfmItem) {
                                context.invoke('insertImage', lfmItem.url);
                            });
                        });
                    }
                });
                return button.render();
            };

            // Initialize summernote with LFM button in the popover button group
            // Please note that you can add this button to any other button group you'd like
            $('#summernote-editor').summernote({
                toolbar: [
                    ['popovers', ['lfm']],
                ],
                buttons: {
                    lfm: LFMButton
                }
            })
        });
    </script>
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
                            window.location.href = data.url;
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
@endpush
