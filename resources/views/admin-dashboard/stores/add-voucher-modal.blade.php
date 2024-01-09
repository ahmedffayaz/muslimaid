<form action="{{ route(getAdminPrefix() . '.vouchers.store') }}" class="gy-3 form-validate is-alter add_voucher_form" id="add_voucher_validation" method="POST">
    @csrf
    <input type="hidden" name="store_id" value="{{ $store->id }}">
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="form-group">
                <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" placeholder="Name" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="tracking_url">Tracking URL</label>
                <div class="form-control-wrap">
                    <input type="url" class="form-control" placeholder="Keep empty to use store's Tracking URL" id="tracking_url" name="tracking_url" value="{{ old('tracking_url') }}" style="width: 79%">
                    <span style="position: absolute; right:0; top:5px; width:20%" data-toggle="tooltip" data-placement="left"
                        title="This parameter containing ID of the click will be concatenated with tracking URL of the store ({{ $store? $store->tracking_url : '' }})">?ref=XXX</span>
                    @error('tracking_url')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="deeplink_url">Deeplink URL</label>
                <div class="form-control-wrap">
                    <span style="position:absolute; left:0; top:5px; width:7%" data-toggle="tooltip" data-placement="right"
                        title="This parameter containing URL of the click will be concatenated with deeplink URL of the store ({{ $store? $store->deeplink_url : '' }})">&u=</span>
                    <input type="url" class="form-control" placeholder="Keep empty to use store's Deeplink URL" id="deeplink_url" name="deeplink_url" value="{{ old('deeplink_url') }}"
                    style="position: relative; left:30px; width: 93%">
                    @error('deeplink_url')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <input name="description" type="hidden">
                <label class="form-label" for="description">Description</label>
                <textarea name="description" class="form-control "> {{ old('description') }}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <div class="form-group">
                    <label class="form-label" for="promotion_type">Promotion Type <span class="text-danger">*</span></label>
                    <div class="form-control-wrap ">
                        <select class="form-select form-control select-2" data-search="on" id="promotion_type" name="promotion_type"
                            value="{{ old('promotion_type') }}" required>
                            <option value="Coupon">Coupon</option>
                            <option value="Sale/Discount">Sale/Discount</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 coupon-div">
            <div class="form-group">
                <label class="form-label" for="coupon_code">Coupon Code <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="coupon_code" name="coupon_code" value="{{ old('coupon_code') }}" required>
                    @error('coupon_code')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="promotion_start_date">Promotion Start Date <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control date-picker promotion_start_date" value="{{ old('promotion_start_date') }}"
                        id="promotion_start_date" name="promotion_start_date" autocomplete="off" required>
                    @error('promotion_start_date')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="promotion_end_date">Promotion End Date <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control date-picker promotion_end_date" value="{{ old('promotion_end_date') }}"
                        id="promotion_end_date" name="promotion_end_date" autocomplete="off" required>
                    @error('promotion_end_date')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-primary">Save</button>
            </div>
        </div>
    </div>
</form>