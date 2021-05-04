@if(count($store->images) < 2)<a href="#file-upload" class="btn btn-primary float-right" data-toggle="modal"><em class="icon ni ni-upload-cloud"></em> <span>Upload</span></a>
@endif
@if(count($store->images))
<div class="nk-block">
<div class="nk-files nk-files-view-grid">
    
    <div class="nk-files-list">
    @foreach ($store->images as $item)
        <div class="nk-file-item nk-file align-items-center d-flex justify-content-center card">
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
                            <li><a href="" image-id={{$item->id}} class="delete-img"><em class="icon ni ni-trash"></em><span>Delete</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
           
        </div><!-- .nk-file -->
        
    @endforeach
        
    </div>
    
</div>

</div><!-- .nk-block -->
@else 
<p>No images found</p>
@endif