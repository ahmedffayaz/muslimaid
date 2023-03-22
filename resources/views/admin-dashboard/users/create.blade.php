@extends('layouts.admin-dashboard.app')

@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="components-preview wide-md mx-auto">
                        <div class="nk-block nk-block-lg">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">

                                    @include('flash::message')

                                    <h4 class="title nk-block-title">Add User</h4>
                                    <div class="nk-block-des"></div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-inner">
                                    <div class="card-head">
                                        <h5 class="card-title">User Info</h5>
                                    </div>
                                    <form action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="gy-3 user-form is-alter" 
                                        method="POST">
                                        @csrf
                                        <div class="row g-4">
                                            <div class="col-lg-12 text-center">
                                                <label class="form-label" for="pay-amount-1">Avatar</label>
                                                <div class="profile-card__avatar text-center">
                                                    <img src="{{ asset('admin-dashboard/images/avatar.png') }}" id="image_avatar" width="100">
                                                    <input type="file" class="form-control mt-3 w-50 mx-auto" name="avatar" accept="image/*"
                                                        value="{{ old('avatar') ?? null }}" onchange="readURL(this);">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="full-name-1">First Name <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="full-name-1" name="firstname" value="{{ old('firstname') ?? null }}"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="lastname">Last Name <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname') ?? null }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') ?? null }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="phone-no-1">Phone </label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="phone-no-1" name="phone" value="{{ old('phone') ?? null }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="pay-amount-1">Address </label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="pay-amount-1" name="address" value="{{ old('address') ?? null }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <input name="intro" type="hidden">
                                                    <label class="form-label" for="phone-no-1">Introduction</label>
                                                    <!-- Create the editor container -->
                                                    <div id="editor-container">{!! old('intro') ?? null !!}</div>
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
                        </div>
                    </div>
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

        $(".user-form").submit(function(e) {
            // Populate hidden form on submit
            var desc = document.querySelector('input[name=intro]');
            desc.value = quill.root.innerHTML;
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image_avatar')
                        .attr('src', e.target.result)
                        .css('border-radius', '50%').css('height', 100);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
        $('.user-form').validate({
            errorClass: 'invalid-feedback d-block',
            rules: {
                firstname: {
                    required: true
                },
                lastname: {
                    required: true
                },
                email: {
                    required: true
                },
            }
        });
    </script>
@endpush
