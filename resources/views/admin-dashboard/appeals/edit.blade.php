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
                                    <h4 class="title nk-block-title">Edit Appeal</h4>
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
                                    <form action="{{ route(getAdminPrefix() . '.appeals.update', $appeal) }}" class="gy-3 form-validate is-alter appeal-form" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        <div class="row g-4">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="title" name="title" value="{{ $appeal->title }}" required>
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
                                                    <div id="editor-container">{!! $appeal->description !!}</div>
                                                    <input name="description" type="hidden">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="image_type">Image <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap ">
                                                        <div class="form-control-select">
                                                            <select class="form-control" name="image_type" id='image_type' required>
                                                                <option value="upload" @if ($appeal->image_type == 'upload') selected @endif>Upload</option>
                                                                <option value="link" @if ($appeal->image_type == 'link') selected @endif>Link</option>
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
                                                        <input type="link" class="form-control" id="image_link" name="image_link" value="{{ $appeal->image_link }}" required onchange="readLinkURL(this);" >
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
                                                            @if($appeal->image_link )
                                                            <img id="image_link-preview" src="{{ asset($appeal->image_link) }}" alt="appeal image" style="max-height: 60px; max-width: 60px;" />
                                                            @else
                                                            <img id="image_link-preview" src="" alt="Appeal image" class="d-none" style="max-height: 60px; max-width: 60px;" />
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 image_upload">
                                                <div class="form-group">
                                                    <label class="form-label" for="image_upload">Image Upload <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="image_upload" id="image_upload" value="{{ old('image_upload') }}"
                                                                onchange="readBannerURL(this);" >
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
                                                            @if ($appeal->image_upload)
                                                                <img id="image-preview"src="{{ asset($appeal->image_upload) }}"
                                                                    style="max-height: 60px; max-width: 60px;" alt="">
                                                            @else
                                                                <img id="image-preview" src="" alt="Appeal image" class="d-none"
                                                                    style="max-height: 60px; max-width: 60px;" />
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="donation-link">Donation Link</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="donation-link" name="donation_link" value="{{ $appeal->donation_link }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap ">
                                                        <div class="form-control-select">
                                                            <select class="form-control form-select" id="status" name="status" required>
                                                                <option @if ($appeal->status == 1) selected @endif value="1">Active</option>
                                                                <option @if ($appeal->status == 0) selected @endif value="0">In-active</option>
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

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="meta-description">Meta Description</label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" id="meta-description" name="meta_description">{{ $appeal->meta_description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="meta-keyword">Meta Keyword</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="meta-keyword" name="meta_keyword" value="{{ $appeal->meta_keyword }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="meta-keyword">Meta title</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="meta-title" name="meta_title" value="{{ $appeal->meta_title }}">
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
    <link rel="stylesheet" href="{{ asset('admin-dashboard/css/editors/quill.css?ver=2.2.0') }}">
    <script src="{{ asset('admin-dashboard/js/libs/editors/quill.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('admin-dashboard/js/editors.js?ver=2.2.0') }}"></script>
    <script>
        var quill = new Quill('#editor-container', {
            modules: {
                toolbar: [
                    ['bold', 'italic'],
                    ['link', 'blockquote', 'code-block', 'image'],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }]
                ]
            },
            placeholder: 'Compose an epic...',
            theme: 'snow'
        });

        var form = document.querySelector('form');
        $(".appeal-form").submit(function(e) {

            // Populate hidden form on submit
            var desc = document.querySelector('input[name=description]');
            desc.value = quill.root.innerHTML;
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
@endpush
