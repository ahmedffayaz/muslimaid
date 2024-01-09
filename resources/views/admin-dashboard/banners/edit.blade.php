@php
    $isEdit = isset($banner) ? true : false;
@endphp
<form action="{{ $isEdit ? route(getAdminPrefix() . '.banners.update', $banner) : route(getAdminPrefix() . '.banners.store') }}" class="gy-3 is-alter banner-form" method="POST"
    enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="full-name-1">Banner Name <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="hidden" value="{{ $isEdit ? $banner->id : '' }}" id="id">
                    <input type="text" class="form-control" id="full-name-1" name="name" value="{{ $isEdit ? $banner->name : '' }}" required>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="type">Type <span class="text-danger">*</span></label>
                <div class="form-control-wrap ">
                    <select class="form-control select2" name="type" id="type" required>
                        <option value="">Choose banner type</option>
                        <option value="referral_banner" {{ $isEdit && $banner->type == 'referral_banner' ? 'selected' : '' }}>Referral Banner</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="banner_type">Banner Type <span class="text-danger">*</span></label>
                <div class="form-control-wrap ">
                    <select class="form-control select2" name="banner_type" id="banner_type" required>
                        <option value="upload" {{ $isEdit && $banner->banner_type == 'upload' ? 'selected' : '' }}>Upload</option>
                        <option value="link" {{ $isEdit && $banner->banner_type == 'link' ? 'selected' : '' }}>Link</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-6 banner_link">
            <div class="form-group">
                <label class="form-label" for="banner_link">Banner Link @if ($isEdit == false)
                        <span class="text-danger">*</span>
                    @endif </label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="banner_link" name="banner_link" value="{{ $isEdit ? $banner->banner_link : '' }}" required
                        onchange="readLinkURL(this);">
                </div>
            </div>
            <div class="col-lg-3 ">
                <div class="form-group">
                    <div class="preview-wrapper">
                        @if ($isEdit && $banner->banner_link)
                            <img id="banner_link-preview" src="{{ $banner->banner_link }}" style="max-height: 60px;max-width:60px" alt="">
                        @else
                            <img id="banner_link-preview" src="" alt="banner" class="d-none" style="max-height: 60px; max-width: 60px;" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 banner_upload">
            <div class="form-group">
                <label class="form-label" for="banner_upload">banner Upload @if ($isEdit == false)
                        <span class="text-danger">*</span>
                    @endif </label>
                <div class="form-control-wrap">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="banner_upload" id="banner_upload"
                            value="{{ $isEdit ? $banner->banner_upload : '' }}"onchange="readBannerURL(this);">
                        <label class="custom-file-label" for="banner_upload">Choose file</label>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 ">
                <div class="form-group">
                    <div class="preview-wrapper">
                        @if ($isEdit && $banner->banner_upload)
                            <img id="banner-preview" src="{{ asset('storage/' . $banner->banner_upload) }}" style="max-height: 60px;max-width:60px" alt="">
                        @else
                            <img id="banner-preview" src="" alt="banner" class="d-none" style="max-height: 60px; max-width: 60px;" />
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="default-06">Status <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <select class="form-control select2" id="default-06" name="status" required>
                        <option value="active" {{ $isEdit && $banner->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="disabled" {{ $isEdit && $banner->status == 'disabled' ? 'selected' : '' }}>Disabled</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn-lg btn-primary" id="save-btn"></button>
            </div>
        </div>
    </div>
</form>
