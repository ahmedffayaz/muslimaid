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
                                    <h4 class="title nk-block-title">Edit Testimonial</h4>
                                    <div class="nk-block-des">
                                        {{-- <p>You can make style out your....</p> --}}
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
                                        <h5 class="card-title">Testimonial Info</h5>
                                    </div>
                                    <form action="{{ route(getAdminPrefix() . '.testimonials.update', $testimonial) }}" class="form-validate is-alter" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-4">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="user">User <span class="text-danger">*</span></label>
                                                    <select class="select-user" id="user" name="user" required>
                                                        @foreach ($users as $user)
                                                            <option {{ $user->id == $testimonial->user_id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->first_name }}
                                                                {{ $user->last_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="name">User Name <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input id="user-name" type="text" class="form-control" name="name" placeholder="User Name"
                                                            value="{{ $testimonial->name }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input id="title" type="text" class="form-control" name="title" placeholder="Title"
                                                            value="{{ $testimonial->title }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="company_name">Company Name <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input id="company_name" type="text" class="form-control " name="company_name" placeholder="Company Name"
                                                            value="{{ $testimonial->company }}" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="description">Description <span class="text-danger">*</span></label>
                                                    <textarea class="form-control description " name="description" placeholder="Description" value="" required>{{ $testimonial->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="position">Job Position <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input id="position" type="text" class="form-control " name="position" placeholder="Job Position"
                                                            value="{{ $testimonial->position }}" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="order_no">Order No <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input id="order_no" type="number" class="form-control " name="order_no" min="0.1" step="0.1" placeholder="Order No"
                                                            value="{{ $testimonial->order_no }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <div class="form-control-wrap ">
                                                            <div class="form-control-select">
                                                                <select class="form-control" id="status" name="status" required>
                                                                    <option @if ($testimonial->status == 'active') selected @endif value="active">Active</option>
                                                                    <option @if ($testimonial->status == 'in-active') selected @endif value="in-active">In-Active</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="image">User Image</label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="image" id="image" onchange="readURL(this);">
                                                            <label class="custom-file-label" for="image">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <div class="preview-wrapper">
                                                            @if ($testimonial->image)
                                                                <img id="logo-preview"src="{{ asset($testimonial->image) }}"
                                                                    style="max-height: 60px; max-width: 60px;" alt="">
                                                            @else
                                                                <img id="logo-preview" src="" alt="store logo" class="d-none"
                                                                    style="max-height: 60px; max-width: 60px;" />
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <button onclock="remobe_bug()" class="btn btn-primary add-testimonial" type="submit">Save</button>
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
        $(document).ready(function() {
            $('.select-user').select2();
        });

        $(document).ready(function() {
            $(document).on('change', '.select-user', function() {
                var id = $(this).val();
                event.preventDefault();
                $.ajax({
                    method: "GET",
                    url: "get/user/" + id + "",
                    success: function(data) {
                        console.log(data['data']['first_name']);
                        $('#user-name').val(data['data']['first_name'] + '' + data['data']['last_name']);
                    }
                });
            });
        });
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
                },
                name: {
                    required: true
                },
                company_name: {
                    required: true
                },
                position: {
                    required: true
                },
                order_no: {
                    required: true
                },
            }
        });
    </script>
@endpush
