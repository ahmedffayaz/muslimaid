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
                                    {{-- <h4 class="title nk-block-title">Create Page</h4> --}}
                                    <div class="nk-block-des">
                                        {{-- <p>You can make style out your....</p> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-inner">
                                    <div class="card-head">
                                    </div>
                                    <form action="{{ route('admin.pages.update', $page) }}" class="form-validate pages-form" method="POST">
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
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="default-06">Status</label>
                                                    <div class="form-control-wrap ">
                                                        <div class="">
                                                            @if ($page->default)
                                                                <select class="form-control form-select disabled " name="status" required>
                                                                    <option @if ($page->status == 1) selected @endif value="1">Active</option>
                                                                </select>
                                                            @else
                                                                <select class="form-control form-select" name="status" required>
                                                                    <option @if ($page->status == 1) selected @endif value="1">Active</option>
                                                                    <option @if ($page->status == 0) selected @endif value="0">In-active</option>

                                                                </select>
                                                            @endif
                                                        </div>
                                                    </div>
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
@endsection
@push('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            Laraberg.init('content', {
                height: '600px',
                laravelFilemanager: true,
                sidebar: true
            })
        })
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
@endpush
