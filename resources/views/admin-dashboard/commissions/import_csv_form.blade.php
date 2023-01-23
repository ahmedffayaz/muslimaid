<!-- Modal Form -->
<form class="form-validate is-alter" action="{{ route('admin.commissions.import') }}" method="POST" id="save_modal_form" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label">Import Cashbacks</label>
                    <div class="form-control-wrap">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="import-csv" name="import_cashback">
                            <label class="custom-file-label">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <span class="badge badge-dot badge-danger">Must be upload CSV file.</span>
                </div>
            </div>
            <div class="col-12 mt-3">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" id='save-btn'></button>
                </div>
            </div>
        </div>
    </div>
</form>
