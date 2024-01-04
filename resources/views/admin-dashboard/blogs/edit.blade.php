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
                                    <form action="{{ route(getAdminPrefix() . '.blogs.update', $blog) }}" class="" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-4">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Title <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input id="blog-title" type="text" class="form-control " name="title" placeholder="Title" value="{{ $blog->title }}"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <fieldset class="uk-fieldset">
                                                    <div class="uk-margin">
                                                        <textarea name="content" id="content" hidden>{{ $blog->lb_raw_content }}</textarea>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="form-group">
                                                    <label class="form-label" for="featured_image">Banner <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="featured_image" id="featured_image"
                                                                onchange="BannerReadURL(this);">
                                                            <label class="custom-file-label" for="featured_image">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <div class="preview-wrapper">
                                                            @if ($blog->featured_image)
                                                                <img id="featured_image-preview" src="{{ asset($blog->featured_image) }}"
                                                                    style="max-height: 60px; max-width: 60px;" alt="">
                                                            @else
                                                                <img id="featured_image-preview" src="" alt="logo" class="d-none"
                                                                    style="max-height: 60px; max-width: 60px;" />
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Publish Date</label>
                                                    <div class="form-control-wrap">
                                                        <input id="publish_date" type="date" class="form-control " name="publish_date" placeholder="Publish Date" value="{{ $blog->publish_date }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Meta Title</label>
                                                    <div class="form-control-wrap">
                                                        <input id="blog-title" type="text" class="form-control " name="meta_title" placeholder="Meta Title" value="{{ $blog->meta_title }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Meta Description</label>
                                                    <textarea class="form-control " name="meta_description" placeholder="Meta Description" value=""> {{ $blog->meta_description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Meta Keywords</label>
                                                    <div class="form-control-wrap">
                                                        <input id="blog-keyword" type="text" class="form-control " name="meta_keyword" placeholder="Meta keyword"
                                                            value="{{ $blog->meta_keyword }}">
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
        window.onbeforeunload = function() {
            return null;
        };
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
        function BannerReadURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    if (input.id === "featured_image") {
                        $('#featured_image-preview').attr('src', e.target.result).removeClass('d-none');
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        jQuery.validator.addMethod("regex", function(value, element) {
            return this.optional(element) || /^[\w. ]+$/i.test(value);
        }, "Letters, numbers, and underscores only please");

        $('.form-validate').validate({
            rules: {
                title: {
                    required: true,
                    regex: true
                }
            }
        });
    </script>
@endpush
