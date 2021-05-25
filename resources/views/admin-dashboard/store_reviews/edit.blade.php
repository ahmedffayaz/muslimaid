@extends('layouts.admin-dashboard.app')
@section('content')
    

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                        <div class="nk-block-between align-items-baseline">

                            <div class="nk-block-head-content">
                                <h4 class="title nk-block-title">Edit Review</h4>
                                <div class="nk-block-des">
                                    {{-- <p>You can make style out your....</p> --}}
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            {{-- <li class="nk-block-tools-opt"><a href="{{route('admin.stores.show',$review->store)}}" class="btn btn-primary btn-sm"><em class="icon ni ni-arrow-left"></em><span>Go to store</span></a></li> --}}
                                        
                                            {{-- <li><a href="{{route('admin.stores.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li> --}}
                                          
                                        </ul>
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div>
                        </div>
                        @include('flash::message')

                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                    <h5 class="card-title">Review</h5>
                                </div>
                                <form action="{{route('admin.reviews.update',$review)}}" class="gy-3 form-validate is-alter review_form" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="edit_from_store" value="1">
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="reviewer">Reviewer Name</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="reviewer" name="reviewer" value="{{$review->reviewer}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="default-06">Store</label>
                                                <div class="form-control-wrap ">
                                                    
                                                        <select class="form-select form-control" data-search="on" id="default-06" name="store_id" required>
                                                            @foreach ($stores as $store)
                                                            <option @if($store->id == $review->store_id) selected @endif value="{{$store->id}}">{{$store->name}}</option>
                                                            @endforeach
                                                        </select>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <input name="review" type="hidden">
                                                <label class="form-label" for="phone-no-1">Review</label>
                                                <!-- Create the editor container -->
                                                <div  id="editor-container">
                                                  {!! $review->review!!}
                                                </div>
                                               
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="default-06">Status</label>
                                                <div class="form-control-wrap ">
                                                    
                                                        <select class="form-select form-control" data-search="on" id="default-06" name="status" required>
                                                            
                                                            <option @if($review->status == 'active') selected @endif value="active">Active</option>
                                                            <option @if($review->status == 'pending') selected @endif value="pending">Pending</option>
                                                           
                                                        </select>
                                                   
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
      $(".review_form").submit(function(e) {
          
        // Populate hidden form on submit
        var desc = document.querySelector('input[name=review]');
        desc.value = quill.root.innerHTML;
       
        
      });</script>
@endpush