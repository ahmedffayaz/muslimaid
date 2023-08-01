<form action="{{ route(getAdminPrefix() . '.stores.save_seo_rule') }}" class="gy-3 form-validate is-alter add_seorule_form" id="theForm" method="POST">
    @csrf
    <input type="hidden" name="store_id" value="{{ $store->id }}">
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="form-group">
                <label class="form-label" for="full-name-1">Key <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <select class="form-control key" id="key" name="key" required>
                        <option selected disabled>Select Key</option>
                        <option value="meta:keywords">Meta:keywords</option>
                        <option value="meta:description">Meta:description</option>
                        <option value="meta:title">Meta:title</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-12 key_value">
            <div class="form-group">
                <label class="form-label" for="vsale_commission">Value <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <textarea class="form-control" id="value" value="" name="value" required></textarea>
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