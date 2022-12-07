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
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                    {{-- <h5 class="card-title">Blog</h5> --}}
                                </div>
                                <form action="{{route('admin.blogs.update',$blog)}}" class="" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-4">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Title</label>
                                                <div class="form-control-wrap">
                                                    <input id="blog-title" type="text" class="form-control " name="title" placeholder="Title" value="{{ $blog->title }}" required> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <fieldset class="uk-fieldset">
                                                <div class="uk-margin">
                                                    <textarea name="content" id="content" hidden>{{ $blog->lb_raw_content }}</textarea>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Meta Keywords</label>
                                                <div class="form-control-wrap">
                                                    <input id="blog-title" type="text" class="form-control " name="meta_keyword" placeholder="Meta keyword" value="{{ $blog->meta_keyword }}" > 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                            <label class="form-label" for="reviewer">Meta Description</label>
                                            <textarea  class="form-control " name="meta_description" placeholder="Meta Description" value="{{ $blog->meta_description }}" ></textarea>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <button class="btn btn-primary" type="submit">Save</button>
                                                
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
    window.addEventListener('DOMContentLoaded', () => {
        Laraberg.init('content', { height: '600px', laravelFilemanager: true, sidebar: true })
    })
</script>  
@endpush