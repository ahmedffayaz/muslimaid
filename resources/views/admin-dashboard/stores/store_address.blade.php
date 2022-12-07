<div id="add-seorule-form" class=" p-4">
    <form action="{{route('admin.stores.save_address')}}" class="gy-3 form-validate is-alter add_seo_form" method="POST">
        @csrf
        <input type="hidden" name="store_id" value="{{$store->id}}">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="full-name-1">City</label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" id="city" name="city" value="{{!empty($store->city) ? $store->city : ''}}">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="vsale_commission">Postal code</label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{!empty($store->postal_code) ? $store->postal_code : ''}}">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="latitude">Latitude</label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" id="latitude" name="latitude" value="{{!empty($store->latitude) ? $store->latitude : ''}}" required>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="longitude">Longitude</label>
                    <div class="form-control-wrap">
                    <input type="text"  class="form-control" id="longitude" name="longitude" value="{{!empty($store->longitude) ? $store->longitude : ''}}" required>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label" for="vsale_commission">Address</label>
                    <div class="form-control-wrap">
                        <textarea class="form-control" id="address" value="" name="address" required>{{!empty($store->address) ? $store->address : ''}}</textarea>
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