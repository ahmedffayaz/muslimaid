@php
    $isEdit = isset($country) ? true : false;
@endphp
<form class="form-validate is-alter" method="POST" id="save_modal_form">
    @csrf
    <input type="hidden" value="{{ $isEdit ? $country->id : '' }}" id="id">
    <div class="modal-body">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="title">Title</label>
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="{{ $isEdit ? $country->name : '' }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="iso_code">ISO Code</label>
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <input type="text" class="form-control" id="iso_code" name="iso_code" placeholder="ISO Code" value="{{ $isEdit ? $country->iso_code : '' }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="region_id">Region</label>
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <select class="form-control form-select" name="region_id" required>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}" {{ $isEdit && $country->region_id == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label" for="currency_id">Currency</label>
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <select class="form-control form-select" name="currency_id" required>
                                <option value="0">Currency</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}" {{ $isEdit && $country->currency_id == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label" for="upload_type">Upload Type</label>
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <select class="form-control form-select" name="upload_type" id="upload_type" required>
                                <option disabled selected>Select Upload Type</option>
                                <option value="upload" {{ $isEdit && $country->upload_type == 'upload' ? 'selected' : '' }}>Upload</option>
                                <option value="url" {{ $isEdit && $country->upload_type == 'url' ? 'selected' : '' }}>URL</option>
                                <option value="code" {{ $isEdit && $country->upload_type == 'code' ? 'selected' : '' }}>Code</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="form-group" id="type">
                </div>
            </div>

            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <select class="form-control form-select" name="status" required>
                                <option value="1" {{ $isEdit && $country->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $isEdit && $country->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12 mt-5">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" id='save-btn'></button>
            </div>
        </div>
    </div>
</form>

<script>
    $('#upload_type').on('change', function(event) {
        let option_selected = $("option:selected", this);
        let value_selected = this.value;
        let html = '';

        if (value_selected == 'upload') {
            html = `<label class="form-label" for="type_value">Upload Type</label>
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <input type="file" class="form-control" name="type_value" id="type_value">
                        </div>
                    </div>`;
        } else if (value_selected == 'url') {
            html = `<label class="form-label" for="type_value">Upload Type</label>
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <input type="text" class="form-control" name="type_value" placeholder="URL Here" id="type_value">
                        </div>
                    </div>`;
        } else if (value_selected == 'code') {
            html = `<label class="form-label" for="type_value">Upload Type</label>
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <textarea class="form-control" rows="3" name="type_value" placeholder="HTML Code Here" id="type_value"></textarea>
                        </div>
                    </div>`;
        }

        $('#type').html(html);
    });
</script>
