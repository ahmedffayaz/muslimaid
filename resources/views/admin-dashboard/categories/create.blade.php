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
                                <h4 class="title nk-block-title">Add Category</h4>
                                <div class="nk-block-des">
                                    {{-- <p>You can make style out your....</p> --}}
                                </div>
                            </div>
                        </div>
                        @include('flash::message')
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                    <h5 class="card-title">Category Info</h5>
                                </div>
                                <form action="{{route('admin.categories.store')}}" class="gy-3 form-validate is-alter" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="full-name-1">Category Name</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="full-name-1" name="name" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="default-06">Parent Category</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-control" id="default-06" name="parent_id" required>

                                                        <option value="0">None</option>
                                                        @foreach ($categories as $parent)
                                                        <option value="{{$parent->id}}" style="font-weight:bold">{{$parent->name}}</option>
                                                        @if(count($parent->childs))
                                                                @include('admin-dashboard.categories.child_input',['childs' => $parent->childs, 'isEdit'=>0,'dashes'=>'~'])
                                                            @endif

                                                        @endforeach


                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <input name="description" type="hidden">
                                                <label class="form-label" for="phone-no-1">Description</label>
                                                <!-- Create the editor container -->
                                                <div  id="editor-container">

                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="logo_type">Logo</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-control" name="logo_type" id='logo_type' required>

                                                            <option value="upload">Upload</option>
                                                            <option value="link">Link</option>


                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 logo_link">
                                            <div class="form-group">
                                                <label class="form-label" for="logo_link">Logo Link</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="logo_link" name="logo_link" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 logo_upload">
                                            <div class="form-group">
                                                <label class="form-label" for="logo_upload">Logo Upload</label>
                                                <div class="form-control-wrap">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name='logo_upload' id="logo_upload">
                                                        <label class="custom-file-label" for="logo_upload">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 ">
                                            <div class="form-group">
                                                <label class="form-label" for="banner_type">Banner</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-control" name="banner_type" id='banner_type' required>
                                                            <option value="upload">Upload</option>
                                                            <option value="link">Link</option>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 banner_link">
                                            <div class="form-group">
                                                <label class="form-label" for="banner_link">Banner Link</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="banner_link" name="banner_link" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 banner_upload">
                                            <div class="form-group">
                                                <label class="form-label" for="banner_upload">Banner Upload</label>
                                                <div class="form-control-wrap">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="banner_upload" id="banner_upload">
                                                        <label class="custom-file-label" for="banner_upload">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="full-name-1">Sort</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="full-name-1" name="sort">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="default-06">Status</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-control" id="default-06" name="status" required>

                                                            <option value="1">Active</option>
                                                            <option value="0">In-active</option>


                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Title</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="title" placeholder="Title" value="" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Meta Keywords</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="meta_keyword" placeholder="Meta keyword" value="" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                            <label class="form-label" for="reviewer">Meta Description</label>
                                            <textarea  class="form-control" name="meta_description" placeholder="Meta Description" value="" ></textarea>
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
<link rel="stylesheet" href="{{ asset('admin-dashboard/css/editors/quill.css?ver=2.2.0')}}">
    <script src="{{ asset('admin-dashboard/js/libs/editors/quill.js?ver=2.2.0')}}"></script>
    <script src="{{ asset('admin-dashboard/js/editors.js?ver=2.2.0')}}"></script>
    <script>
    var quill = new Quill('#editor-container', {
        modules: {
          toolbar: [
            ['bold', 'italic'],
            ['link', 'blockquote', 'code-block', 'image'],
            [{ list: 'ordered' }, { list: 'bullet' }]
          ]
        },
        placeholder: 'Compose an epic...',
        theme: 'snow'
      });

    //   var form = document.querySelector('form');
      $(".user-form").submit(function(e) {

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

        }
        else if ($('#logo_type').val() == 'link') {

            $('.logo_link').show();
            $('#logo_link').attr('required', 'required');
            $('.logo_upload').hide();

        }

    });
    $(document.body).on("change","#logo_type",function(){
        if (this.value == 'upload') {
            $('.logo_upload').show();
            $('.logo_link').hide();
            $('#logo_link').removeAttr('required').val('');

        }
        else if (this.value == 'link') {

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

        }
        else if ($('#banner_type').val() == 'link') {

            $('.banner_link').show();
            $('#banner_link').attr('required', 'required');
            $('.banner_upload').hide();

        }

    });
    $(document.body).on("change","#banner_type",function(){

        if (this.value == 'upload') {
            $('.banner_upload').show();
            $('.banner_link').hide();
            $('#banner_link').removeAttr('required').val('');

        }
        else if (this.value == 'link') {

            $('.banner_link').show();
            $('#banner_link').attr('required', 'required');
            $('.banner_upload').hide();

        }

    });
</script>
@endpush
