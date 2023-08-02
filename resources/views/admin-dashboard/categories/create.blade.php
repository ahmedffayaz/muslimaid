@php
    $isEdit = isset($category) ? true : false;
@endphp
<form action="{{ $isEdit ? route(getAdminPrefix() . '.categories.update', $category) : route(getAdminPrefix() . '.categories.store') }}" class="gy-3 is-alter" method="POST" enctype="multipart/form-data"
    id="category-form">
    @csrf
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="full-name-1">Category Name <span class="text-danger">*</span></label>
                <div class="form-control-wrap">
                    <input type="hidden" value="{{ $isEdit ? $category->id : '' }}" id="id">
                    <input type="text" class="form-control" id="full-name-1" name="name" value="{{ $isEdit ? $category->name : '' }}" required>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="parent_id">Parent Category <span class="text-danger"></span></label>
                <div class="form-control-wrap">

                    <select class="form-control get_parent" data-search="on" id="parent_id" name="parent_id">
                        <option value="0" selected>--Choose Category---</option>
                        @foreach ($categories as $parent)
                            <option value="{{ $parent->id }}" {{ $isEdit && $category->parent_id == $parent->id ? 'selected' : '' }} style="font-weight:bold">
                                {{ $parent->name }}
                            </option>
                            @if ($isEdit && count($parent->childs))
                                @include('admin-dashboard.categories.child_input', [
                                    'childs' => $parent->childs,
                                    'isEdit' => $isEdit,
                                    'category' => $category,
                                    'dashes' => '~',
                                ])
                            @elseif (count($parent->childs))
                                @include('admin-dashboard.categories.child_input', [
                                    'childs' => $parent->childs,
                                    'isEdit' => $isEdit,
                                    'dashes' => '~',
                                ])
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="visibility">Visibility</label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" name="visibility" id="visibility">
                            <option value="visible">Visible</option>
                            <option value="hidden" {{ $isEdit && $category->visibility == 'hidden' ? 'selected' : '' }}>
                                Hidden</option>
                            <option value="more" {{ $isEdit && $category->visibility == 'more' ? 'selected' : '' }}>
                                More</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6" style="margin-top: 5%;">
            <div class="form-group">
                <div class="custom-control custom-control-sm custom-checkbox notext">
                    <input type="checkbox" class="custom-control-input" name="is_map_enable" value="{{ $isEdit ?? $category->is_map_enable }}" id="is_map_enable"
                        {{ $isEdit && $category->is_map_enable == 1 ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_map_enable">Enable Google Map</label>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <input name="description" type="hidden">
                <label class="form-label" for="phone-no-1">Description</label>
                <!-- Create the editor container -->
                <div id="editor-container">{!! $isEdit ? $category->description : '' !!}</div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="logo_type">Logo Type </label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" name="logo_type" id='logo_type' required>
                            <option value="upload" {{ $isEdit && $category->logo_type == 'upload' ? 'selected' : '' }}>
                                Upload</option>
                            <option value="link" {{ $isEdit && $category->logo_type == 'link' ? 'selected' : '' }}>
                                Link</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 logo_link">
            <div class="form-group">
                <label class="form-label" for="logo_link">Logo Link @if($isEdit == false) <span class="text-danger">*</span> @endif </label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="logo_link" name="logo_link" value="{{ $isEdit ? $category->logo_link : '' }}" required
                        onchange="readLinkURL(this);">
                </div>
            </div>
            <div class="col-lg-3 ">
                <div class="form-group">
                    <div class="preview-wrapper">
                        @if ($isEdit && $category->logo_link)
                            <img id="logo_link-preview" src="{{ asset($category->logo_link) }}" style="max-height: 60px;max-width:60px" alt="">
                        @else
                            <img id="logo_link-preview" src="" alt="logo" class="d-none" style="max-height: 60px; max-width: 60px;" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 logo_upload">
            <div class="form-group">
                <label class="form-label" for="logo_upload">Logo Upload @if($isEdit == false) <span class="text-danger">*</span> @endif </label>
                <div class="form-control-wrap">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="logo_upload" id="logo_upload"
                            value="{{ $isEdit ? $category->logo_upload : '' }}"onchange="readBannerURL(this);">
                        <label class="custom-file-label" for="logo_upload">Choose file</label>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 ">
                <div class="form-group">
                    <div class="preview-wrapper">
                        @if ($isEdit && $category->logo_upload)
                            <img id="logo-preview" src="{{ asset($category->logo_upload) }}" style="max-height: 60px;max-width:60px" alt="">
                        @else
                            <img id="logo-preview" src="" alt="store logo" class="d-none" style="max-height: 60px; max-width: 60px;" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 ">
            <div class="form-group">
                <label class="form-label" for="banner_type">Banner Type </label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" name="banner_type" id='banner_type' required>
                            <option value="upload" {{ $isEdit && $category->banner_type == 'upload' ? 'selected' : '' }}>Upload</option>
                            <option value="link" {{ $isEdit && $category->banner_type == 'link' ? 'selected' : '' }}>Link</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 banner_link">
            <div class="form-group">
                <label class="form-label" for="banner_link">Banner Link  @if($isEdit == false) <span class="text-danger">*</span> @endif </label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="banner_link" name="banner_link" value="{{ $isEdit ? $category->banner_link : '' }}" required
                        onchange="readLinkURL(this);">

                </div>
            </div>
            <div class="col-lg-3 ">
                <div class="form-group">
                    <div class="preview-wrapper">
                        @if ($isEdit && $category->banner_link)
                            <img id="banner_link-preview" src="{{ asset($category->logo_link) }}" style="max-height: 60px;max-width:60px" alt="">
                        @else
                            <img id="banner_link-preview" src="" alt="store logo" class="d-none" style="max-height: 60px; max-width: 60px;" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 banner_upload">
            <div class="form-group">
                <label class="form-label" for="banner_upload">Banner Upload  @if($isEdit == false) <span class="text-danger">*</span> @endif </label>
                <div class="form-control-wrap">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="banner_upload" id="banner_upload"
                            value="{{ $isEdit ? $category->banner_upload : '' }}"onchange="readBannerURL(this);">
                        <label class="custom-file-label" for="banner_upload">Choose file</label>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 ">
                <div class="form-group">
                    <div class="preview-wrapper">
                        @if ($isEdit && $category->banner_upload)
                            <img id="banner-preview" src="{{ asset($category->banner_upload) }}" style="max-height: 60px;max-width:60px" alt="">
                        @else
                            <img id="banner-preview" src="" alt="store logo" class="d-none" style="max-height: 60px; max-width: 60px;" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="sort">Sort <em class="icon ni ni-question form-label" data-toggle="tooltip" data-placement="top"
                        title="Smallest number appear first."></em></label>
                <div class="form-control-wrap">
                    <input type="number" class="form-control" id="sort" name="sort" value="{{ $isEdit ? $category->sort : $sort }}">
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="default-06">Status <span class="text-danger">*</span></label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" id="default-06" name="status" required>
                            <option value="1" {{ $isEdit && $category->status == '1' ? 'selected' : '' }}>Active
                            </option>
                            <option value="0" {{ $isEdit && $category->status == '0' ? 'selected' : '' }}>
                                In-active</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>
        @if ($isEdit && $category->parent_id === 0)
            <div class="col-md-12">
                <label class="form-label" for="default-06">Tags</label>
                <div class="form-control-wrap ">
                    <div class="">
                        @php
                            $categoryTagsIds = $category
                                ->tags()
                                ->pluck('tag_id')
                                ->toArray();
                        @endphp
                        <select class="form-control form-select select-2" name="tags[]" id="tags" multiple>
                            @foreach ($tags as $tag)
                                <option @if (in_array($tag->id, $categoryTagsIds)) selected @endif value="{{ $tag->id }}">{{ $tag->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-lg-12">
            <div class="form-group">
                <label class="form-label" for="reviewer">Meta Description</label>
                <textarea class="form-control" name="meta_description" placeholder="Meta Description">{{ $isEdit ? $category->meta_description : '' }}</textarea>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label class="form-label" for="reviewer">Meta Keywords</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" name="meta_keyword" placeholder="Meta keyword" value="{{ $isEdit ? $category->meta_keyword : '' }}">
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label class="form-label" for="reviewer">Meta Title</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" name="meta_title" placeholder="Meta Title" value="{{ $isEdit ? $category->meta_title : '' }}">
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
