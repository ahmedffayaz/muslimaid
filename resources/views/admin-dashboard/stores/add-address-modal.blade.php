<form action="{{ route(getAdminPrefix() . '.stores.save_address') }}" class="gy-3 form-validate is-alter add_address_form" method="POST">
    @csrf
    <input type="hidden" name="store_id" value="{{ $store->id }}">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="full-name-1">City <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="city" name="city" value="" required>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="vsale_commission">Postal code <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="postal_code" name="postal_code" value="" required>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="latitude">Latitude <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="number" class="form-control" min="-90" max="90" step="any" id="latitude" name="latitude"
                        value="" required>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="longitude">Longitude <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="number" class="form-control" min="-180" max="180" step="any" id="longitude" name="longitude"
                        value="" required>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label class="form-label" for="vsale_commission">Address <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <textarea class="form-control" id="address" value="" name="address" required></textarea>
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