@php
    $isEdit = isset($store) ? true : false;
    // $url = $isEdit ? route('job-safety.update', $job_safety) : route('job-safety.store');
@endphp
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
            @if($store->storeAddress()->count() == 0)
                <div class="col-lg-11  address-fields" data-count="{{$isEdit ? $store->count() : 1}}">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="latitude">Latitude</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="latitude" name="address[0][latitude]" value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="longitude">Longitude</label>
                                <div class="form-control-wrap">
                                <input type="text"  class="form-control" id="longitude" name="address[0][longitude]" value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label" for="vsale_commission">Address</label>
                                <div class="form-control-wrap">
                                    <textarea class="form-control" id="address" value="" name="address[0][address]" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
            @foreach ($store->storeAddress()->get() as $key => $val)
                <div class="col-lg-11">
                    <div class=" address-fields" data-count="{{$isEdit ? $store->storeAddress()->count() : 1}}">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="latitude">Latitude</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="latitude" name="address[{{$key}}][latitude]" value="{{ $val->latitude }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="longitude">Longitude</label>
                                    <div class="form-control-wrap">
                                    <input type="text"  class="form-control" id="longitude" name="address[{{$key}}][longitude]" value="{{ $val->longitude }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="vsale_commission">Address</label>
                                    <div class="form-control-wrap">
                                        <textarea class="form-control" id="address" value="" name="address[{{$key}}][address]" required>{{ $val->address }}</textarea>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-lg-1" style="margin-left: 817px;margin-top: -96px;">
                                <em class="icon ni ni-minus-c removeBtn" ></em>
                            </div> --}}
                        </div>
                    </div>
                </div>
        @endforeach
        @endif
        <div class="col-lg-1" style="margin-top: 41px">
            <span><em class="icon ni ni-plus-c addBtn" ></em></span>
        </div>
        <div class="col-lg-11 append-fields">
        </div>
            <div class="col-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>