<form action="{{ route(getAdminPrefix() . '.stores.update_seo', $storeSeoRule) }}" class="gy-3 form-validate is-alter seo_form" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="seo_id" value="{{ $storeSeoRule->id }}">
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="form-group">
                <label class="form-label" for="full-name-1">Key <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <select class="form-control select-2 key" id="editkey" name="key" required>
                        <option selected disabled>Select Key</option>
                        <option {{ $storeSeoRule->key == 'meta:keywords' ? 'selected' : '' }} value="meta:keywords">Meta:keywords</option>
                        <option {{ $storeSeoRule->key == 'meta:description' ? 'selected' : '' }} value="meta:description">Meta:description</option>
                        <option {{ $storeSeoRule->key == 'meta:title' ? 'selected' : '' }} value="meta:title">Meta:title</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-12 key_value">
            <div class="form-group">
                <label class="form-label" for="meta_description">Meta Description <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <textarea class="form-control" id="value" value="" name="value" required>{{ $storeSeoRule->value }}</textarea>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-primary">update</button>
            </div>
        </div>
    </div>
    </div>
</form>
