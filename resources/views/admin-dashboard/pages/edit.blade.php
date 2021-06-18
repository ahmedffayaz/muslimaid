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
                                {{-- <h4 class="title nk-block-title">Create Page</h4> --}}
                                <div class="nk-block-des">
                                    {{-- <p>You can make style out your....</p> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                    {{-- <h5 class="card-title">Page</h5> --}}
                                </div>
                                <form action="{{route('admin.pages.update',$page)}}" class="" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Title</label>
                                                <div class="form-control-wrap">
                                                    <input id="page-title" type="text" class="form-control " name="title" placeholder="Title" value="{{ $page->title }}" required> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="default-06">Status</label>
                                                <div class="form-control-wrap ">
                                                    <div class="">
                                                        <select class="form-control form-select" name="status" required>
                                                            <option @if($page->status == 1) selected @endif value="1">Active</option>
                                                            <option @if($page->status == 0) selected @endif value="0">In-active</option>
                                                          
                                                        </select>
                                                    </div>
                                                </div>                                                
                                            </div>                                           
                                        </div>
                                        <div class="col-lg-12">
                                            <fieldset class="uk-fieldset">
                                                <div class="uk-margin">
                                                    <textarea name="content" id="content" hidden>{{ $page->lb_raw_content }}</textarea>
                                                </div>
                                            </fieldset>
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