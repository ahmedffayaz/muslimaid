<form action="{{ route(getAdminPrefix() . '.stores.update_address') }}" class="gy-3 form-validate is-alter address_form" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="address_id" value="{{ $address->id }}">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="full-name-1">City <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="city" name="city" value="{{ $address->city }}" required>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="vsale_commission">Postal code <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" step="any" class="form-control" id="postal_code" name="postal_code" value="{{ $address->postal_code }}" required>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="latitude">Latitude <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="number" min="-90" max="90" step="any" class="form-control" id="latitude" name="latitude" value="{{ $address->latitude }}"
                        required>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="longitude">Longitude <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="number" min="-180" max="180" step="any" class="form-control" id="longitude" name="longitude" value="{{ $address->longitude }}"
                        required>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label class="form-label" for="vsale_commission">Address <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <textarea class="form-control" id="address" value="" name="address" required>{{ $address->address }}</textarea required>
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
