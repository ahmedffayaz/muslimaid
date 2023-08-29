@extends('layouts.admin-dashboard.app')

@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="components-preview wide-md mx-auto">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="nk-block-head nk-block-head-lg pb-2">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title fw-normal">Profile</h3>
                                    <div class="nk-block-des">

                                    </div>
                                </div>
                                <div class="nk-block-head-content">

                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                        <div class="toggle-expand-content" data-content="pageMenu">
                                            <ul class="nk-block-tools g-3">
                                                {{-- <li class="nk-block-tools-opt"><a href="{{route(getAdminPrefix() . '.stores.edit',$store)}}" class="btn btn-primary btn-sm"><em class="icon ni ni-edit"></em><span>Edit Store</span></a></li> --}}

                                                {{-- <li><a href="{{route(getAdminPrefix() . '.stores.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li> --}}

                                            </ul>
                                        </div>
                                    </div><!-- .toggle-wrap -->
                                </div><!-- .nk-block-head-content -->
                            </div>
                        </div>

                        @include('flash::message')

                        <div class="nk-block nk-block-lg">

                            <div class="card card-preview">
                                <div class="card-inner">

                                    <ul class="nav nav-tabs mt-n3">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#tabItem4"><em class="icon ni ni-user-fill"></em><span>Profile</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#tabItem9"><em class="icon ni ni-lock-alt-fill"></em><span>Change Password</span></a>
                                        </li>

                                    </ul>
                                    <div class="tab-content">

                                        <div class="tab-pane active" id="tabItem4">
                                            <div class="nk-block">
                                                <div class="nk-block-head">
                                                    <h5 class="title">Profile Settings</h5>
                                                    {{-- <p>Basic info, like your name and address, that you use on Nio Platform.</p> --}}
                                                </div><!-- .nk-block-head -->
                                                <form action="{{ route(getAdminPrefix() . '.profile.update', $profile) }}" class="gy-3 form-settings form-validate is-alter" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row g-3 align-center">
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="dashboard_title">Avatar</label>
                                                            </div>

                                                        </div>
                                                        <div class="col-lg-8">
                                                            <div class="form-group">
                                                                <img id="image_avatar"
                                                                    src="@if ($profile->avatar != 'default.png' && Storage::exists('public/users/images/avatar/' . $profile->avatar)) {{ asset('storage/users/images/avatar/' . $profile->avatar) }}@else{{ asset('admin-dashboard/images/avatar.png') }} @endif"
                                                                    alt="store logo" class="" width="100" style="border-radius: 50%; height: 100px" />
                                                                <input type="file" class="form-control mt-3" name="avatar" accept="image/*" value="{{ old('avatar') ?? null }}"
                                                                    onchange="readURL(this);">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3 align-center">
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Name</label>

                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" name="first_name" value="{{ $profile->first_name }}"
                                                                        placeholder="First name" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" name="last_name" value="{{ $profile->last_name }}"
                                                                        placeholder="Last name" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 align-center">
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="email">Email</label>
                                                                {{-- <span class="form-note">Copyright information of your website.</span> --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <div class="form-group">
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="email" disabled name="email" value="{{ $profile->email }}"
                                                                        placeholder="Email" required>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="row g-3 align-center">
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="date_of_birth">Date of Birth</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <div class="form-group">
                                                                <div class="form-control-wrap">
                                                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                                                        value="{{ $profile->date_of_birth }}" placeholder="Date of Birth" required>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="row g-3 align-center">
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="phone">Phone Number</label>
                                                                {{-- <span class="form-note">Copyright information of your website.</span> --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <div class="form-group">
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $profile->phone }}"
                                                                        placeholder="Phone Number">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row g-3 align-center">
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="address">Address</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <div class="form-group">
                                                                <div class="form-control-wrap">
                                                                    <textarea type="text" class="form-control" id="address" name="address">{{ $profile->address }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row g-3">
                                                        <div class="col-lg-8 offset-lg-4">
                                                            <div class="form-group mt-2">
                                                                <button type="submit" class="btn btn-lg btn-primary">Update</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                        <div class="tab-pane" id="tabItem9">
                                            <h5 class="title mb-4">Change Password</h5>
                                            <form action="{{ route(getAdminPrefix() . '.profile.save_password', $profile) }}" class="gy-3 form-validate is-alter" id='password_form'
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="row g-4">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="full-name-1">New Password</label>
                                                            <div class="form-control-wrap">
                                                                <input type="password" class="form-control" id="full-name-1" value="" name="password" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="full-name-1">Confirm New Password</label>
                                                            <div class="form-control-wrap">
                                                                <input type="password" class="form-control" id="full-name-1" value="" name="password_confirmation"
                                                                    required>
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
                                </div>
                            </div><!-- .card-preview -->

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .nk-tb-list {
            table-layout: fixed;
        }

        .nk-files-view-grid .nk-file-icon-type {
            width: 150px;
            padding: 2rem 0 .5rem 0;
        }

        @media (min-width: 1200px) {
            .nk-files-view-grid .nk-file {
                width: calc(25% - 16px) !important;
            }
        }

        .nk-files-view-grid .nk-file {
            background-color: #f5f6fa7a !important;
        }

        .stores .select2 {
            width: 300px !important;
        }

        ul.categories {
            list-style: none;
            margin: 5px 5x;
        }

        ul.categories li {
            margin: 10px 0;
        }
    </style>

@endsection
@push('scripts')
<script src="{{ asset('storage/__asset/telephone-dropdown/js/intlTelInput.min.js') }}"></script>
<script src="{{ asset('storage/__asset/telephone-dropdown/js/intlTelInput-jquery.min.js') }}"></script>
    <!-- Update Store-->
    <script>
        $(document).ready(function() {
            $(document).on('submit', '.form-settingss', function(event) {

                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {

                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            NioApp.Toast(data.message, data.response);
                        })(NioApp, jQuery);

                    },
                    error: function(data) {

                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            NioApp.Toast(data.message, data.response);
                        })(NioApp, jQuery);

                    }
                });

            });
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
    </script>

    <script>
        jQuery.validator.addMethod("regex", function(value, element) {
            return this.optional(element) || /^[A-Za-z ]+$/i.test(value);
        }, "Only alphabetic name is allow");

        jQuery.validator.addMethod("validPhone", function(value, element) {
            var regex = /^\+\d{12}$/;
            if(regex.test(value) == false){
                $('.iti__flag-container').css('padding-bottom', '39px');
            } else {
                $('.iti__flag-container').css('padding-bottom', '15px');
            }
            return regex.test(value);
        }, "Enter a valid phone number");

        $('.form-validate').validate({
            rules: {
                first_name: {
                    required: true,
                    regex: true
                },
                last_name: {
                    required: true,
                    regex: true
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    validPhone: true
                },
            }
        });
        </script>
        <script>
            $(document).ready(function () {
                var input = document.querySelector("#phone");
                window.intlTelInput(input,({
                    autoInsertDialCode:true,
                    nationalMode:false,
                    preferredCountries: [],
                }));
                $('.iti').css('width', '100%');
            });
    </script>
@endpush
