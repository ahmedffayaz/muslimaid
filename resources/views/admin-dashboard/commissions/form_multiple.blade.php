@php
    $isImport = isset($csvData) ? true : false;
@endphp
<form action="{{ route('admin.commissions.store_multiple') }}" class="gy-3 form-validate is-alter" method="POST"
    id="save_form" autocomplete="off">
    @csrf
    @method('POST')
    <div class="fields-container">
        @if ($isImport)
            @foreach ($csvData as $row)
                <div class="row ">
                    <div class="col-lg-12 ml-auto mt-3">
                        <span class="delete-row float-right">
                            <em class="icon ni ni-cross-circle-fill text-danger"></em>
                        </span>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="exit_click_id">Exit Click</label>
                            <div class="form-control-wrap ">
                                <input type="number" class="form-control" id="exit_click_id"
                                    value="{{ $row[0] }}" name="exit_click_id[]" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="order_value">Order Value</label>
                            <div class="form-control-wrap">
                                <input type="number" class="form-control" id="order_value" value="{{ $row[1] }}"
                                    name="order_value[]" step="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="network_commission">Network Commission</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="network_commission" value="{{ $row[2] }}"
                                    name="network_commission[]" step="0.00" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="amount">Cashback Amount</label>
                            <div class="form-control-wrap">
                                <input type="number" class="form-control" id="amount"
                                    value="{{ !empty($row[3]) ? $row[3] : '' }}" name="amount[]" step="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="event_date">Event Date</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control date-picker" id="event_date"
                                    value="{{ $row[4] }}" name="event_date[]" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="status">Status</label>
                            <div class="form-control-wrap ">
                                <div class="form-control-select">
                                    <select class="form-control form-select select-2" name="status[]" required>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->id }}"
                                                @if ($status->id == $row[5]) selected @endif>{{ $status->status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="row ">
                <div class="col-lg-12 ml-auto mt-3">
                    <span class="delete-row float-right">
                        <em class="icon ni ni-cross-circle-fill text-danger"></em>
                    </span>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="exit_click_id">Exit Click</label>
                        <div class="form-control-wrap ">
                            <input type="text" class="form-control" id="exit_click_id" value=""
                                name="exit_click_id[]" required>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="phone-no-1">Order Value</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="phone-no-1" value=""
                                name="order_value[]">
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="phone-no-1">Network Commission</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="phone-no-1" value=""
                                name="network_commission[]" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="phone-no-1">Cashback Amount</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="phone-no-1" value=""
                                name="amount[]" value="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="pay-amount-1">Event Date</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control date-picker" id="pay-amount-1" value=""
                                name="event_date[]" required>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="status">Status</label>
                        <div class="form-control-wrap ">
                            <div class="form-control-select">
                                <select class="form-control form-select select-2" name="status[]" required>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-lg-12 ml-auto mt-3">
                    <span class="delete-row float-right">
                        <em class="icon ni ni-cross-circle-fill text-danger"></em>
                    </span>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="exit_click_id">Exit Click</label>
                        <div class="form-control-wrap ">
                            <input type="text" class="form-control" id="exit_click_id" value=""
                                name="exit_click_id[]" required>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="phone-no-1">Order Value</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="phone-no-1" value=""
                                name="order_value[]">
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="phone-no-1">Network Commission</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="phone-no-1" value=""
                                name="network_commission[]" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="phone-no-1">Cashback Amount</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="phone-no-1" value=""
                                name="amount[]" value="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="pay-amount-1">Event Date</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control date-picker" id="pay-amount-1" value=""
                                name="event_date[]" required>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="status">Status</label>
                        <div class="form-control-wrap ">
                            <div class="form-control-select">
                                <select class="form-control form-select select-2" name="status[]" required>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-lg-12 ml-auto mt-3">
                    <span class="delete-row float-right">
                        <em class="icon ni ni-cross-circle-fill text-danger"></em>
                    </span>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="exit_click_id">Exit Click</label>
                        <div class="form-control-wrap ">
                            <input type="text" class="form-control" id="exit_click_id" value=""
                                name="exit_click_id[]" required>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="phone-no-1">Order Value</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="phone-no-1" value=""
                                name="order_value[]">
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="phone-no-1">Network Commission</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="phone-no-1" value=""
                                name="network_commission[]" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="phone-no-1">Cashback Amount</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="phone-no-1" value=""
                                name="amount[]" value="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="pay-amount-1">Event Date</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control date-picker" id="pay-amount-1" value=""
                                name="event_date[]" required>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="status">Status</label>
                        <div class="form-control-wrap ">
                            <div class="form-control-select">
                                <select class="form-control form-select select-2" name="status[]" required>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-lg-12 ml-auto mt-3">
                    <span class="delete-row float-right">
                        <em class="icon ni ni-cross-circle-fill text-danger"></em>
                    </span>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="exit_click_id">Exit Click</label>
                        <div class="form-control-wrap ">
                            <input type="text" class="form-control" id="exit_click_id" value=""
                                name="exit_click_id[]" required>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="phone-no-1">Order Value</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="phone-no-1" value=""
                                name="order_value[]">
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="phone-no-1">Network Commission</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="phone-no-1" value=""
                                name="network_commission[]" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="phone-no-1">Cashback Amount</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="phone-no-1" value=""
                                name="amount[]" value="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="pay-amount-1">Event Date</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control date-picker" id="pay-amount-1" value=""
                                name="event_date[]" required>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="status">Status</label>
                        <div class="form-control-wrap ">
                            <div class="form-control-select">
                                <select class="form-control form-select select-2" name="status[]" required>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-lg-12 ml-auto mt-3">
                    <span class="delete-row float-right">
                        <em class="icon ni ni-cross-circle-fill text-danger"></em>
                    </span>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="exit_click_id">Exit Click</label>
                        <div class="form-control-wrap ">
                            <input type="text" class="form-control" id="exit_click_id" value=""
                                name="exit_click_id[]" required>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="phone-no-1">Order Value</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="phone-no-1" value=""
                                name="order_value[]">
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="phone-no-1">Network Commission</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="phone-no-1" value=""
                                name="network_commission[]" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="phone-no-1">Cashback Amount</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="phone-no-1" value=""
                                name="amount[]" value="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="pay-amount-1">Event Date</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control date-picker" id="pay-amount-1" value=""
                                name="event_date[]" required>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="status">Status</label>
                        <div class="form-control-wrap ">
                            <div class="form-control-select">
                                <select class="form-control form-select select-2" name="status[]" required>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <span class="btn btn-sm btn-success float-right" onclick="addRows();">Add Row</span>
    <div class="row ">
        {{-- <div class="col-12"> --}}
            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-primary">Save</button>
            </div>
        {{-- </div> --}}
    </div>
</form>
