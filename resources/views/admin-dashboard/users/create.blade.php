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
                                    <form action="{{ route('admin.users.store') }}" class="gy-3 form-validate user-form is-alter" method="POST">
                                        @csrf
                                        <div class="row g-4">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="full-name-1">First Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="full-name-1" name="firstname" value="{{ old('firstname') ?? null }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="full-name-1">Last Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="full-name-1" name="lastname" value="{{ old('lastname') ?? null }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="full-name-1">Email</label>
                                                    <div class="form-control-wrap">
                                                        <input type="email" class="form-control" id="full-name-1" name="email" value="{{ old('email') ?? null }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="phone-no-1">Phone</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="phone-no-1" name="phone" value="{{ old('phone') ?? null }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="pay-amount-1">Address</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="pay-amount-1" name="address" value="{{ old('address') ?? null }}" required>
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
    </script>
@endpush
