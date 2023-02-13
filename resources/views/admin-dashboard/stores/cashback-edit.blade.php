<form action="{{ route('admin.stores.cashbacks.update', $cashback) }}" class="gy-3 form-validate is-alter cashback_form" method="POST">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="type">Type</label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" id="type" name="type" required>
                            <option @if ($cashback->type == 'percentage') selected @endif value="percentage">Percentage</option>
                            <option @if ($cashback->type == 'fixed') selected @endif value="fixed">Fixed</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="sale_commission">Network Commission</label>
                <div class="form-control-wrap">
                    <input type="number" class="form-control" min="0" step="0.01" id="sale_commission" value="{{ $cashback->sale_commission }}" name="sale_commission" required
                        oninput="calcPercentage()">
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label class="form-label" for="sale_commission">Deeplink URl</label>
                <div class="form-control-wrap">
                    <span style="position:absolute; left:0; top:5px; width:3%" data-toggle="tooltip" data-placement="right"
                            title="This parameter containing URL of the click will be concatenated with deeplink URL of the store (&u={{ isset($cashback->deeplink_url) ? $cashback->deeplink_url : '' }})">&{{ $cashback->store->network->deeplink_identifier }}=</span>
                    <input type="text" class="form-control" id="deeplink_url" value="{{ isset($cashback->deeplink_url) ? $cashback->deeplink_url : '' }}"
                        name="deeplink_url" style="position: relative; left:30px; width: 95%">
                </div>
            </div>
        </div>
        <div class="col-lg-6 currency-div">
            <div class="form-group">
                <label class="form-label" for="currency">Currency</label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" id="currency" name="currency" required>
                            @foreach ($currencies as $currency)
                                <option @if ($cashback->currency == $currency->id) selected @endif value="{{ $currency->id }}">{{ $currency->short_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <label class="form-label" for="phone-no-1">Detail</label>
                <textarea name="detail" class="form-control ">{{ $cashback->detail }}</textarea>
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
@endpush
