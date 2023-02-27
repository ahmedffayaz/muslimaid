@php
    $isImport = isset($csvData) ? true : false;
@endphp
<form action="{{ route('admin.commissions.store_multiple') }}" class="gy-3 form-validate is-alter" method="POST" id="save_form" autocomplete="off">
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
                            <label class="form-label" for="exit_click_id">Exit Click aaa</label>
                            <div class="form-control-wrap ">
                                <input type="number" class="form-control" id="exit_click_id" min="0.0" value="{{ $row[0] }}" name="exit_click_id[]"
                                    placeholder="Exit Click ID" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-label" for="order_value">Order Value</label>
                            <div class="form-control-wrap">
                                <input type="number" class="form-control" min="0" id="order_value" value="{{ $row[1] }}" name="order_value[]" step="0.01"
                                    placeholder="Order Value">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-label" for="network_commission">Network Commission</label>
                            <div class="form-control-wrap">
                                <input type="number" class="form-control" min="0" id="network_commission" value="{{ $row[2] }}" name="network_commission[]"
                                    step="0.01" placeholder="Network Commission" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="event_date">Event Date</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control date-picker" id="event_date" value="{{ convertDateFormat($row[3]) }}" name="event_date[]"
                                    placeholder="01/25/2000" required>
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
                                            <option value="{{ $status->id }}" @if ($status->id == $row[4]) selected @endif>{{ $status->status }}
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
                        <label class="form-label" for="exit_click_id">Exit Click <span class="text-danger">*</span></label>
                        <div class="form-control-wrap ">
                            <input type="number" class="form-control" id="exit_click_id" value="" min="0" name="exit_click_id[]" placeholder="Exit Click ID"
                                required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label" for="order_value">Order Value <span class="text-danger">*</span></label>
                        <div class="form-control-wrap">
                            <input type="number" class="form-control" id="order_value" value="" min="0" name="order_value[]" step="any"
                                placeholder="Order Value">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label" for="network_commission">Network Commission <span class="text-danger">*</span></label>
                        <div class="form-control-wrap">
                            <input type="number" class="form-control" id="network_commission" value="" min="0" name="network_commission[]" step="any"
                                placeholder="Network Commission" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="event_date">Event Date <span class="text-danger">*</span></label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control date-picker" id="event_date" value="" name="event_date[]" placeholder="01/25/2000" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
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
                        <label class="form-label" for="exit_click_id">Exit Click <span class="text-danger">*</span></label>
                        <div class="form-control-wrap ">
                            <input type="number" class="form-control" id="exit_click_id" value="" min="0" name="exit_click_id[]"
                                placeholder="Exit Click ID" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label" for="order_value">Order Value <span class="text-danger">*</span></label>
                        <div class="form-control-wrap">
                            <input type="number" class="form-control" id="order_value" value="" min="0" name="order_value[]" step="any"
                                placeholder="Order Value">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label" for="network_commission">Network Commission <span class="text-danger">*</span></label>
                        <div class="form-control-wrap">
                            <input type="number" class="form-control" id="network_commission" value="" min="0" name="network_commission[]" step="any"
                                placeholder="Network Commission" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="event_date">Event Date <span class="text-danger">*</span></label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control date-picker" id="event_date" value="" name="event_date[]" placeholder="01/25/2000" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
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
                        <label class="form-label" for="exit_click_id">Exit Click <span class="text-danger">*</span></label>
                        <div class="form-control-wrap ">
                            <input type="number" class="form-control" id="exit_click_id" value="" min="0" name="exit_click_id[]"
                                placeholder="Exit Click ID" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label" for="order_value">Order Value <span class="text-danger">*</span></label>
                        <div class="form-control-wrap">
                            <input type="number" class="form-control" id="order_value" value="" min="0" name="order_value[]" step="any"
                                placeholder="Order Value">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label" for="network_commission">Network Commission <span class="text-danger">*</span></label>
                        <div class="form-control-wrap">
                            <input type="number" class="form-control" id="network_commission" value="" min="0" name="network_commission[]" step="any"
                                placeholder="Network Commission" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="event_date">Event Date <span class="text-danger">*</span></label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control date-picker" id="event_date" value="" name="event_date[]" placeholder="01/25/2000" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
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
                        <label class="form-label" for="exit_click_id">Exit Click <span class="text-danger">*</span></label>
                        <div class="form-control-wrap ">
                            <input type="number" class="form-control" id="exit_click_id" value="" min="0" name="exit_click_id[]"
                                placeholder="Exit Click ID" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label" for="order_value">Order Value <span class="text-danger">*</span></label>
                        <div class="form-control-wrap">
                            <input type="number" class="form-control" id="order_value" value="" min="0" name="order_value[]" step="any"
                                placeholder="Order Value">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label" for="network_commission">Network Commission <span class="text-danger">*</span></label>
                        <div class="form-control-wrap">
                            <input type="number" class="form-control" id="network_commission" value="" min="0" name="network_commission[]" step="any"
                                placeholder="Network Commission" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="event_date">Event Date <span class="text-danger">*</span></label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control date-picker" id="event_date" value="" name="event_date[]" placeholder="01/25/2000" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
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
                        <label class="form-label" for="exit_click_id">Exit Click <span class="text-danger">*</span></label>
                        <div class="form-control-wrap ">
                            <input type="number" class="form-control" id="exit_click_id" value="" min="0" name="exit_click_id[]"
                                placeholder="Exit Click ID" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label" for="order_value">Order Value <span class="text-danger">*</span></label>
                        <div class="form-control-wrap">
                            <input type="number" class="form-control" id="order_value" value="" min="0" name="order_value[]" step="any"
                                placeholder="Order Value">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label" for="network_commission">Network Commission <span class="text-danger">*</span></label>
                        <div class="form-control-wrap">
                            <input type="number" class="form-control" id="network_commission" value="" min="0" name="network_commission[]" step="any"
                                placeholder="Network Commission" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="event_date">Event Date <span class="text-danger">*</span></label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control date-picker" id="event_date" value="" name="event_date[]" placeholder="01/25/2000" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
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
        <div class="form-group">
            <button type="submit" class="btn-lg btn-primary" id="multiple-cashbacks-form-btn">Save</button>
        </div>
    </div>
</form>
