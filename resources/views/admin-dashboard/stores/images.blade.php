@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{$store->name}}'s images</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have total {{count($store->images)}}  images.</p>
                            </div>
                            
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        {{-- <li class="nk-block-tools-opt"><a href="" class="btn btn-primary btn-sm"><em class="icon ni ni-plus"></em><span>Upload</span></a></li> --}}
                                        <li><a href="#file-upload" class="btn btn-primary" data-toggle="modal"><em class="icon ni ni-upload-cloud"></em> <span>Upload</span></a></li>

                                    
                                        {{-- <li><a href="" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li> --}}
                                      
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                @include('flash::message')
                <div class="nk-block">
                    <div class="nk-files nk-files-view-grid">
                        
                        <div class="nk-files-list">
                            @foreach ($store->images as $item)
                            <div class="nk-file-item nk-file align-items-center d-flex justify-content-center">
                                <div class="nk-file-info">
                                    <div class="nk-file-title">
                                        <div class="nk-file-icon">
                                            <a class="nk-file-icon-link" href="{{asset('storage/stores/images/'.$item->image)}}" target="_blank">
                                                <span class="nk-file-icon-type">
                                                   <img src="{{asset('storage/stores/images/'.$item->image)}}" alt="">
                                                </span>
                                            </a>
                                        </div>
                                        <div class="nk-file-name">
                                            <div class="nk-file-name-text">
                                                <a href="#" class="title">{{$item->title}}</a>
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="nk-file-actions">
                                    <div class="dropdown">
                                        <a href="" class="dropdown-toggle btn btn-sm btn-icon btn-trigger" data-toggle="dropdown" aria-expanded="false"><em class="icon ni ni-more-h"></em></a>
                                        <div class="dropdown-menu dropdown-menu-right" style="">
                                            <ul class="link-list-plain no-bdr">
                                                <li><a href="{{asset('storage/stores/images/'.$item->image)}}" target="_blank"><em class="icon ni ni-eye"></em><span>View</span></a></li>
                                                <li><a href="{{route('admin.stores.images.delete',$item)}}"><em class="icon ni ni-trash"></em><span>Delete</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .nk-file -->
                            @endforeach
                        </div>
                    </div>
                  
                </div><!-- .nk-block -->
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
        <form action="{{route('admin.stores.images.upload',$store)}}" class="form-validate" method="POST" enctype="multipart/form-data">

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
                                <label class="form-label" for="pay-amount-1">Title (title must be unique)</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="pay-amount-1" value="" name="title" required>
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
 
@endsection

@push('scripts')
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
@endpush   
