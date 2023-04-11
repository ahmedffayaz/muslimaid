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
                                    <form action="{{ route('admin.blogs.store') }}" class="form-validate is-alter" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <div class="row g-4">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Title <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input id="blog-title" type="text" class="form-control " name="title" placeholder="Title" value="" required>
                                                    </div>
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
                                                            <img id="featured_image-preview" src="" alt="logo" class="d-none"
                                                                style="max-height: 60px; max-width: 60px;" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Meta Description</label>
                                                    <textarea class="form-control " name="meta_description" placeholder="Meta Description" value=""></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="reviewer">Meta Keywords</label>
                                                    <div class="form-control-wrap">
                                                        <input id="blog-title" type="text" class="form-control " name="meta_keyword" placeholder="Meta keyword" value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <button onclock="remobe_bug()" class="btn btn-primary add-blog" type="submit">Save</button>
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
@endpush
