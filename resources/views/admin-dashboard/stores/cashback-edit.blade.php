<form action="{{ route('admin.stores.cashbacks.update', $cashback) }}" class="gy-3 form-validate is-alter cashback_form" method="POST">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="type">Type <span class="text-danger">*</span></label>
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
                <label class="form-label" for="sale_commission">Network Commission <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="number" class="form-control" min="0" step="0.01" id="sale_commission" value="{{ $cashback->sale_commission }}"
                        name="sale_commission" required oninput="calcPercentage()">
                </div>
            </div>
        </div>
        @if ($cashback->store->override_network)
            <div class="col-lg-12 network_url">
                <div class="form-group">
                    <label class="form-label" for="network_id">Network</label>
                    <div class="form-control-wrap">
                        <select class="form-control form-select select-2" id="network_id" name="network_id">
                            @foreach ($networks as $network)
                                <option @if ($cashback->network_id == $network->id) selected @endif value="{{ $network->id }}">{{ $network->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 network_url">
                <div class="form-group">
                    <label class="form-label" for="tracking_url">Tracking URL <span class="text-danger">*</span></label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" id="tracking_url" name="tracking_url" value="{{ $cashback->tracking_url }}"
                            placeholder="https://example.com/item/abc-id-1345" required style="width: 83%">
                        <span style="position: absolute; right:0; top:5px; width:17%" data-toggle="tooltip" data-placement="left"
                            title="This parameter containing ID of the click will be concatenated with tracking URL of the store (https://example.com?ref=XXX)">{{ optional($cashback->network)->click_ref ?? '?clickref=' }}XXX</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 network_url">
                <div class="form-group">
                    <label class="form-label" for="sale_commission">Deeplink URL</label>
                    <div class="form-control-wrap">
                        <span style="position:absolute; left:0; top:5px; width:3%" data-toggle="tooltip" data-placement="right"
                            title="This parameter containing URL of the click will be concatenated with deeplink URL of the store (&u=https://example.com)">{{ optional($cashback->network)->deeplink_identifier ?? '&u=' }}</span>
                        <input type="text" class="form-control" id="deeplink_url" value="{{ isset($cashback->deeplink_url) ? $cashback->deeplink_url : '' }}"
                            name="deeplink_url" value="{{ $cashback->deeplink_url }}" placeholder="https://example.com/item/abc-id-1345"
                            style="position: relative; left:30px; width: 95%">
                    </div>
                </div>
            </div>
        @endif
     
          
        <div class="col-12">
            <div class="form-group">
                <label class="form-label" for="image">Icon Upload</label>
                <div class="input-group">
                    <span class="input-group-btn">
                        <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
                            <i class="fa fa-picture-o"></i> Choose
                        </a>
                    </span>
                    <input id="thumbnail" class="form-control" type="text" name="image">
                </div>     
                <div id="holder" style="margin-top:15px;max-height:100px;"></div>             
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
