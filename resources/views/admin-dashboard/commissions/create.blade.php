@php
    $isEdit = isset($commission) ? true : false;
@endphp
<form action="{{ $isEdit ? route(getAdminPrefix() . '.commissions.update', $commission->id) : route(getAdminPrefix() . '.commissions.store') }}" class="gy-3 is-alter forms-validate" method="POST"
    id="update_cashback_form">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif
    <input type="hidden" name="id" id="id" value="{{ $isEdit ? $commission->id : '' }}">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="exit_click_id">Exit Click <span class="text-danger">*</span></label>
                <div class="form-control-wrap ">
                    <input type="number" min="1" step="1" class="form-control" id="exit_click_id" value="{{ $isEdit ? $commission->exit_click_id : '' }}"
                        name="exit_click_id" placeholder="Exit Click ID" required>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="order_value">Order Value <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="number" class="form-control" min="0.01" step="0.01" id="order_value" value="{{ $isEdit ? $commission->order_value : '' }}"
                        name="order_value" placeholder="Order Value">
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="network_commission">Network Commission <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="number" class="form-control" id="network_commission" value="{{ $isEdit ? $commission->network_commission : '' }}" name="network_commission"
                        placeholder="Network Commission" min="0.01" step="0.01" required>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="default-06">Status <span class="text-danger">*</span></label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control form-select" name="status" required>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}" {{ $isEdit && $status->id == $commission->status ? 'selected' : '' }}>
                                    {{ $status->status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn-lg btn-primary" id="save-btn">Save</button>
            </div>
        </div>
    </div>
</form>
