@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.vouchers.update', $voucher) }}" class="gy-3 form-validate is-alter voucher_form" method="POST">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="link_name">Title</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="link_name" value="{{ $voucher->link_name }}" name="link_name" required>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="store_id">Store</label>
                <div class="form-control-wrap ">
                    <select class="form-select form-control select-2" data-search="on" value="{{ $voucher->store_id }}" id="store_id" name="store_id" required>
                        @foreach ($stores as $store)
                            <option @if ($store->id == $voucher->store_id) selected @endif value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="click_url">Click url</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="click_url" value="{{ $voucher->click_url }}" name="click_url" required>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="destination">Destination url</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="destination" value="{{ $voucher->destination }}" name="destination" required>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <label class="form-label" for="phone-no-1">Description</label>
                <textarea name="description" class="form-control ">{!! $voucher->description !!}</textarea>

            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <div class="form-group">
                    <label class="form-label" for="promotion_type">Promotion Type</label>
                    <div class="form-control-wrap ">
                        <select class="form-select form-control select-2" data-search="on" id="promotion_type" name="promotion_type" required>
                            <option @if ($voucher->promotion_type == 'Coupon') selected @endif value="Coupon">Coupon</option>
                            <option @if ($voucher->promotion_type == 'Sale/Discount') selected @endif value="Sale/Discount">Sale/Discount</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 coupon-div">
            <div class="form-group">
                <label class="form-label" for="coupon_code">Coupon Code</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="coupon_code" value="{{ $voucher->coupon_code }}" name="coupon_code" required>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="sale_commission">Sale commission</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="sale_commission" value="{{ $voucher->sale_commission }}" name="sale_commission" required>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="promotion_start_date">Promotion Start Date</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control date-picker" id="promotion_start_date"
                        value="{{ \Carbon\Carbon::parse($voucher->promotion_start_date)->format('Y-m-d') }}" name="promotion_start_date" required>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="promotion_end_date">Promotion End Date</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control date-picker" id="promotion_end_date"
                        value="{{ \Carbon\Carbon::parse($voucher->promotion_end_date)->format('Y-m-d') }}" name="promotion_end_date" required>
                </div>
                @if ($errors->has('promotion_end_date'))
                    <span class="invalid-feedback d-block" role="alert">End date must be greater than start date.</span>
                @endif
            </div>
        </div>

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

        $(".voucher_form").submit(function(e) {
            e.preventDefault();

            // Populate hidden form on submit
            var desc = document.querySelector('input[name=description]');
            desc.value = vquill.root.innerHTML;

            alert('yes');
        });
    </script>
@endpush
