<div class="modal-header align-center">
    <div class="nk-file-title">
        <div class="nk-file-name">
            <div class="nk-file-name-text"><span class="title">Upload image for {{ $store->name }}</span></div>
        </div>
    </div>
    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
</div>
<form action="{{ route(getAdminPrefix() . '.stores.images.upload', $store) }}" class="form-validate file-upload" method="POST" enctype="multipart/form-data">
    <div class="modal-body modal-body-md">
        @csrf
        <div class="row gy-4">
            <div class="col-lg-12 mx-auto">
                <div class="form-group">
                    <div class="text-center mb-4 logo">
                        <label for="logo-input">
                            <img id="blah" src="{{ asset('admin-dashboard/images/cloud-uploading.png') }}" alt="store logo" width="150px" />
                            <input id="logo-input" name="image" class="d-none" type='file' onchange="readURL(this);" required />
                            <br> <br><span>Click here to select image <span class="text-danger">*</span></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label" for="title">Type</label>
                    <div class="form-control-wrap ">
                        <div class="form-control-select">
                            <select class="form-control" id="title" name="title" required>
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
            <div class="g"></div>
            <div class="g">
                <ul class="btn-toolbar g-3">
                    <li><a href="#file-share" data-dismiss="modal"class="btn btn-outline-light btn-white">Cancel</a></li>
                    <li><button type="submit" class="btn btn-primary file-dl-toast">Upload</button></li>
                </ul>
            </div>
        </div>
    </div><!-- .modal-footer -->
</form>