@php
    $isEdit = isset($slide) ? true : false;
@endphp
<form action="{{ $isEdit ? route('admin.slides.update', $slide) : route('admin.slides.store') }}" id="form-validate" class="gy-3 is-alter category_form" method="POST"
    enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif
    <input type="hidden" value="{{ $isEdit ? $slide->id : $slider }}" id="slide_id" name="slide_id">
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="form-group">
                <label class="form-label" for="name">Slide name <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="name" name="name" value="{{ $isEdit ? $slide->name : '' }}" required>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="slider_type">Slider Type <span class="text-danger">*</span></label>
                <div class="form-control-wrap ">
                    <div class="">
                        <select class="form-control form-select select-2" id="slider_type" name="slider_type" required data-search="on">
                            <option value="link" {{ $isEdit && $slide->slider_type == 'link' ? 'selected' : '' }}>Link
                            </option>
                            <option value="store" {{ $isEdit && $slide->slider_type == 'store' ? 'selected' : '' }}>Store</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 store">
            <div class="form-group">
                <label class="form-label" for="store_id">Store <span class="text-danger">*</span></label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-select select-2" name="store_id" id="store_id" data-search="on">
                            <option value="">Select Store</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}" {{ $isEdit && $slide->store_id == $store->id ? 'selected' : '' }}>
                                    {{ $store->id }} -
                                    {{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 sliderlink">
            <div class="form-group">
                <label class="form-label" for="link">Link <span class="text-danger">*</span></label>
                <input name="link" type="text" id="link" class="form-control" value="{{ $isEdit ? $slide->link : '' }}" required>

            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <label class="form-label" for="phone-no-1">Description</label>
                <textarea name="description" class="form-control" rows="5">{{ $isEdit ? $slide->description : '' }}</textarea>

            </div>
        </div>
        <div class="col-lg-6 logo_upload">
            <div class="form-group">
                <label class="form-label">Logo</label>
                <div class="input-group">
                    <span class="input-group-btn">
                        <a  data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white lfm">
                            <i class="fa fa-picture-o"></i> Choose
                        </a>
                    </span>
                    <input id="thumbnail" class="form-control" type="text" name="logo">
                </div>
                <div id="holder" style="margin-top:15px;max-height:100px;"></div>
            </div>
        </div>
        <div class="col-lg-6 banner_upload">
            <div class="form-group">
                <label class="form-label">Banner</label>
                <div class="input-group">
                    <span class="input-group-btn">
                        <a  data-input="thumbnail2" data-preview="holder2" class="btn btn-primary text-white lfm">
                            <i class="fa fa-picture-o"></i> Choose
                        </a>
                    </span>
                    <input id="thumbnail2" class="form-control" type="text" name="banner">
                </div>
                <div id="holder2" style="margin-top:15px;max-height:100px;"></div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-primary">Save</button>
            </div>
        </div>
    </div>
</form>
