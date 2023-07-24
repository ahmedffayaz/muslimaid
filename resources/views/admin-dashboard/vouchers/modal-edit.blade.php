<form action="{{ route(getAdminPrefix() . '.vouchers.update', $voucher) }}" class="gy-3 form-validate is-alter voucher_form " id="model_edit" method="POST">
    @csrf
    @method('PUT')
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" id="name" value="{{ $voucher->name }}" name="name" required>
                        <input type="hidden" name="store_id" id="store_id" value="{{ $voucher->store->id }}"></input>
                    </div>
                </div>
            </div>
        </div>
        <div class="row"> 
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="tracking_url">Tracking URL</label>
                    <div class="form-control-wrap">
                        <input type="url" class="form-control" id="tracking_url" value="{{ $voucher->tracking_url }}"
                            name="tracking_url" placeholder="Keep empty to use store's Tracking URL" style="width: 79%">
                            <span style="position: absolute; right:0; top:5px; width:20%" data-toggle="tooltip" data-placement="left"
                                title="This parameter containing ID of the click will be concatenated with tracking URL of the store ({{ $voucher->store->tracking_url ? $voucher->store->tracking_url : '' }})">{{ optional($voucher->store->network)->click_ref }}XXX</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="deeplink_url">Deeplink URL</label>
                    <div class="form-control-wrap">
                        <span style="position:absolute; left:0; top:5px; width:7%" data-toggle="tooltip" data-placement="right"
                            title="This parameter containing URL of the click will be concatenated with deeplink URL of the store ({{ $voucher->store->deeplink_url ? $voucher->store->deeplink_url : '' }})">{{ optional($voucher->store->network)->deeplink_identifier }}</span>
                        <input type="url" class="form-control" id="deeplink_url" value="{{ $voucher->deeplink_url }}" name="deeplink_url"
                            placeholder="Keep empty to use store's Deeplink URL" style="position: relative; left:30px; width: 93%">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <label class="form-label" for="phone-no-1">Description </label>
                    <textarea name="description" class="form-control ">{!! $voucher->description !!}</textarea >

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="form-group">
                        <label class="form-label" for="promotion_type">Promotion Type <span class="text-danger">*</span></label>
                        <div class="form-control-wrap ">
                            <select class="form-select form-control select-2" data-search="on" id="promotion_type" name="promotion_type" required>
                                <option value="Coupon" {{  $voucher->promotion_type == 'Coupon' ? 'selected' : '' }}>Coupon</option>
                                <option value="Sale/Discount" {{  $voucher->promotion_type == 'Sale/Discount' ? 'selected' : '' }}>Sale/Discount</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 coupon-div">
                <div class="form-group">
                    <label class="form-label" for="coupon_code">Coupon Code <span class="text-danger">*</span></label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" id="coupon_code" value="{{ $voucher->coupon_code }}" name="coupon_code" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="promotion_start_date">Promotion Start Date <span class="text-danger">*</span></label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control date-picker promotion_start_date" id="promotion_start_date" value="{{ \Carbon\Carbon::parse($voucher->promotion_start_date)->format('m/d/Y')  }}" name="promotion_start_date" 
                            required>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="promotion_end_date">Promotion End Date <span class="text-danger">*</span></label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control date-picker promotion_end_date" id="promotion_end_date" value="{{  \Carbon\Carbon::parse($voucher->promotion_end_date)->format('m/d/Y') }}" name="promotion_end_date"
                            required>
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

@push('scripts')
    <link rel="stylesheet" href="{{ asset('admin-dashboard/css/editors/summernote.css?ver=2.2.0') }}">
        <script src="{{ asset('admin-dashboard/js/libs/editors/summernote.js?ver=2.2.0') }}"></script>

        <link rel="stylesheet" href="{{ asset('admin-dashboard/css/editors/quill.css?ver=2.2.0') }}">
        <script src="{{ asset('admin-dashboard/js/libs/editors/quill.js?ver=2.2.0') }}"></script>

        <script src="{{ asset('admin-dashboard/js/editors.js?ver=2.2.0') }}"></script>

        <script>
            var vquill = new Quill('#veditor-container', {
                modules: {
                    toolbar: [
                        ['bold', 'italic'],
                        ['link', 'blockquote', 'code-block', 'image'],
                        [{
                            list: 'ordered'
                        }, {
                            list: 'bullet'
                        }]
                    ]
                },
                placeholder: 'Compose an epic...',
                theme: 'snow'
            });
            //   var form = document.querySelector('form');
            $(".voucher_form").submit(function(e) {
                e.preventDefault();
                // Populate hidden form on submit
                var desc = document.querySelector('input[name=description]');
                desc.value = vquill.root.innerHTML;
            });
        </script>
@endpush
