@extends('layouts.admin-dashboard.app')
<style>
    .nk-tb-list{
        table-layout: fixed;
    }
    .nk-files-view-grid .nk-file-icon-type {
    width: 150px;
    padding: 2rem 0 .5rem 0;
}
@media (min-width: 1200px){
    .nk-files-view-grid .nk-file {
    width: calc(25% - 16px)!important;
}
}
.nk-files-view-grid .nk-file {
    background-color:#f5f6fa7a!important;
}
.stores .select2{
    width: 300px!important;
}
ul.categories { 
  list-style: none;
  margin: 5px 5x;
}
ul.categories li {
  margin: 10px 0;
}
</style>

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block-head nk-block-head-lg pb-2">
                        <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title fw-normal"><a href="{{route('store.show',$store->slug)}}" target="_blank">{{$store->name}}</a> <span class="badge badge-dim badge-pill badge-outline-primary">{{$store->network->name}}</span></h3>
                            <div class="nk-block-des">
                              
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <div class="form-group stores">
                                <label class="form-label" for="default-06"></label>
                                <div class="form-control-wrap ">
                                    <div class="">
                                        <select class="form-control form-select" data-search="on" name="open_store" id="store_select" required>
                                            @foreach ($stores as $st)
                                            <option @if($st->id == $store->id) selected @endif value="{{route('admin.stores.show',$st->id)}}">{{$st->id}} {{$st->name}}</option>
                                                
                                            @endforeach
                                         
                                                
                                           
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        {{-- <li class="nk-block-tools-opt"><a href="{{route('admin.stores.edit',$store)}}" class="btn btn-primary btn-sm"><em class="icon ni ni-edit"></em><span>Edit Store</span></a></li> --}}
                                    
                                        {{-- <li><a href="{{route('admin.stores.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li> --}}
                                      
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
                                        <a class="nav-link active" data-toggle="tab" href="#tabItem4"><em class="icon ni ni-file-text"></em><span>Details</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem5"><em class="icon ni ni-file-text"></em><span>Categories</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem6"><em class="icon ni ni-link"></em><span>Cashbacks</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem7"><em class="icon ni ni-money"></em><span>Vouchers</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem8"><em class="icon ni ni-notice"></em><span>Reviews</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem9"><em class="icon ni ni-img-fill"></em><span>Images</span></a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                   
                                    <div class="tab-pane active" id="tabItem4">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                                <h5 class="title">Store Information</h5>
                                                {{-- <p>Basic info, like your name and address, that you use on Nio Platform.</p> --}}
                                            </div><!-- .nk-block-head -->
                                            <form action="{{route('admin.stores.update', $store)}}" id="store_form" class="gy-3 form-validate is-alter" method="POST" enctype="multipart/form-data">
                                               @csrf
                                                @method('PUT')
                                                {{-- <meta name="csrf-token" content="{{ csrf_token() }}" /> --}}

                                               <div class="row g-4">
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="full-name-1">Store Name</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="full-name-1" value="{{$store->name}}" name="store_name" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="full-name-1">Slug</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="full-name-1" value="{{$store->slug}}" name="slug" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="default-06">Network</label>
                                                            <div class="form-control-wrap ">
                                                                <div class="">
                                                                    <select class="form-control form-select" id="" name="network_id" required>
                                                                        @foreach ($networks as $network)
                                                                        <option @if($store->network_id == $network->id) selected @endif value="{{$network->id}}">{{$network->name}}</option>
                                                                            
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
                                                               {!!$store->description!!}
                                                            </div>
                                                           
                                                        </div>
                                                    </div>
                                                    
                                                   
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="phone-no-1">Tracking url</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="phone-no-1" value="{{$store->tracking_url}}" name="tracking_url" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="pay-amount-1">Store url</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="pay-amount-1" value="{{$store->store_url}}" name="store_url" required>
                                                            </div>
                                                        </div>
                                                    </div>
            
                                                   
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="default-06">Status</label>
                                                            <div class="form-control-wrap ">
                                                                <div class="">
                                                                    <select class="form-control form-select" name="status" required>
                                                                        <option @if($store->status == 'pending review') selected @endif value="pending review">Pending Review</option>
                                                                        <option @if($store->status == 'active') selected @endif value="active">Active</option>
                                                                        <option @if($store->status == 'disabled') selected @endif value="disabled">Disabled</option>
                                                                        <option @if($store->status == 'closed') selected @endif value="closed">Closed at Network</option>
                                                                        <option @if($store->status == 'error') selected @endif value="error">Error</option>
                                                                            
                                                                       
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                       
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="text-danger mt-md-4">
                                                            @if($store->status == 'error') {{$store->status_description}} @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label" for="default-06">Tags</label>
                                                            <div class="form-control-wrap ">
                                                                <div class="">
                                                                    <select class="form-control form-select" name="tags[]" required multiple>
                                                                        <option @if($store->editor_pick) selected @endif value="editor_pick">Editor Pick</option>
                                                                        <option @if($store->feature_homepage) selected @endif value="feature_homepage">Homepage featured</option>
                                                                        <option @if($store->feature_sidebar) selected @endif value="feature_sidebar">Sidebar featured</option>
                                                                    </select>
                                                                </div>
                                                            </div> 
                                                    </div>
                                                    
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-checkbox mr-3">
                                                                <input type="checkbox" class="custom-control-input" id="override-cashback" name="override_cashback" value="1"
                                                                @if($store->override_cashback==1) checked @endif>
                                                                <label class="custom-control-label" for="override-cashback">Override Cashback</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="override-categories" name="override_categories" value="1"
                                                                @if($store->override_categories==1) checked @endif>
                                                                <label class="custom-control-label" for="override-categories">Override Categories</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                   
                                                    
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-lg btn-primary">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                      
                                    </div>
                                    <div class="tab-pane" id="tabItem5">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                                <h5 class="title">Store Categoires</h5>
                                                <form action="{{route('admin.stores.categories.update')}}" id="store_cat_form" class="gy-3 form-validate is-alter" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                     @method('POST')
                                                     <input type="hidden" name="store_id" value="{{$store->id}}">
                                                     <div class="col-12">
                                                        <ul class="categories">
                                                            @foreach ($categories as $parent)
                                                            <li class="custom-control custom-control-sm custom-checkbox d-block">
                                                                <input @if(in_array($parent->id, $store->categories->pluck('id')->toArray())) checked @endif 
                                                                type="checkbox" class="custom-control-input" name="category_id[]" id="{{$parent->name}}" value="{{$parent->id}}">
                                                                <label class="custom-control-label" for="{{$parent->name}}">{{$parent->name}}</label>
                                                                @if(count($parent->childs))
                
                                                                @include('admin-dashboard.stores.child_cat_input',['childs' =>$parent->childs])
                                                            @endif
                                                                            
                                                            @endforeach
            
                                                            </li>                                      
                                                        </ul>
                                                     </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-lg btn-primary">Update</button>
                                                            </div>
                                                        </div>
                                           
                                        </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tabItem6">
                                        <h5 class="title mb-4 d-inline-block">Cashbacks</h5>
                                        <a href="#cashback-modal" class="btn btn-primary btn-sm float-right" data-toggle="modal"><em class="icon ni ni-upload-cloud"></em> <span>Add cashback</span></a>

                                        <span id='cashbacks-data' class="mt-4"></span>

                                        
                                       
                                    </div>
                                    <div class="tab-pane" id="tabItem7">
                                        <h5 class="title mb-4">Vouchers</h5>
                                        <span id='vouchers-data'></span>
                                        
                                    </div>
                                    <div class="tab-pane" id="tabItem8">
                     

                                        <h5 class="title mb-4 d-inline">Reviews</h5>
                                        <span id='reviews-data'></span>
                                        
                                        
                                    </div>
                                    <div class="tab-pane" id="tabItem9">
                                        <span id="images-data"></span>
                                  
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
<!-- @@ File Upload Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="file-upload">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
              
                   
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Upload image for {{$store->name}}</span></div>
                        {{-- <div class="nk-file-name-sub">Project</div> --}}
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
        <form action="{{route('admin.stores.images.upload',$store)}}" class="form-validate file-upload" method="POST" enctype="multipart/form-data">

            <div class="modal-body modal-body-md">
                    @csrf

                    <div class="row gy-4">
                        <div class="col-lg-12 mx-auto">
                            <div class="form-group">
                                <div class="text-center mb-4 logo">
                                    <label for="logo-input">
                                    <img id="blah" src="{{asset('admin-dashboard/images/cloud-uploading.png')}}" alt="store logo" width="150px"/>
                                    <input id="logo-input"  name="image" class="d-none" type='file' onchange="readURL(this);" required/>
                                    <br> <br><span>Click here to select image</span>
                                    </label>
                                   
                                </div>
                               
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label" for="title">Type</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" id="title" name="title"  required>
                                            <option value="logo">Logo</option>
                                            <option value="Cover">Cover</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
           
            </div>
            <div class="modal-footer modal-footer-stretch bg-light">
                <div class="modal-footer-between">
                    <div class="g">
                        {{-- <a href="#" class="link link-primary">View All Activity</a> --}}
                    </div>
                    <div class="g">
                        <ul class="btn-toolbar g-3">
                            <li><a href="#file-share" data-dismiss="modal"class="btn btn-outline-light btn-white">Cancel</a></li>
                            <li><button type="submit" class="btn btn-primary file-dl-toast">Upload</button></li>
                        </ul>
                    </div>
                </div>
            </div><!-- .modal-footer -->
        </form>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->
<!-- @@ Voucher Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="voucher-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
              
                   
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Edit Voucher</span></div>
                        {{-- <div class="nk-file-name-sub">Project</div> --}}
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="voucher" class=" p-4">

            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->
 
<!-- @@ Edit Cashback Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="edit-cashback-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
              
                   
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Edit Cashback</span></div>
                        {{-- <div class="nk-file-name-sub">Project</div> --}}
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="edit-cashback" class=" p-4">
                

            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->
<!-- @@ Add Cashback Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="cashback-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
              
                   
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Add Cashback</span></div>
                        {{-- <div class="nk-file-name-sub">Project</div> --}}
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="cashback" class=" p-4">
                <form action="{{route('admin.stores.cashbacks.store')}}" class="gy-3 form-validate is-alter cashback_form_add" method="POST">
                    @csrf
                   
                    <input type="hidden" name="store_id" value="{{$store->id}}">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="type">Type</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" id="type" name="type"  required>
                                            <option  value="percentage">Percentage</option>
                                            <option  value="fixed">Fixed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="sale_commission">Commission</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="sale_commission" value="" name="sale_commission" required>
                                </div>
                            </div>
                        </div>                   
                        <div class="col-lg-12">
                            <div class="card">
                                <label class="form-label" for="phone-no-1" >Detail</label>
                                <textarea name="detail" class="form-control " ></textarea>
                                
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
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->
<!-- @@ Review Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="review-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
              
                   
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Review</span></div>

                        {{-- <div class="nk-file-name-sub">Project</div> --}}
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="review" class=" p-4">
             

            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->
<!-- @@ Review Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="add-review-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
              
                   
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Review</span></div>

                        {{-- <div class="nk-file-name-sub">Project</div> --}}
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="" class=" p-4">
                <form action="{{route('admin.reviews.store')}}" class="gy-3 form-validate is-alter review_form_add" method="POST">
                    @csrf
                 
                    <input type="hidden" name="store_id" value="{{$store->id}}">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="reviewer">Reviewer Name</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="reviewer" name="reviewer" value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="rating">Rating</label>
                                <div class="form-control-wrap ">
                                    
                                        <select class="form-select form-control"  id="rating" name="rating" required>
                                            
                                            <option  value="1">1</option>
                                            <option  value="2">2</option>
                                            <option  value="3">3</option>
                                            <option  value="4">4</option>
                                            <option  value="5">5</option>
                                            
                                        </select>
                                    
                                </div>
                            </div>
                        </div>
                      
                        
                        <div class="col-lg-12">
                            <div class="card">
                                <input name="review" type="hidden">
                                <label class="form-label" for="phone-no-1">Review</label>
                                <!-- Create the editor container -->
                                <div  id="reditor-container">
                                    
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="default-06">Status</label>
                                <div class="form-control-wrap ">
                                    
                                        <select class="form-select form-control" data-search="on" id="default-06" name="status" required>
                                            
                                            <option value="active">Active</option>
                                            <option value="in-active">In-active</option>
                                            
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
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->



@endsection
@push('scripts')
<script>
    $(document).ready( function() {
        fetchVouchers();
        fetchCashbacks();
        fetchReviews();
        fetchImages();
    });
</script>
<link rel="stylesheet" href="{{ asset('admin-dashboard/css/editors/quill.css?ver=2.2.0')}}">
<script src="{{ asset('admin-dashboard/js/libs/editors/quill.js?ver=2.2.0')}}"></script>
<script src="{{ asset('admin-dashboard/js/editors.js?ver=2.2.0')}}"></script>

<!-- Quill Editor-->
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
      var rquill = new Quill('#reditor-container', {
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
      
     
      
      $("#store_form").submit(function(e) {
          
        // Populate hidden form on submit
        var desc = document.querySelector('input[name=description]');
        desc.value = quill.root.innerHTML;
       
        
      });
      
</script>

<!-- Fetch Data-->
<script>
    function fetchVouchers(){
        pageurl = "{{route('admin.stores.vouchers')}}"
        var _token = $("input[name=_token]").val();
        var store = '{{$store->id}}'; 
        $.ajax({

        url:pageurl,
        method:"POST",
        data:{_token:_token,store:store},
        success:function(data)
        {
            $('#vouchers-data').html(data);
            
        }
        });
    }
    function fetchCashbacks(){
        pageurl = "{{route('admin.stores.cashbacks')}}"
        var _token = $("input[name=_token]").val();
        var store = '{{$store->id}}'; 
        $.ajax({

        url:pageurl,
        method:"POST",
        data:{_token:_token,store:store},
        success:function(data)
        {
            $('#cashbacks-data').html(data);
            
        }
        });
    }
    function fetchReviews(){
        pageurl = "{{route('admin.stores.reviews')}}"
        var _token = $("input[name=_token]").val();
        var store = '{{$store->id}}'; 
        $.ajax({

        url:pageurl,
        method:"POST",
        data:{_token:_token,store:store},
        success:function(data)
        {
            $('#reviews-data').html(data);
            
        }
        });
    }
    function fetchImages(){
        pageurl = "{{route('admin.stores.fetchimages')}}"
        var _token = $("input[name=_token]").val();
        var store = '{{$store->id}}'; 
        $.ajax({

        url:pageurl,
        method:"POST",
        data:{_token:_token,store:store},
        success:function(data)
        {
            $('#images-data').html(data);
            
        }
        });
    }
</script>
      
<!-- Image uploader popup-->
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(150);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<!--Voucher-->
<script>
    $(document).ready(function(){
     $(document).on('click', '.voucher-edit', function(event){
        event.preventDefault(); 
        
        var id = $(this).attr('voucher-id');
        pageurl = "vouchers/"+id+"/edit"
        store_editor = 1;
        var _token = $("input[name=_token]").val();
            $.ajax({

                url:pageurl,
                method:"GET",
                data:{_token:_token,store_editor:store_editor},
                success:function(data)
                {
                    $('#voucher-modal').modal('show');
                    $('#voucher').html(data);
                }
                });
               
     });
    });
    $(document).ready( function() {
        $(document).on('submit', '.voucher_form', function(event){
   
          event.preventDefault();          
          $.ajax({
                url: $(this).attr('action'),
                type: "PUT",
                data: $(this).serialize(),
                success: function(data){
                    $('#voucher-modal').modal('hide');
                    (function(NioApp, $){
                    'use strict';
                    toastr.clear();
                    NioApp.Toast('Voucher Updated Successfully.', 'success');
                    
                })(NioApp, jQuery);
                    fetchVouchers();
                }
            });      
        });
    });
</script>

<!-- Cashback-->
<script>
    $(document).ready(function(){
     $(document).on('click', '.cashback-edit', function(event){
        event.preventDefault(); 
        
        var id = $(this).attr('cashback-id');
        
        
            
             pageurl = 'stores/cashbacks/'+id+'/edit';
             var _token = $("input[name=_token]").val();
            $.ajax({

                url:pageurl,
                method:"GET",
                data:{_token:_token},
                success:function(data)
                {
                    $('#edit-cashback-modal').modal('show');
                    $('#edit-cashback').html(data);
                    checkCashbackType();
                }
                });
               
     });
    });
    $(document).ready( function() {
        $(document).on('submit', '.cashback_form', function(event){
   
          event.preventDefault();          
          $.ajax({
                url: $(this).attr('action'),
                type: "PUT",
                data: $(this).serialize(),
                success: function(data){
                    $('#cashback-modal').modal('hide');
                    (function(NioApp, $){
                    'use strict';
                    toastr.clear();
                    NioApp.Toast('Cashback Updated Successfully.', 'success');
                    
                })(NioApp, jQuery);
                   fetchCashbacks();
                }
            });      
        });
    });
    $(document).ready( function() {
        $(document).on('submit', '.cashback_form_add', function(event){
   
          event.preventDefault();          
          $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: $(this).serialize(),
                success: function(data){
                    $('#cashback-modal').modal('hide');
                    (function(NioApp, $){
                    'use strict';
                    toastr.clear();
                    NioApp.Toast('Cashback Added Successfully.', 'success');
                    
                })(NioApp, jQuery);
                   fetchCashbacks();
                }
            });      
        });
    });
</script>

<!-- Review-->

<script>
    $(document).ready(function(){
     $(document).on('click', '.review-edit', function(event){
        event.preventDefault(); 
        
            var id = $(this).attr('review-id');
            pageurl = 'stores/reviews/'+id+'/edit';
            var _token = $("input[name=_token]").val();
            $.ajax({

                url:pageurl,
                method:"GET",
                data:{_token:_token},
                success:function(data)
                {
                    $('#review-modal').modal('show');
                    $('#review').html(data);
                    var rquill = new Quill('#reditor-container', {
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
                }
                });
               
     });
    });
    $(document).ready( function() {
        $(document).on('submit', '.review_form', function(event){
   
            event.preventDefault();      
            var editor = document.querySelector('#reditor-container')

            var desc = document.querySelector('input[name=review]');
            desc.value = editor.children[0].innerHTML 
            $.ajax({
                url: $(this).attr('action'),
                type: "PUT",
                data: $(this).serialize(),
                success: function(data){
                    $('#review-modal').modal('hide');
                    (function(NioApp, $){
                    'use strict';
                   
                    toastr.clear();
                    NioApp.Toast('Review Updated Successfully.', 'success');
                
                    
                })(NioApp, jQuery);
                   fetchReviews();
                }
            });      
        });
    });
    $(document).ready( function() {
        $(document).on('submit', '.review_form_add', function(event){
   
            event.preventDefault();      
            var editor = document.querySelector('#reditor-container')

            var desc = document.querySelector('input[name=review]');
            desc.value = editor.children[0].innerHTML 
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: $(this).serialize(),
                success: function(data){
                    $('#review-modal').modal('hide');
                    (function(NioApp, $){
                    'use strict';
                    toastr.clear();
                    NioApp.Toast('Review Added Successfully.', 'success');
                    
                })(NioApp, jQuery);
                    fetchReviews();
                }
            });      
        });
    });
</script>


<!-- Image upload-->
<script>
  $(document).ready( function() {
        $(document).on('submit', '.file-upload', function(event){

            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
                $('#file-upload').modal('hide');
                    (function(NioApp, $){
                    'use strict';
                    toastr.clear();
                    NioApp.Toast(data.message, data.updated);
                })(NioApp, jQuery);
                    fetchImages();
                    $('.file-upload').trigger("reset");
                    $('#blah').attr("src","{{asset('admin-dashboard/images/cloud-uploading.png')}}");
            },
            error: function(data){
                console.log("error");
                console.log(data);
            }
        });
     
        });
    });
    $(document).ready(function(){
     $(document).on('click', '.delete-img', function(event){
        event.preventDefault(); 
        
            var storeimage = $(this).attr('image-id');
            pageurl = 'stores/images/delete/'+storeimage;
            var _token = $("input[name=_token]").val();
            $.ajax({

                url:pageurl,
                method:"GET",
                data:{_token:_token},
                success:function(data)
                {
                    (function(NioApp, $){
                    'use strict';
                    toastr.clear();
                    NioApp.Toast('Image deleted Successfully.', 'success');
                })(NioApp, jQuery);
                    fetchImages();
            
                }
                });
               
     });
    });

</script>
<!-- Update Store-->
<script>

$(document).ready( function() {
    $(document).on('submit', '#store_form', function(event){

        event.preventDefault();
        
        
        $.ajax({
        type:'PUT',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success:function(data){
        
            (function(NioApp, $){
            'use strict';
            toastr.clear();
            NioApp.Toast('Store updated Successfully.', 'success');
        })(NioApp, jQuery);

            // console.log(data);
            
        },
        error: function(data){
            console.log("error");
            console.log(data);
        }
    });

    });
});
$(document).ready( function() {
    $(document).on('submit', '#store_cat_form', function(event){

        event.preventDefault();
        
        
        $.ajax({
        type:'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success:function(data){
        
            (function(NioApp, $){
            'use strict';
            toastr.clear();
            NioApp.Toast('Store categories updated Successfully.', 'success');
        })(NioApp, jQuery);

            // console.log(data);
            
        },
        error: function(data){
            console.log("error");
            console.log(data);
        }
    });

    });
});
</script>

<!-- Change store-->
<script>
    $(function(){
      // bind change event to select
      $('#store_select').on('change', function () {
          var url = $(this).val(); // get selected value
          if (url) { // require a URL
              window.location = url; // redirect
          }
          return false;
      });
    });

    $('input[type="checkbox"]').change(function(e) {

var checked = $(this).prop("checked"),
    container = $(this).parent(),
    siblings = container.siblings();

container.find('input[type="checkbox"]').prop({
  indeterminate: false,
  checked: checked
});

function checkSiblings(el) {

  var parent = el.parent().parent(),
      all = true;

  el.siblings().each(function() {
    let returnValue = all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
    return returnValue;
  });
  
  if (all && checked) {

    parent.children('input[type="checkbox"]').prop({
      indeterminate: false,
      checked: checked
    });

    checkSiblings(parent);

  } else if (all && !checked) {

    parent.children('input[type="checkbox"]').prop("checked", checked);
    parent.children('input[type="checkbox"]').prop("checked", (parent.find('input[type="checkbox"]:checked').length > 0));
    checkSiblings(parent);

  } else {

    el.parents("li").children('input[type="checkbox"]').prop({
      
      checked: checked
    });

  }

}

checkSiblings(container);
});
function checkCashbackType(){
    
    if ($('#type').val() == 'fixed') {    
        $('.currency-div').show();      
        $('#currency').attr('required', 'required');
    }
    else{
        $('.currency-div').hide();      
        $('#currency').removeAttr('required').val('');
    }

}
$(document.body).on("change","#type",function(){
    checkCashbackType();
});
</script>
@endpush