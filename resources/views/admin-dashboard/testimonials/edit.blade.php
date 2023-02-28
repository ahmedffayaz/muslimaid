@extends('layouts.admin-dashboard.app')

@push('styles')
    <style>
        .btn-choose{
            padding:7px 10px;
            background-color: #854fff;
            border-color: #854fff;
            position:relative;
            color:#fff;
            border-radius:8px;
            text-align:center;
            float:left;
            cursor:pointer
        }
        .hide_file {
            position: absolute;
            z-index: 1000;
            opacity: 0;
            cursor: pointer;
            right: 0;
            top: 0;
            height: 100%;
            font-size: 24px;
            width: 100%;

        }
    </style>
@endpush

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
                                <form action="{{route('admin.testimonials.update',$testimonial)}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">User  <span class="text-danger">*</span></label>
                                                <select class=" select-user" id="user" name="user"  required>
                                                    @foreach($users as $user)
                                                    <option {{ $user->id == $testimonial->user_id ? 'selected' : '' }} value="{{$user->id}}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                                    @endforeach
                                                </select>
                                             </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">User Name <span class="text-danger">*</span></label>
                                                <div class="form-control-wrap">
                                                    <input id="user-name" type="text" class="form-control " name="name" placeholder="User Name" value="{{ $testimonial->name }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Title <span class="text-danger">*</span></label>
                                                <div class="form-control-wrap">
                                                    <input id="blog-title" type="text" class="form-control " name="title" placeholder="Title" value="{{ $testimonial->title }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Company Name <span class="text-danger">*</span></label>
                                                <div class="form-control-wrap">
                                                    <input id="blog-title" type="text" class="form-control " name="company_name" placeholder="Company Name" value="{{ $testimonial->company }}" required>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-12">
                                            <div class="form-group">
                                            <label class="form-label" for="reviewer">Description <span class="text-danger">*</span></label>
                                            <textarea  class="form-control " name="description" placeholder="Description" value="" required>{{ $testimonial->description }}</textarea>
                                        </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Job Position <span class="text-danger">*</span></label>
                                                <div class="form-control-wrap">
                                                    <input id="blog-title" type="text" class="form-control " name="position" placeholder="Job Position" value="{{ $testimonial->position }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Order No <span class="text-danger">*</span></label>
                                                <div class="form-control-wrap">
                                                    <input id="blog-title" type="number" class="form-control " name="order_no" placeholder="Order No" value="{{ $testimonial->order_no }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Status <span class="text-danger">*</span></label>
                                                <div class="form-control-wrap">
                                                        <div class="form-control-wrap ">
                                                            <div class="form-control-select">
                                                                <select class="form-control" id="default-06" name="status" required>
                                                                    <option @if($testimonial->status == 'active') selected @endif value="active">Active</option>
                                                                    <option @if($testimonial->status == 'in-active') selected @endif value="in-active">In-Active</option>

                                                                </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <div class=" logo">
                                                <label class="form-label" for="reviewer">User Image <span class="text-danger">*</span></label>
                                                <div class="btn-choose form-control">
                                                    Choose Image
                                                   <input preview="#logo" type="file" name="user_image" class="hide_file form-control" onchange="readURL(this);">
                                                  </div>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <div class=" logo">
                                                  <img id="logo" src="" alt="store logo" class="d-none" style="max-width:80px;max-height:120px"/>
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
    $(document).ready(function () {
        $('.select-user').select2();
    });

    $(document).ready(function(){
            $(document).on('change', '.select-user', function() {

            var id = $(this).val();
            event.preventDefault();
                        $.ajax({
                        method:"GET",
                        url: "get/user/"+id+"",
                        success:function(data){
                        console.log(data['data']['first_name']);
                        $('#user-name').val(data['data']['first_name']+''+data['data']['last_name']);
                    }
            });
        });
    });


    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var preview = $(input).attr('preview');
                $('#logo').removeClass('d-none');
                //$('#logo-input').addClass('d-none');

                $(preview)
                    .attr('src', e.target.result)
                    .css('max-width',80).css('max-height',120);
            };

            reader.readAsDataURL(input.files[0]);
        }
        }
</script>

@endpush
