@php
    $isEdit = isset($slide) ? true : false;
@endphp
<form action="{{ $isEdit ? route('admin.slides.update', $slide) : route('admin.slides.store') }}" id="form-validate" class="gy-3 is-alter category_form" method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
    @method('PUT')
    @endif
    <input type="hidden" value="{{ $isEdit ? $slide->id : null }}" id="slide_id" name="slide_id">
    <input type="hidden" value="{{ $slider }}" id="slider_id" name="slider_id">
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="form-group">
                <label class="form-label" for="name">Slide name</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="name" name="name" value="{{ $isEdit ?  $slide->name : '' }}" required>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="slider_type">Slider Type</label>
                <div class="form-control-wrap ">
                    <div class="">
                        <select class="form-control form-select" id="slider_type" name="slider_type" required data-search="on">
                            <option value="link"    {{ ($isEdit && $slide->slider_type == 'link') ? 'selected' : '' }}>Link</option>
                            <option value="store"   {{ ($isEdit && $slide->slider_type == 'store') ? 'selected' : '' }}>Store</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 store">
            <div class="form-group">
                <label class="form-label" for="store_id">Store</label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-select select-2" name="store_id" id="store_id"  data-search="on">
                            <option value="">Select Store</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}" {{ ($isEdit && $slide->store_id == $store->id) ? 'selected' : '' }}>{{ $store->id }} - {{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 sliderlink">
            <div class="form-group">
                <label class="form-label" for="link">Link</label>
                <input name="link" type="text" id="link" class="form-control" value="{{ $isEdit ? $slide->link : '' }}" required>

            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <label class="form-label" for="phone-no-1">Description</label>
                <textarea name="description" class="form-control" rows="5">{{$isEdit ? $slide->description : ''}}</textarea>

            </div>
        </div>

        <div class="col-lg-6 logo_upload">
            <div class="form-group">
                <label class="form-label" for="logo">Logo</label>
                <div class="form-control-wrap">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name='logo' id="logo">
                        <label class="custom-file-label" for="logo">Choose file</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 banner_upload">
            <div class="form-group">
                <label class="form-label" for="banner">Banner</label>
                <div class="form-control-wrap">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="banner" id="banner">
                        <label class="custom-file-label" for="banner">Choose file</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-primary">Save</button>
            </div>
        </div>
    </div>
</form>
