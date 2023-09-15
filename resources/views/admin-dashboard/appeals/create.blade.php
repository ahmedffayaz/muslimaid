@extends('layouts.admin-dashboard.app')
@section('content')
    @if (session()->has('message'))
        <div class="container alert {{ session('alert-class') }} alert-dismissible fade show alert-important" role="alert">
            {{ session('message') }}.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="components-preview wide-md mx-auto">
                        <div class="nk-block nk-block-lg">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h4 class="title nk-block-title">Add Appeal</h4>
                                    <div class="nk-block-des">
                                    </div>
                                </div>
                            </div>
                            @include('flash::message')
                            <div class="card">
                                <div class="card-inner">
                                    <div class="card-head">
                                        <h5 class="card-title">Appeal Info</h5>
                                    </div>
                                    <form action="{{ route(getAdminPrefix() . '.appeals.store') }}" class="gy-3 form-validate is-alter charity-form" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row g-4">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                                                        @error('title')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <label class="form-label" for="description">Description</label>
                                                    <!-- Create the editor container -->
                                                    <div id="editor-container"> </div>
                                                    <input name="description" value="{{ old('description') }}" type="hidden">
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="image_type">Image <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap ">
                                                        <div class="form-control-select">
                                                            <select class="form-control form-select" name="image_type" id='image_type' value="{{ old('image_type') }}" required>
                                                                <option value="upload">Upload</option>
                                                                <option value="link">Link</option>
                                                            </select>
                                                            @error('image_type')
                                                                <span class="invalid-feedback d-block" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 image_link">
                                                <div class="form-group">
                                                    <label class="form-label" for="image_link">Image Link <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input type="link" id="image_link" class="form-control" id="image_link" name="image_link" value="{{ old('image_link') }}"
                                                            required onchange="readLinkURL(this);">
                                                        @error('image_link')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 ">
                                                    <div class="form-group">
                                                        <div class="preview-wrapper">
                                                            <img id="image_link-preview" src="" alt="store image" class="d-none"
                                                                style="max-height: 60px; max-width: 60px;" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 image_upload">
                                                <div class="form-group">
                                                    <label class="form-label" for="image_upload">Image Upload <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="image_upload" id="image_upload"
                                                                value="{{ old('image_upload') }}" onchange="readBannerURL(this);" required>
                                                            <label class="custom-file-label" for="image_upload">Choose file</label>
                                                            @error('image_upload')
                                                                <span class="invalid-feedback d-block" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <div class="preview-wrapper">
                                                            <img id="image-preview" src="" alt="image" class="d-none" style="max-width:80px;max-height:120px" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="donation-link">Donation Link</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="donation-link" name="donation_link" value="{{ old('donation_link') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap ">
                                                        <div class="form-control-select">
                                                            <select class="form-control form-select" id="status" name="status" value="{{ old('status') }}" required>
                                                                <option value="1">Active</option>
                                                                <option value="0">In-active</option>
                                                            </select>
                                                            @error('status')
                                                                <span class="invalid-feedback d-block" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label" for="default-06">Tags</label>
                                                <div class="form-control-wrap ">
                                                    <div class="">
                                                        <select class="form-control form-select select-2" name="tags[]" id="tags" multiple>
                                                            @foreach ($tags as $tag)
                                                                <option value="{{ $tag->id }}">{{ $tag->title }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="meta-description">Meta Description</label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" id="meta-description" name="meta_description"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="meta-keyword">Meta Keyword</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="meta-keyword" name="meta_keyword" value="{{ old('meta_keyword') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="meta-keyword">Meta title</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="meta-title" name="meta_title" value="{{ old('meta_title') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-lg btn-primary">Save</button>
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
        tinymce.init({
            selector: 'div#editor-container', // Replace this CSS selector to match the placeholder element for TinyMCE
            plugins: 'code table lists link image textcolor',
            toolbar: 'blocks | bold italic underline | alignleft aligncenter alignright alignjustify | link unlink | image | bullist numlist | code | table | forecolor backcolor',
            cleanup: true
        });
        var form = document.querySelector('form');
        $(".charity-form").submit(function(e) {
            // Populate hidden form on submit
            var editor = tinymce.get('editor-container')
            var desc = document.querySelector('input[name=description]');
            desc.value = editor.getContent();
        });
    </script>
    <script>
        $(document).ready(function() {
            if ($('#image_type').val() == 'upload') {
                $('.image_upload').show();
                $('.image_link').hide();
                $('#image_link').removeAttr('required').val('');

            } else if ($('#image_type').val() == 'link') {

                $('.image_link').show();
                $('#image_link').attr('required', 'required');
                $('.image_upload').hide();

            }

        });
        $(document.body).on("change", "#image_type", function() {
            if (this.value == 'upload') {
                $('.image_upload').show();
                $('.image_link').hide();
                $('#image_link').removeAttr('required').val('');

            } else if (this.value == 'link') {

                $('.image_link').show();
                $('#image_link').attr('required', 'required');
                $('.image_upload').hide();

            }

        });

        function initializeSelect2() {
            $('.form-select').select2({
                placeholder: function() {
                    $(this).data('placeholder');
                }
            });
        }

        $('.form-validate').validate({
            rules: {
                image_link: {
                    url: true,
                },
            }
        });
    </script>
    <script>
        function appealReadURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    if (input.id === "image_upload") {
                        $('#image-preview').attr('src', e.target.result).removeClass('d-none');
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
