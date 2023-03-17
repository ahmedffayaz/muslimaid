<form action="{{ route('admin.commissions.import') }}" class="form-validate file-upload" method="POST" id="save_modal_form" enctype="multipart/form-data">
    <div class="modal-body modal-body-md">
            @csrf
            <div class="row gy-4">
                <div class="col-lg-12 mx-auto">
                    <div class="form-group">
                        <div class="text-center mb-4 logo">
                            <label for="logo-input">
                            <img id="blah" src="{{asset('admin-dashboard/images/cloud-uploading.png')}}" alt="store logo" width="150px"/>
                            <input id="logo-input" name="import_cashback" class="d-none" type='file'/>
                            <br> <br><span>Click here to select .csv file</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <span class="badge badge-dot badge-danger">Only .csv file allowed.</span>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <span class="badge badge-dot badge-danger">
                            <a href="{{ route('admin.commissions.download.file') }}">Download CSV demo file</a>
                        </span>
                    </div>
                </div>
            </div>
    </div>
    <div class="modal-footer modal-footer-stretch bg-light">
        <div class="modal-footer-between">
            <div class="g">
            </div>
            <div class="g">
                <ul class="btn-toolbar g-3">
                    <li><a href="#file-share" data-dismiss="modal"class="btn btn-outline-light btn-white">Cancel</a></li>
                    <li><button type="submit" class="btn btn-primary file-dl-toast" id="save-btn">Import</button></li>
                </ul>
            </div>
        </div>
    </div><!-- .modal-footer -->
</form>
