@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview mx-auto">
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                {{-- <h4 class="title nk-block-title">Create Blog</h4> --}}
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
                                    {{-- <h5 class="card-title">Blog</h5> --}}
                                </div>
                                <form action="{{route('admin.testimonials.store')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Title</label>
                                                <div class="form-control-wrap">
                                                    <input id="blog-title" type="text" class="form-control " name="title" placeholder="Title" value="" required> 
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">User Image</label>
                                                <input id="logo-input"  name="user_image" class="form-control" type="file" onchange="readURL(this);" />
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                            <label class="form-label" for="reviewer">Description</label>
                                            <textarea  class="form-control " name="description" placeholder="Description" value="" required></textarea>
                                        </div>
                                        </div>
                                        <hr>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">User Name</label>
                                                <div class="form-control-wrap">
                                                    <input id="user-name" type="text" class="form-control " name="name" placeholder="User Name" value="" required> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Job Position</label>
                                                <div class="form-control-wrap">
                                                    <input id="blog-title" type="text" class="form-control " name="position" placeholder="Job Position" value="" required> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Company Name</label>
                                                <div class="form-control-wrap">
                                                    <input id="blog-title" type="text" class="form-control " name="company_name" placeholder="Company Name" value="" required> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Order No</label>
                                                <div class="form-control-wrap">
                                                    <input id="blog-title" type="number" class="form-control " name="order_no" placeholder="Order No" value="" required> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Status</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-control" id="default-06" name="status" required>
                                                            <option value="active" selected>Active</option>
                                                            <option value="in-active">In-Active</option>
                                                        </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Meta Title</label>
                                                <div class="form-control-wrap">
                                                    <input id="blog-title" type="text" class="form-control " name="meta_title" placeholder="Meta Title" value="" required> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Meta Description</label>
                                                <div class="form-control-wrap">
                                                    <textarea  class="form-control " name="meta_description" placeholder="Meta Description" value="" required></textarea>
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


@endpush