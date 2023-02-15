@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@php
    $isEdit = isset($voucher) ? true : false;
@endphp
<form action="{{ $isEdit ? route('admin.vouchers.update', $voucher) : route('admin.vouchers.store') }}" class="gy-3 is-alter" id="create-edit-voucher-form" method="POST">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="link_name">Title</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="link_name" value="{{ $isEdit ? $voucher->link_name : '' }}" name="link_name" required>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="store_id">Store</label>
                <div class="form-control-wrap ">
                    <select class="form-select form-control select-2" data-search="on" value="{{ $isEdit ? $voucher->store_id : '' }}" id="store_id" name="store_id"
                        required>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}" {{ $isEdit && $store->id == $voucher->store_id ? 'selected' : '' }}>{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="click_url">Click url</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="click_url" value="{{ $isEdit ? $voucher->click_url : '' }}" name="click_url" required>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="destination">Destination url</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="destination" value="{{ $isEdit ? $voucher->destination : '' }}" name="destination" required>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <label class="form-label" for="phone-no-1">Description</label>
                <textarea name="description" class="form-control ">{!! $isEdit ? $voucher->description : '' !!}</textarea>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <div class="form-group">
                    <label class="form-label" for="promotion_type">Promotion Type</label>
                    <div class="form-control-wrap ">
                        <select class="form-select form-control select-2" data-search="on" id="promotion_type" name="promotion_type" required>
                            <option value="Coupon" {{ $isEdit && $voucher->promotion_type == 'Coupon' ? 'selected' : '' }}>Coupon</option>
                            <option value="Sale/Discount" {{ $isEdit && $voucher->promotion_type == 'Sale/Discount' ? 'selected' : '' }}>Sale/Discount</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 coupon-div">
            <div class="form-group">
                <label class="form-label" for="coupon_code">Coupon Code</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="coupon_code" value="{{ $isEdit ? $voucher->coupon_code : '' }}" name="coupon_code" required>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="sale_commission">Sale commission</label>
                <div class="form-control-wrap">
                    <input type="number" min="0.0" step="1" class="form-control" id="sale_commission" value="{{ $isEdit ? $voucher->sale_commission : '' }}" name="sale_commission" required>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="promotion_start_date_edit">Promotion Start Date</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control date-picker promotion_start_date" id="promotion_start_date_edit"
                        value="{{ $isEdit ? \Carbon\Carbon::parse($voucher->promotion_start_date)->format('m/d/Y') : '' }}" name="promotion_start_date" autocomplete="off"
                        required>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="promotion_end_date_edit">Promotion End Date</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control date-picker promotion_end_date" id="promotion_end_date_edit"
                        value="{{ $isEdit ? \Carbon\Carbon::parse($voucher->promotion_end_date)->format('m/d/Y') : '' }}" name="promotion_end_date" autocomplete="off"
                        required>
                </div>
                @if ($errors->has('promotion_end_date'))
                    <span class="invalid-feedback d-block" role="alert">End date must be greater than start date.</span>
                @endif
            </div>
        </div>

        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-primary">
                    <span>Save</span>
                </button>
            </div>
        </div>
    </div>
</form>
