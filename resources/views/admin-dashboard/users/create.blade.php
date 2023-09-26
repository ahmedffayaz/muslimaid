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
                                    <form action="{{ route(getAdminPrefix() . '.users.store') }}" enctype="multipart/form-data" class="gy-3 user-form is-alter" method="POST">
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
                                                        <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="{{ old('phone') ?? null }}">
                                                        <input type="hidden" id="phone-no-1" name="phone">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="date_of_birth">Date of Birth </label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                                            value="{{ old('date_of_birth') ?? null }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="pay-amount-1">Address </label>
                                                    <div class="form-control-wrap">
                                                        <textarea type="text" class="form-control" id="pay-amount-1" name="address">{{ old('address') ?? null }}</textarea>
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

    <script src="{{ asset('admin-dashboard/telephone-dropdown/js/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('admin-dashboard/telephone-dropdown/js/intlTelInput-jquery.min.js') }}"></script>

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

        $('.user-form').validate({
            errorClass: 'invalid-feedback d-block',
            rules: {
                firstname: {
                    required: true,
                    regex: true
                },
                lastname: {
                    required: true,
                    regex: true
                },
                email: {
                    required: true
                },
                phone: {
                    validPhone: true
                },
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            var input = document.querySelector("#phoneNumber");
            const iti = window.intlTelInput(input,({
                nationalMode:true,
                preferredCountries: [],
                utilsScript: "{{ asset('admin-dashboard/telephone-dropdown/js/utils.js') }}",
                separateDialCode:true,
            }));
            $('.iti').css('width', '100%');
            const handleChange = () => {
                let phoneNumber;
                if(input.value) {
                    if(iti.isValidNumber()){
                        phoneNumber = iti.getNumber();
                        $("#phone-no-1").val(phoneNumber);
                    }
                }
            }
            input.addEventListener('change', handleChange);
            input.addEventListener('keyup', handleChange);
        });
    </script>
@endpush
