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
                                    <h4 class="title nk-block-title">Edit Charity</h4>
                                    <div class="nk-block-des">
                                    </div>
                                </div>
                            </div>

                            @include('flash::message')
                            <div class="card">
                                <div class="card-inner">
                                    <div class="card-head">
                                        <h5 class="card-title">Charity Info</h5>
                                    </div>
                                    <form action="{{ route('admin.charities.update', $charity) }}" class="gy-3 form-validate is-alter charity-form" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        <div class="row g-4">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="title" name="title" value="{{ $charity->title }}" required>
                                                        @error('title')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="charity_types_id">Charity Type <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap ">
                                                        <div class="form-control-select">
                                                            <select class="form-control form-select" id="charity_types_id" name="charity_types_id" required>
                                                                <option value="0">None</option>
                                                                @foreach ($CharityType as $types)
                                                                    <option @if ($charity->charity_types_id == $types->id) selected @endif value="{{ $types->id }}" style="font-weight:bold">
                                                                        {{ $types->title }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('charity_types_id')
                                                                <span class="invalid-feedback d-block" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="card">

                                                    <label class="form-label" for="description">Description</label>
                                                    <div id="editor-container">{!! $charity->description !!}</div>
                                                    <input name="description" type="hidden">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="logo_type">Logo <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap ">
                                                        <div class="form-control-select">
                                                            <select class="form-control" name="logo_type" id='logo_type' required>
                                                                <option value="upload" @if ($charity->logo_type == 'upload') selected @endif>Upload</option>
                                                                <option value="link" @if ($charity->logo_type == 'link') selected @endif>Link</option>
                                                            </select>
                                                            @error('logo_type')
                                                                <span class="invalid-feedback d-block" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 logo_link">
                                                <div class="form-group">
                                                    <label class="form-label" for="logo_link">Logo Link <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input type="link" class="form-control" id="logo_link" name="logo_link" value="{{ $charity->logo_link }}" required onchange="readLinkURL(this);" >
                                                        @error('logo_link')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 ">
                                                    <div class="form-group">
                                                     
                                                        <div class="preview-wrapper">
                                                            @if($charity->logo_link )
                                                            <img id="logo_link-preview" src="{{ asset($charity->logo_link) }}" alt="store logo" style="max-height: 60px; max-width: 60px;" />   
                                                            @else
                                                            <img id="logo_link-preview" src="" alt="store logo" class="d-none" style="max-height: 60px; max-width: 60px;" /> 
                                                            @endif
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 logo_upload">
                                                <div class="form-group">
                                                    <label class="form-label" for="logo_upload">Logo Upload <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="logo_upload" id="logo_upload" value="{{ old('logo_upload') }}"
                                                                onchange="readBannerURL(this);" >
                                                            <label class="custom-file-label" for="logo_upload">Choose file</label>
                                                            @error('logo_upload')
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
                                                            @if ($charity->logo_upload)
                                                                <img id="logo-preview"src="{{ asset($charity->logo_upload) }}"
                                                                    style="max-height: 60px; max-width: 60px;" alt="">
                                                            @else
                                                                <img id="logo-preview" src="" alt="store logo" class="d-none"
                                                                    style="max-height: 60px; max-width: 60px;" />
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 ">
                                                <div class="form-group">
                                                    <label class="form-label" for="banner_type">Banner <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap ">
                                                        <div class="form-control-select">
                                                            <select class="form-control" name="banner_type" id='banner_type' required>
                                                                <option value="upload" @if ($charity->banner_type == 'upload') selected @endif>Upload</option>
                                                                <option value="link" @if ($charity->banner_type == 'link') selected @endif>Link</option>

                                                            </select>
                                                            @error('banner_type')
                                                                <span class="invalid-feedback d-block" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 banner_link">
                                                <div class="form-group">
                                                    <label class="form-label" for="banner_link">Banner Link <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input type="link" class="form-control" id="banner_link" name="banner_link" value="{{ $charity->banner_link }}"
                                                            required onchange="readLinkURL(this);">
                                                        @error('banner_link')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 ">
                                                    <div class="form-group">
                                                        <div class="preview-wrapper">
                                                            @if($charity->banner_link )
                                                                <img id="banner_link-preview" src="{{ asset($charity->banner_link) }}" alt="logo" style="max-height: 60px; max-width: 60px;" />   
                                                            @else
                                                                <img id="banner_link-preview" src="" alt="logo" class="d-none" style="max-height: 60px; max-width: 60px;" /> 
                                                            @endif
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 banner_upload">
                                                <div class="form-group">
                                                    <label class="form-label" for="banner_upload">Banner Upload</label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="banner_upload" id="banner_upload"
                                                                value="{{ $charity->logo_upload }}" onchange="readBannerURL(this);" >
                                                            <label class="custom-file-label" for="banner_upload">Choose file</label>
                                                            @error('banner_upload')
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
                                                            @if ($charity->banner_upload)
                                                                <img id="banner-preview"src="{{ asset($charity->banner_upload) }}"
                                                                    style="max-height: 60px; max-width: 60px;" alt="">
                                                            @else
                                                                <img id="banner-preview" src="" alt="logo" class="d-none"
                                                                    style="max-height: 60px; max-width: 60px;" />
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="country">Country <span class="text-danger">*</span></label>
                                                    <div class="form-control-select">
                                                        <select class="form-control form-select" id="country" name="country" value="{{ old('country') }}" required>
                                                            @foreach($countries as $country)
                                                                <option value="{{ $country->id }}" {{ $charity->country == $country->id ? 'slected' : '' }}>{{ $country->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('country')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap ">
                                                        <div class="form-control-select">
                                                            <select class="form-control form-select" id="status" name="status" required>
                                                                <option @if ($charity->status == 1) selected @endif value="1">Active</option>
                                                                <option @if ($charity->status == 0) selected @endif value="0">In-active</option>
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
        $(".charity-form").submit(function(e) {

            // Populate hidden form on submit
            var desc = document.querySelector('input[name=description]');
            desc.value = quill.root.innerHTML;
        });
    </script>
    <script>
        $(document).ready(function() {
            if ($('#logo_type').val() == 'upload') {
                $('.logo_upload').show();
                $('.logo_link').hide();
                $('#logo_link').removeAttr('required').val('');

            } else if ($('#logo_type').val() == 'link') {

                $('.logo_link').show();
                $('#logo_link').attr('required', 'required');
                $('.logo_upload').hide();

            }

        });
        $(document.body).on("change", "#logo_type", function() {
            if (this.value == 'upload') {
                $('.logo_upload').show();
                $('.logo_link').hide();
                $('#logo_link').removeAttr('required').val('');

            } else if (this.value == 'link') {

                $('.logo_link').show();
                $('#logo_link').attr('required', 'required');
                $('.logo_upload').hide();

            }

        });
    </script>
    <script>
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

        function initializeSelect2() {
            $('.form-select').select2({
                placeholder: function() {
                    $(this).data('placeholder');
                }
            });
        }
        $('.form-validate').validate({
            rules: {
                logo_link: {
                    url: true,
                },
                banner_link: {
                    url: true
                },
            }
        });
    </script>
@endpush
