<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div wire:loading class="centered">
                    <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div wire:loading.remove class="components-preview wide-md mx-auto">
                    <div class="nk-block-head nk-block-head-lg pb-2">
                        <div class="nk-block-between d-block d-md-flex">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title fw-normal"><a href="{{ route('store.show', $store->slug) }}" target="_blank" class="a_link">{{ $store->name }}</a>
                                    <span class="badge badge-dim badge-pill badge-outline-primary">{{ $store->network->name }}</span>
                                </h3>
                                <div class="store_id_checker d-none">{{ $store->id }}</div>
                                <div class="nk-block-des">
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="form-group stores">
                                    <label class="form-label" for="default-06"></label>
                                    <div class="form-control-wrap ">
                                        <div class="">
                                            <select class="form-control form-select  select-2" data-search="on" name="open_store" id="store_select" required>
                                                @foreach ($stores as $st)
                                                    <option @if ($st->id == $store->id) selected @endif value="{{ $st->slug }}">{{ $st->id }} -
                                                        {{ $st->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div>
                    </div>
                    @include('flash::message')
                    <div class="nk-block nk-block-lg">
                        <div class="card card-preview">
                            <div class="card-inner">
                                <ul class="nav nav-tabs mt-n3">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tabItem4"><em class="icon ni ni-file-text"></em><span>Details</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem5"><em class="icon ni ni-file-text"></em><span>Categories</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem6"><em class="icon ni ni-link"></em><span>Cashbacks</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem7"><em class="icon ni ni-money"></em><span>Vouchers</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem8"><em class="icon ni ni-notice"></em><span>Reviews</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem9"><em class="icon ni ni-img-fill"></em><span>Images</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem10"><em class="icon ni ni-map-pin"></em><span>Address</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem11"><em class="icon ni ni-external"></em><span>SEO</span></a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabItem4">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                                <h5 class="title">Store Information</h5>
                                            </div><!-- .nk-block-head -->
                                            <form action="{{ route('admin.stores.update', $store) }}" id="store_form" class="gy-3 form-validate is-alter" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="row g-4">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="store_name">Store Name <span class="text-danger">*</span></label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="store_name" value="{{ $store->name }}" name="store_name"
                                                                    required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="slug" value="{{ $store->slug }}" name="slug"
                                                                    required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="card">
                                                            <input name="description" type="hidden">
                                                            <label class="form-label" for="description">Description</label>
                                                            <!-- Create the editor container -->
                                                            <div id="editor-container">
                                                                {!! $store->description !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="card">
                                                            <input name="terms_conditions" type="hidden">
                                                            <label class="form-label" for="terms_conditions">Terms & Conditions</label>
                                                            <!-- Create the editor container -->
                                                            <div id="teditor-container">
                                                                {!! $store->terms_conditions !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="preview-block">
                                                            <span class="preview-title form-label">Override Network <em class="icon ni ni-question" data-toggle="tooltip"
                                                                    data-placement="top" title="If checked, store cashback level network settings will be used."></em></span>
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" class="custom-control-input" id="store_override_network"
                                                                    name="store_override_network" @if ($store->override_network == 1) checked @endif>
                                                                <label class="custom-control-label" for="store_override_network"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 sote-override-network"
                                                        @if ($store->override_network) style="display: none;" @else style="display: block;" @endif>
                                                        <div class="form-group">
                                                            <label class="form-label" for="default-06">Network <span class="text-danger">*</span></label>
                                                            <div class="form-control-wrap ">
                                                                <div class="">
                                                                    <select class="form-control form-select select-2" id="" name="network_id" required>
                                                                        @foreach ($networks as $network)
                                                                            <option @if ($store->network_id == $network->id) selected @endif value="{{ $network->id }}">
                                                                                {{ $network->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 sote-override-network"
                                                        @if ($store->override_network) style="display: none;" @else style="display: block;" @endif>
                                                        <div class="form-group">
                                                            <label class="form-label" for="tracking_url">Tracking URL <span class="text-danger">*</span></label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="tracking_url" value="{{ $store->tracking_url }}"
                                                                    name="tracking_url" placeholder="https://example.com/item/abc-id-1345" required style="width: 79%">
                                                                <span style="position: absolute; right:0; top:5px; width:20%" data-toggle="tooltip" data-placement="left"
                                                                    title="This parameter containing ID of the click will be concatenated with tracking URL of the store ({{ $store->tracking_url }})">{{ $store->network->click_ref }}XXX</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 sote-override-network"
                                                        @if ($store->override_network) style="display: none;" @else style="display: block;" @endif>
                                                        <div class="form-group">
                                                            <label class="form-label" for="tracking_url">Deeplink URL</label>
                                                            <div class="form-control-wrap">
                                                                <span style="position:absolute; left:0; top:5px; width:7%" data-toggle="tooltip" data-placement="right"
                                                                    title="This parameter containing URL of the click will be concatenated with deeplink URL of the store ({{ $store->deeplink_url }})">{{ $store->network->deeplink_identifier }}</span>
                                                                <input type="text" class="form-control" id="deeplink_url" value="{{ $store->deeplink_url }}"
                                                                    name="deeplink_url" placeholder="https://example.com/item/abc-id-1345"
                                                                    style="position: relative; left:30px; width: 93%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="store_url">Store url <span class="text-danger">*</span></label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="store_url" value="{{ $store->store_url }}"
                                                                    name="store_url" placeholder="https://example.com/item/abc-id-1345" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="custom_cashback_percentage">Override Cashback Percentage <em
                                                                    class="icon ni ni-question" data-toggle="tooltip" data-placement="top"
                                                                    title="Define custom cashback percentage for this store, keep empty to use global setting"></em></label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="custom_cashback_percentage"
                                                                    value="{{ $store->custom_cashback_percentage }}" name="custom_cashback_percentage">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label" for="default-06">Tags</label>
                                                        <div class="form-control-wrap ">
                                                            <div class="">
                                                                @php 
                                                                    $storeTagsIds = $store->tags()->pluck('tag_id')->toArray();
                                                                @endphp
                                                                <select class="form-control form-select select-2" name="tags[]" multiple>
                                                                    @foreach($tags as $tag)
                                                                        <option @if (in_array($tag->id, $storeTagsIds)) selected @endif value="{{$tag->id}}">{{ $tag->title }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="default-06">Status @if ($store->status == 'error')
                                                                    (<span class="text-danger"> Error: missing {{ $store->status_description }}</span>)
                                                                @endif
                                                            </label>
                                                            <div class="form-control-wrap ">
                                                                <div class="">
                                                                    <select class="form-control form-select select-2" name="status" required>
                                                                        <option @if ($store->status == 'pending review') selected @endif value="pending review">Pending Review
                                                                        </option>
                                                                        <option @if ($store->status == 'active') selected @endif value="active">Active</option>
                                                                        <option @if ($store->status == 'disabled') selected @endif value="disabled">Disabled</option>
                                                                        <option @if ($store->status == 'closed') selected @endif value="closed">Closed at Network</option>
                                                                        <option @if ($store->status == 'error') selected @endif value="error">Error</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-lg btn-primary">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tabItem5">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                                <form action="{{ route('admin.stores.override_categories', $store) }}" id="override-categories-form"
                                                    class="gy-3 form-validate is-alter" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row g-4">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="override-categories" name="override_categories"
                                                                        value="1" @if ($store->override_categories == 1) checked @endif>
                                                                    <label class="custom-control-label" for="override-categories">Override Categories <em
                                                                            class="icon ni ni-question" data-toggle="tooltip" data-placement="top"
                                                                            title="If checked categories will be overriden when importer runs next time"></em></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <h5 class="title mt-3">Categories</h5>
                                                <form action="{{ route('admin.stores.categories.update') }}" id="store_cat_form" class="gy-3 form-validate is-alter"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="store_id" value="{{ $store->id }}">
                                                    <div class="col-12">
                                                        <ul class="categories">
                                                            @foreach ($categories as $parent)
                                                                <li class="custom-control custom-control-sm custom-checkbox d-block">
                                                                    <input @if (in_array($parent->id, $store->categories->pluck('id')->toArray())) checked @endif type="checkbox" class="custom-control-input"
                                                                        name="category_id[]" id="{{ $parent->name }}_{{ $parent->id }}" value="{{ $parent->id }}">
                                                                    <label class="custom-control-label"
                                                                        for="{{ $parent->name }}_{{ $parent->id }}">{{ $parent->name }}</label>
                                                                    @if (count($parent->childs))
                                                                        @include('admin-dashboard.stores.child_cat_input', ['childs' => $parent->childs])
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-lg btn-primary">Update</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tabItem6">
                                        <form action="{{ route('admin.stores.override_cashback', $store) }}" id="override-cashback-form"
                                            class="gy-3 form-validate is-alter" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="row g-4">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox mr-3">
                                                            <input type="checkbox" class="custom-control-input" id="override-cashback" name="override_cashback"
                                                                value="1" @if ($store->override_cashback == 1) checked @endif>
                                                            <label class="custom-control-label" for="override-cashback">Override Cashback <em class="icon ni ni-question"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title=" If checked, admin will be in charge, cashbacks will not auto-update."></em></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <h5 class="title mb-4 d-inline-block mt-3">Cashbacks</h5>
                                        <span id='cashbacks-data' class="mt-4"></span>
                                    </div>
                                    <div class="tab-pane" id="tabItem7">
                                        <h5 class="title mb-4  d-inline-block">Vouchers</h5>
                                        <span id='vouchers-data' class="mt-4"></span>
                                    </div>
                                    <div class="tab-pane" id="tabItem8">
                                        <h5 class="title mb-4 d-inline">Reviews</h5>
                                        <span id='reviews-data'></span>
                                    </div>
                                    <div class="tab-pane" id="tabItem9">
                                        <span id="images-data"></span>
                                    </div>
                                    <div class="tab-pane" id="tabItem10">
                                        <h5 class="title mb-4  d-inline-block">Address</h5>
                                        <span id="address-data" class="mt-4"></span>
                                    </div>
                                    <div class="tab-pane" id="tabItem11">
                                        <h5 class="title mb-4  d-inline-block">SEO Rules</h5>
                                        <span id="seo-data" class="mt-4"></span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- .card-preview -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- @@ File Upload Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="file-upload">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Upload image for {{ $store->name }}</span></div>
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <form action="{{ route('admin.stores.images.upload', $store) }}" class="form-validate file-upload" method="POST" enctype="multipart/form-data">
                <div class="modal-body modal-body-md">
                    @csrf
                    <div class="row gy-4">
                        <div class="col-lg-12 mx-auto">
                            <div class="form-group">
                                <div class="text-center mb-4 logo">
                                    <label for="logo-input">
                                        <img id="blah" src="{{ asset('admin-dashboard/images/cloud-uploading.png') }}" alt="store logo" width="150px" />
                                        <input id="logo-input" name="image" class="d-none" type='file' onchange="readURL(this);" required />
                                        <br> <br><span>Click here to select image <span class="text-danger">*</span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label" for="title">Type</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" id="title" name="title" required>
                                            <option value="logo">Logo</option>
                                            <option value="Cover">Cover</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-stretch bg-light">
                    <div class="modal-footer-between">
                        <div class="g"></div>
                        <div class="g">
                            <ul class="btn-toolbar g-3">
                                <li><a href="#file-share" data-dismiss="modal"class="btn btn-outline-light btn-white">Cancel</a></li>
                                <li><button type="submit" class="btn btn-primary file-dl-toast">Upload</button></li>
                            </ul>
                        </div>
                    </div>
                </div><!-- .modal-footer -->
            </form>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->
<!-- @@ Voucher Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="voucher-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Edit Voucher</span></div>
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="voucher" class=" p-4">
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->

<!-- Add Voucher Modal -->
<div class="modal fade" tabindex="-1" id="add-voucher-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Add Voucher</span></div>
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="add-voucher-form" class=" p-4">
                <form action="{{ route('admin.vouchers.store') }}" class="gy-3 form-validate is-alter add_voucher_form" id="add_voucher_validation" method="POST">
                    @csrf
                    <input type="hidden" name="store_id" value="{{ $store->id }}">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="full-name-1">Title <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="full-name-1" name="link_name" value="{{ old('link_name') }}" required>
                                    @error('link_name')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="vsale_commission">Sale commission <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="number" min="1" step="1" class="form-control" id="vsale_commission" name="sale_commission"
                                        value="{{ old('sale_commission') }}" required>
                                    @error('vsale_commission')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="click_url">Click url <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="url" class="form-control" id="click_url" name="click_url" value="{{ old('click_url') }}" required>
                                    @error('click_url')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="phone-no-1">Destination url <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="url" class="form-control" id="phone-no-1" name="destination" value="{{ old('destination') }}" required>
                                    @error('destination')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <input name="description" type="hidden">
                                <label class="form-label" for="phone-no-1">Description</label>
                                <textarea name="description" class="form-control "> {{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="form-label" for="promotion_type">Promotion Type <span class="text-danger">*</span></label>
                                    <div class="form-control-wrap ">
                                        <select class="form-select form-control select-2" data-search="on" id="promotion_type" name="promotion_type"
                                            value="{{ old('promotion_type') }}" required>
                                            <option value="Coupon">Coupon</option>
                                            <option value="Sale/Discount">Sale/Discount</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 coupon-div">
                            <div class="form-group">
                                <label class="form-label" for="coupon_code">Coupon Code <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="coupon_code" name="coupon_code" value="{{ old('coupon_code') }}" required>
                                    @error('coupon_code')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="promotion_start_date">Promotion Start Date <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control date-picker promotion_start_date" value="{{ old('promotion_start_date') }}"
                                        id="promotion_start_date" name="promotion_start_date" autocomplete="off" required>
                                    @error('promotion_start_date')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="promotion_end_date">Promotion End Date <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control date-picker promotion_end_date" value="{{ old('promotion_end_date') }}"
                                        id="promotion_end_date" name="promotion_end_date" autocomplete="off" required>
                                    @error('promotion_end_date')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
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
            </div>
        </div>
    </div>
</div>
<!-- .modal -->

<!-- @@ Edit Cashback Modal @e -->
<div class="modal fade cashback-modal" tabindex="-1" role="dialog" id="edit-cashback-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title"></span></div>
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="edit-cashback" class=" p-4">
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->

<!-- @@ Add Cashback Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="cashback-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Add Cashback</span></div>
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="cashback" class=" p-4">
                <form action="{{ route('admin.stores.cashbacks.store') }}" class="gy-3 form-validate is-alter cashback_form_add" method="POST">
                    @csrf
                    <input type="hidden" name="store_id" value="{{ $store->id }}">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="type">Type <span class="text-danger">*</span></label>
                                <div class="form-control-wrap ">
                                    <select class="form-control form-select select-2" id="type" name="type" required>
                                        <option value="percentage">Percentage</option>
                                        <option value="fixed">Fixed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="sale_commission">Commission <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="number" class="form-control" min="0" step="0.01" id="sale_commission" value="" name="sale_commission"
                                        required>
                                </div>
                            </div>
                        </div>
                        @if ($store->override_network)
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="network_id">Network</label>
                                    <div class="form-control-wrap">
                                        <select class="form-control form-select select-2" id="network_id" name="network_id">
                                            @foreach ($networks as $network)
                                                <option value="{{ $network->id }}">{{ $network->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="tracking_url">Tracking URL</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="tracking_url" name="tracking_url"
                                            placeholder="https://example.com/item/abc-id-1345" required style="width: 83%">
                                        <span style="position: absolute; right:0; top:5px; width:17%" data-toggle="tooltip" data-placement="left"
                                            title="This parameter containing ID of the click will be concatenated with tracking URL of the store (https://example.com?ref=XXX)">{{ $store->network->click_ref }}XXX</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="sale_commission">Deeplink URL</label>
                                    <div class="form-control-wrap">
                                        <span style="position:absolute; left:0; top:5px; width:3%" data-toggle="tooltip" data-placement="right"
                                            title="This parameter containing URL of the click will be concatenated with deeplink URL of the store (&u=https://example.com)">{{ $store->network->deeplink_identifier }}</span>
                                        <input type="text" class="form-control" id="deeplink_url"
                                            value="{{ isset($store->cashback->deeplink_url) ? $store->cashback->deeplink_url : '' }}" name="deeplink_url"
                                            placeholder="https://example.com/item/abc-id-1345" style="position: relative; left:30px; width: 95%">
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label" for="cashback_icon">Icon Upload</label>
                                <div class="form-control-wrap">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="cashback_icon" id="cashback_icon" onchange="readURL(this);">
                                        <label class="custom-file-label" for="cashback_icon">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="preview-wrapper">
                                        <img id="logo-preview" src="" alt="store logo" class="d-none" style="max-height: 60px; max-width: 60px;" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <label class="form-label" for="phone-no-1">Detail</label>
                                <textarea name="detail" class="form-control "></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->

<!-- @@ Review Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="review-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Review</span></div>
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="review" class=" p-4">
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->

<!-- @@ Review Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="add-review-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Review</span></div>
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="" class=" p-4">
                <form action="{{ route('admin.reviews.store') }}" class="gy-3 form-validate is-alter review_form_add" method="POST">
                    @csrf
                    <input type="hidden" name="store_id" value="{{ $store->id }}">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="reviewer">Reviewer Name</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="reviewer" name="reviewer" value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="rating">Rating</label>
                                <div class="form-control-wrap ">
                                    <select class="form-select form-control" id="rating" name="rating" required>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <input name="review" type="hidden">
                                <label class="form-label" for="phone-no-1">Review</label>
                                <!-- Create the editor container -->
                                <div id="reditor-container">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="default-06">Status</label>
                                <div class="form-control-wrap ">
                                    <select class="form-select form-control" data-search="on" id="default-06" name="status" required>
                                        <option value="active">Active</option>
                                        <option value="in-active">In-active</option>
                                    </select>
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
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->

<!-- @@ Add SEO Rule Modal @e -->
<div class="modal fade" tabindex="-1" id="add-seorule-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Add SEO Rule</span></div>
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="add-seorule-form" class=" p-4">
                <form action="{{ route('admin.stores.save_seo_rule') }}" class="gy-3 form-validate is-alter add_seorule_form" id="theForm" method="POST">
                    @csrf
                    <input type="hidden" name="store_id" value="{{ $store->id }}">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label" for="full-name-1">Key <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <select class="form-control key" id="key" name="key" required>
                                        <option selected disabled>Select Key</option>
                                        <option value="meta:keywords">Meta:keywords</option>
                                        <option value="meta:description">Meta:description</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 key_value">
                            <div class="form-group">
                                <label class="form-label" for="vsale_commission">Value <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <textarea class="form-control" id="value" value="" name="value" required></textarea>
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
            </div>
        </div>
    </div>
</div><!-- .modal -->

<!-- @@ Edit SEO Rule Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="seo-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Edit SEO Rule</span></div>
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="seo" class=" p-4">
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->

<!-- @@ Add Address Modal @e -->
<div class="modal fade" tabindex="-1" id="add-address-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Add Address</span></div>
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="add-address-form" class=" p-4">
                <form action="{{ route('admin.stores.save_address') }}" class="gy-3 form-validate is-alter add_address_form" method="POST">
                    @csrf
                    <input type="hidden" name="store_id" value="{{ $store->id }}">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="full-name-1">City <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="city" name="city" value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="vsale_commission">Postal code <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="postal_code" name="postal_code" value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="latitude">Latitude <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="number" class="form-control" min="-90" max="90" step="any" id="latitude" name="latitude"
                                        value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="longitude">Longitude <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <input type="number" class="form-control" min="-180" max="180" step="any" id="longitude" name="longitude"
                                        value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label" for="vsale_commission">Address <span class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <textarea class="form-control" id="address" value="" name="address" required></textarea>
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
            </div>
        </div>
    </div>
</div><!-- .modal -->

<!-- @@ Edit Address Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="edit-address-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Edit Address</span></div>
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="edit_address" class=" p-4">
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->

<script>
    document.addEventListener('livewire:load', function() {
        fetchSeoRules();
        fetchVouchers();
        fetchCashbacks();
        fetchReviews();
        fetchImages();
        fetchAddress();
        initializeSelect2();
    })
</script>

@push('scripts')
    <script>
        function fetchVouchers() {
            pageurl = "{{ route('admin.stores.vouchers') }}"
            var _token = $("input[name=_token]").val();
            var store = $('.store_id_checker').text();
            $.ajax({
                url: pageurl,
                method: "POST",
                data: {
                    _token: _token,
                    store: store
                },
                success: function(data) {
                    $('#vouchers-data').html(data);
                }
            });
        }

        function fetchCashbacks() {
            pageurl = "{{ route('admin.stores.cashbacks') }}"
            var _token = $("input[name=_token]").val();
            var store = $('.store_id_checker').text();
            $.ajax({
                url: pageurl,
                method: "POST",
                data: {
                    _token: _token,
                    store: store
                },
                success: function(data) {
                    $('#cashbacks-data').html(data);
                }
            });
        }

        function fetchReviews() {
            pageurl = "{{ route('admin.stores.reviews') }}"
            var _token = $("input[name=_token]").val();
            var store = $('.store_id_checker').text();
            $.ajax({
                url: pageurl,
                method: "POST",
                data: {
                    _token: _token,
                    store: store
                },
                success: function(data) {
                    $('#reviews-data').html(data);
                }
            });
        }

        function fetchImages() {
            pageurl = "{{ route('admin.stores.fetchimages') }}"
            var _token = $("input[name=_token]").val();
            var store = $('.store_id_checker').text();
            $.ajax({
                url: pageurl,
                method: "POST",
                data: {
                    _token: _token,
                    store: store
                },
                success: function(data) {
                    $('#images-data').html(data);
                }
            });
        }

        function fetchAddress() {
            pageurl = "{{ route('admin.stores.storeaddress') }}"
            var _token = $("input[name=_token]").val();
            var store = $('.store_id_checker').text();
            $.ajax({
                url: pageurl,
                method: "POST",
                data: {
                    _token: _token,
                    store: store
                },
                success: function(data) {
                    $('#address-data').html(data);
                }
            });
        }

        function fetchSeoRules() {
            pageurl = "{{ route('admin.stores.fetchseorules') }}"
            var _token = $("input[name=_token]").val();
            var store = $('.store_id_checker').text();
            $.ajax({
                url: pageurl,
                method: "POST",
                data: {
                    _token: _token,
                    store: store
                },
                success: function(data) {
                    $('#seo-data').html(data);
                }
            });
        }
    </script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            window.livewire.on('storeChange', () => {
                fetchVouchers();
                fetchCashbacks();
                fetchReviews();
                fetchImages();
                fetchAddress();
                fetchSeoRules();
                initializeSelect2();
                var quill = new Quill('#editor-container', {
                    modules: {
                        toolbar: [
                            ['bold', 'italic'],
                            ['link', 'blockquote', 'code-block', 'image'],
                            [{
                                list: 'ordered'
                            }, {
                                list: 'bullet'
                            }]
                        ]
                    },
                    placeholder: 'Compose an epic...',
                    theme: 'snow'
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#store_select').on('change', function(e) {
                livewire.emit('changeEvent', e.target.value)
            });
        });
    </script>
    <link rel="stylesheet" href="{{ asset('admin-dashboard/css/editors/quill.css?ver=2.2.0') }}">
    <script src="{{ asset('admin-dashboard/js/libs/editors/quill.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('admin-dashboard/js/editors.js?ver=2.2.0') }}"></script>

    <script>
        // Quill Editor
        var quill = new Quill('#editor-container', {
            modules: {
                toolbar: [
                    ['bold', 'italic'],
                    ['link', 'blockquote', 'code-block', 'image'],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }]
                ]
            },
            placeholder: 'Compose an epic...',
            theme: 'snow'
        });
        // Quill Editor
        var rquill = new Quill('#reditor-container', {
            modules: {
                toolbar: [
                    ['bold', 'italic'],
                    ['link', 'blockquote', 'code-block', 'image'],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }]
                ]
            },
            placeholder: 'Compose an epic...',
            theme: 'snow'
        });
        // Quill Editor
        var quill = new Quill('#teditor-container', {
            modules: {
                toolbar: [
                    ['bold', 'italic'],
                    ['link', 'blockquote', 'code-block', 'image'],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }]
                ]
            },
            placeholder: 'Compose an epic...',
            theme: 'snow'
        });
        $("#store_form").submit(function(e) {
            // Populate hidden form on submit
            var desc = document.querySelector('input[name=description]');
            desc.value = quill.root.innerHTML;
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(150);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        // Edit Voucher popup
        $(document).ready(function() {
            $(document).on('click', '.voucher-edit', function(event) {
                event.preventDefault();
                var id = $(this).attr('voucher-id');
                pageurl = "vouchers/" + id + "/edit";
                store_editor = 1;
                var _token = $("input[name=_token]").val();
                $.ajax({
                    url: pageurl,
                    method: "GET",
                    data: {
                        _token: _token,
                        store_editor: store_editor
                    },
                    success: function(data) {
                        $('#voucher-modal').modal('show');
                        $('#voucher').html(data);
                        $('.select-2').each(function() {
                            initializeSelect2($(this));
                        });
                        $('#voucher').find(".promotion_end_date").datepicker();
                        console.log($('#voucher').find(".promotion_end_date").datepicker());
                        $('#voucher').find(".promotion_start_date").datepicker();
                        checkVoucherType();
                        attachFormValidator($(document).find('#model_edit'));
                    }
                });
            });
        });

        function attachFormValidator(form) {
            jQuery.validator.addMethod("minValue", function(value, element, param) {
                return this.optional(element) || value >= param;
            }, "Value must be equal to or greater than {0}.");
            form.validate({
                rules: {
                    promotion_start_date: {
                        required: true,
                    },
                    promotion_end_date: {
                        required: true,
                    },
                    click_url: {
                        required: true,
                        url: true
                    },
                    destination: {
                        required: true,
                        url: true
                    },
                    sale_commission: {
                        required: true,
                        minValue: 1,
                    }
                },
            });

        }


        // Edit SEO
        $(document).on('click', '.seo-edit', function(event) {
            event.preventDefault();
            var id = $(this).attr('seo-id');
            pageurl = "stores/seo/" + id + ""
            store_editor = 1;
            var _token = $("input[name=_token]").val();
            $.ajax({
                url: pageurl,
                method: "GET",
                data: {
                    _token: _token,
                    store_editor: store_editor
                },
                success: function(data) {
                    $('#seo-modal').modal('show');
                    $('#seo').html(data);
                }
            });

        });

        // Voucher update
        $(document).ready(function() {
            $(document).on('submit', '.voucher_form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: "PUT",
                    data: $(this).serialize(),
                    success: function(data) {
                        if (data.success) {
                            $('#voucher-modal').modal('hide');
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(data.message, 'success');
                                $('#custom_cashback_percentage').val(data['percentage']);
                            })(NioApp, jQuery);
                            fetchVouchers();
                        } else {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                if (data.errors && Object.values(data.errors).length > 0) {
                                    NioApp.Toast(Object.values(data.errors)[0], 'error');
                                } else {
                                    NioApp.Toast(data['message'], 'error');
                                }
                            })(NioApp, jQuery);
                        }
                    },
                    error: function(error) {
                        if (error.responseJSON.error) {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(error.responseJSON.error, 'error');
                            })(NioApp, jQuery);
                        } else {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(Object.values(error.responseJSON.errors)[0], 'error');
                            })(NioApp, jQuery);
                        }
                    }
                });
            });
        });

        $(document).ready(function() {
            $(document).on('submit', '.seo_form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: "PUT",
                    data: $(this).serialize(),
                    success: function(data) {
                        if (data.success) {
                            $('#seo-modal').modal('hide');
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(data.message, 'success');
                            })(NioApp, jQuery);
                            fetchSeoRules();
                        } else {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                if (data.errors && Object.values(data.errors).length > 0) {
                                    NioApp.Toast(Object.values(data.errors)[0], 'error');
                                } else {
                                    NioApp.Toast(data['message'], 'error');
                                }
                            })(NioApp, jQuery);
                        }
                    },
                    error: function(error) {
                        if (error.responseJSON.error) {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(error.responseJSON.error, 'error');
                            })(NioApp, jQuery);
                        } else {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(Object.values(error.responseJSON.errors)[0], 'error');
                            })(NioApp, jQuery);
                        }
                    }

                });
            });
        });

        // Store lat long address edit
        $(document).on('click', '.address-edit', function(event) {
            event.preventDefault();
            var id = $(this).attr('address-id');
            pageurl = "stores/address/" + id + ""
            store_editor = 1;
            var _token = $("input[name=_token]").val();
            $.ajax({
                url: pageurl,
                method: "GET",
                data: {
                    _token: _token,
                    store_editor: store_editor
                },
                success: function(data) {
                    $('#edit-address-modal').modal('show');
                    $('#edit_address').html(data);
                    editAddress()
                }
            });

        });

        function editAddress() {
            $('.address_form').validate({
                errorClass: 'invalid-feedback d-block',
                rules: {
                    city: {
                        required: true,
                    },
                    postal_code: {
                        required: true,
                    },
                    latitude: {
                        required: true,
                    },
                    longitude: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                }
            });
        }

        // Update store address
        $(document).ready(function() {
            $(document).on('submit', '.address_form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: "PUT",
                    data: $(this).serialize(),
                    success: function(data) {
                        $('#edit-address-modal').modal('hide');
                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            NioApp.Toast(data.message, 'success');
                        })(NioApp, jQuery);
                        fetchAddress();
                    },
                    error: function(error) {
                        if (error.responseJSON.error) {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(error.responseJSON.error, 'error');
                            })(NioApp, jQuery);
                        } else {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(Object.values(error.responseJSON.errors)[0], 'error');
                            })(NioApp, jQuery);
                        }
                    }
                });
            });
        });

        // Add Voucher
        $(document).ready(function() {
            $(document).on('submit', '.add_voucher_form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(data) {
                        if (data.success) {
                            $('#add-voucher-modal').modal('hide');
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(data.message, 'success');
                                $('#custom_cashback_percentage').val(data['percentage']);
                            })(NioApp, jQuery);
                            fetchVouchers();
                        } else {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                if (data.errors && Object.values(data.errors).length > 0) {
                                    NioApp.Toast(Object.values(data.errors)[0], 'error');
                                } else {
                                    NioApp.Toast(data['message'], 'error');
                                }
                            })(NioApp, jQuery);
                        }
                    },
                    error: function(error) {
                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            NioApp.Toast('An error occurred.', 'error');
                        })(NioApp, jQuery);
                    }

                });
            });
        });


        // Edit Cashback Popup
        $(document).ready(function() {
            $(document).on('click', '.cashback-edit', function(event) {
                event.preventDefault();
                var id = $(this).attr('cashback-id');
                pageurl = 'stores/cashbacks/' + id + '/edit';
                var _token = $("input[name=_token]").val();
                $.ajax({
                    url: pageurl,
                    method: "GET",
                    data: {
                        _token: _token
                    },
                    success: function(data) {
                        $('.cashback-modal').modal('show');
                        $('#edit-cashback').html(data);
                        if ($('#store_override_network').is(":checked")) {
                            $('.sote-override-network').hide();
                            $('.network_url').css('cssText', 'display: block !important');
                        } else {
                            $('.network_url').css('cssText', 'display: none !important');
                            $('.sote-override-network').show();
                        }
                        checkCashbackType();
                        calcCashback();
                        NioApp.BS.tooltip('[data-toggle="tooltip"]');

                    }
                });
            });
        });



        // Cashback Update
        $(document).ready(function() {
            $(document).on('submit', '.cashback_form', function(event) {
                event.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $('.cashback-modal').modal('hide');
                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            NioApp.Toast(data.message, 'success');
                            $('#custom_cashback_percentage').val(data['percentage']);
                        })(NioApp, jQuery);
                        fetchCashbacks();
                    },
                    error: function(error) {
                        if (error.responseJSON.error) {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(error.responseJSON.error, 'error');
                            })(NioApp, jQuery);
                        } else {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(Object.values(error.responseJSON.errors)[0], 'error');
                            })(NioApp, jQuery);
                        }
                    }
                });
            });
        });

        // Add Cashback Form
        $(document).ready(function () {
            $(document).on('click', '.add-cashbacks', function (event) {
                event.preventDefault();
                let storeId = "{{ encrypt($store->id) }}";
                $.ajax({
                    url: "{{ route('admin.stores.cashbacks.create') }}",
                    type: "GET",
                    data: { storeId: storeId },
                    success: function (data) {
                        $('.cashback-modal').modal('show');
                        $('#edit-cashback').html(data);
                        if ($('#store_override_network').is(":checked")) {
                            $('.sote-override-network').hide();
                            $('.network_url').css('cssText', 'display: block !important');
                        } else {
                            $('.network_url').css('cssText', 'display: none !important');
                            $('.sote-override-network').show();
                        }
                        checkCashbackType();
                        calcCashback();
                        NioApp.BS.tooltip('[data-toggle="tooltip"]');
                    }
                });
            });
        });

        // Review Edit Popup
        $(document).ready(function() {
            $(document).on('click', '.review-edit', function(event) {
                event.preventDefault();
                var id = $(this).attr('review-id');
                pageurl = 'stores/reviews/' + id + '/edit';
                var _token = $("input[name=_token]").val();
                $.ajax({
                    url: pageurl,
                    method: "GET",
                    data: {
                        _token: _token
                    },
                    success: function(data) {
                        $('#review-modal').modal('show');
                        $('#review').html(data);
                        var rquill = new Quill('#reditor-container', {
                            modules: {
                                toolbar: [
                                    ['bold', 'italic'],
                                    ['link', 'blockquote', 'code-block', 'image'],
                                    [{
                                        list: 'ordered'
                                    }, {
                                        list: 'bullet'
                                    }]
                                ]
                            },
                            placeholder: 'Compose an epic...',
                            theme: 'snow'
                        });
                    }
                });
            });
        });

        // Review Update
        $(document).ready(function() {
            $(document).on('submit', '.review_form', function(event) {
                event.preventDefault();
                var editor = document.querySelector('#reditor-container')
                var desc = document.querySelector('input[name=review]');
                desc.value = editor.children[0].innerHTML
                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(data) {
                        if (data.success) {
                            $('#review-modal').modal('hide');
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast('Review Updated Successfully.', 'success');
                            })(NioApp, jQuery);
                            fetchReviews();
                        } else {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                if (data.errors && Object.values(data.errors).length > 0) {
                                    NioApp.Toast(Object.values(data.errors)[0], 'error');
                                } else {
                                    NioApp.Toast(data['message'], 'error');
                                }
                            })(NioApp, jQuery);
                        }
                    },
                    error: function(error) {
                        if (error.responseJSON.error) {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(error.responseJSON.error, 'error');
                            })(NioApp, jQuery);
                        } else {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(Object.values(error.responseJSON.errors)[0], 'error');
                            })(NioApp, jQuery);
                        }
                    }
                });
            });
        });

        // Add Review
        $(document).ready(function() {
            $(document).on('submit', '.review_form_add', function(event) {
                event.preventDefault();
                var editor = document.querySelector('#reditor-container')
                var desc = document.querySelector('input[name=review]');
                desc.value = editor.children[0].innerHTML
                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(data) {
                        $('#review-modal').modal('hide');
                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            NioApp.Toast('Review Added Successfully.', 'success');
                        })(NioApp, jQuery);
                        fetchReviews();
                    }
                });
            });
        });

        // Upload Image
        $(document).ready(function() {
            $(document).on('submit', '.file-upload', function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#file-upload').modal('hide');
                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            NioApp.Toast(data.message, data.updated);
                        })(NioApp, jQuery);
                        fetchImages();
                        $('.file-upload').trigger("reset");
                        $('#blah').attr("src", "{{ asset('admin-dashboard/images/cloud-uploading.png') }}");
                    },
                    error: function(error) {
                        if (error.responseJSON.error) {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(error.responseJSON.error, 'error');
                            })(NioApp, jQuery);
                        } else {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(Object.values(error.responseJSON.errors)[0], 'error');
                            })(NioApp, jQuery);
                        }
                    }
                });
            });
        });

        // Delete Image
        $(document).ready(function() {
            $(document).on('click', '.delete-img', function(event) {
                var storeimage = $(this).attr('image-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then(function(result) {
                    if (result.value) {
                        pageurl = 'stores/images/delete/' + storeimage;
                        var _token = $("input[name=_token]").val();
                        $.ajax({
                            url: pageurl,
                            method: "GET",
                            data: {
                                _token: _token
                            },
                            success: function(data) {
                                fetchImages();
                            }
                        });
                        Swal.fire('Deleted!', 'Image has been deleted.', 'success');
                    }
                });
                event.preventDefault();
            });
        });

        //delete seo rule for store
        $(document).ready(function() {
            $(document).on('click', '.delete-seo', function(event) {
                var seoid = $(this).attr('seo_delete-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then(function(result) {
                    if (result.value) {
                        pageurl = 'stores/seo/delete/' + seoid;
                        var _token = $("input[name=_token]").val();
                        $.ajax({
                            url: pageurl,
                            method: "GET",
                            data: {
                                _token: _token
                            },
                            success: function(data) {
                                fetchSeoRules();
                            }
                        });
                        Swal.fire('Deleted!', 'SEO rule has been deleted.', 'success');
                    }
                });
                event.preventDefault();
            });
        });

        //delete Store Address
        //delete seo rule for store
        $(document).ready(function() {
            $(document).on('click', '.delete-address', function(event) {
                var addressid = $(this).attr('address-delete-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then(function(result) {
                    if (result.value) {
                        pageurl = 'stores/address/delete/' + addressid;
                        var _token = $("input[name=_token]").val();
                        $.ajax({
                            url: pageurl,
                            method: "GET",
                            data: {
                                _token: _token
                            },
                            success: function(data) {
                                fetchAddress();
                            }
                        });
                        Swal.fire('Deleted!', 'Address has been deleted.', 'success');
                    }
                });
                event.preventDefault();
            });
        });

        // Update Store
        $(document).ready(function() {
            $(document).on('submit', '#store_form', function(event) {
                event.preventDefault();
                var editor = document.querySelector('#editor-container');
                var desc = document.querySelector('input[name=description]');
                desc.value = editor.children[0].innerHTML;
                var editor = document.querySelector('#teditor-container');
                var desc = document.querySelector('input[name=terms_conditions]');
                desc.value = editor.children[0].innerHTML;
                $.ajax({
                    type: 'PUT',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function(data) {
                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            NioApp.Toast(data.message, 'success');
                        })(NioApp, jQuery);
                    },
                    error: function(error) {
                        if (error.responseJSON.error) {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(error.responseJSON.error, 'error');
                            })(NioApp, jQuery);
                        } else {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(Object.values(error.responseJSON.errors)[0], 'error');
                            })(NioApp, jQuery);
                        }
                    }
                });
            });
        });
        $('#store_form').validate({
            errorClass: 'invalid-feedback d-block',
            rules: {
                tracking_url: {
                    url: true
                },
                deeplink_url: {
                    url: true
                },
                store_url: {
                    url: true
                },
                description: {
                    maxlength: 255,
                },
                terms_conditions: {
                    maxlength: 255
                }
            }
        });
        // Update categories
        $(document).ready(function() {
            $(document).on('submit', '#store_cat_form', function(event) {
                event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function(data) {
                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            NioApp.Toast('Store categories updated Successfully.', 'success');
                        })(NioApp, jQuery);
                    },
                    error: function(data) {
                        console.log("error");
                        console.log(data);
                    }
                });
            });
        });

        //add address form
        $(document).ready(function() {
            $(document).on('submit', '.add_address_form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(data) {
                        $('#add-address-modal').modal('hide');
                        $('.add_address_form').trigger('reset');
                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            NioApp.Toast(data.message, 'success');
                        })(NioApp, jQuery);
                        fetchAddress();
                    },
                    error: function(error) {
                        if (error.responseJSON.error) {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(error.responseJSON.error, 'error');
                            })(NioApp, jQuery);
                        } else {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(Object.values(error.responseJSON.errors)[0], 'error');
                            })(NioApp, jQuery);
                        }
                    }
                });
            });
        });

        $(document).ready(function() {
            const form = $('#theForm');
            $(document).on('submit', '.add_seorule_form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(data) {
                        if (data.success) {
                            form.trigger('reset');
                            $('#add-seorule-modal').modal('hide');
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(data.message, 'success');
                            })(NioApp, jQuery);
                            fetchSeoRules();
                        } else {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                if (data.errors && Object.values(data.errors).length > 0) {
                                    NioApp.Toast(Object.values(data.errors)[0], 'error');
                                } else {
                                    NioApp.Toast(data['message'], 'error');
                                }
                            })(NioApp, jQuery);
                        }
                    },
                    error: function(error) {
                        if (error.responseJSON.error) {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(error.responseJSON.error, 'error');
                            })(NioApp, jQuery);
                        } else {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(Object.values(error.responseJSON.errors)[0], 'error');
                            })(NioApp, jQuery);
                        }
                    }

                })
            });
        });
        // });

        // Categories child parent selector
        $('input[type="checkbox"]').change(function(e) {
            var checked = $(this).prop("checked"),
                container = $(this).parent(),
                siblings = container.siblings();

            container.find('input[type="checkbox"]').prop({
                indeterminate: false,
                checked: checked
            });

            function checkSiblings(el) {
                var parent = el.parent().parent(),
                    all = true;

                el.siblings().each(function() {
                    let returnValue = all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
                    return returnValue;
                });

                if (all && checked) {

                    parent.children('input[type="checkbox"]').prop({
                        indeterminate: false,
                        checked: checked
                    });

                    checkSiblings(parent);

                } else if (all && !checked) {
                    parent.children('input[type="checkbox"]').prop("checked", checked);
                    parent.children('input[type="checkbox"]').prop("checked", (parent.find('input[type="checkbox"]:checked').length > 0));
                    checkSiblings(parent);
                } else {
                    el.parents("li").children('input[type="checkbox"]').prop({
                        checked: checked
                    });
                }
            }

            checkSiblings(container);
        });

        // Cashback Type checker
        function checkCashbackType() {
            if ($('#type').val() == 'fixed') {
                $('.currency-div').show();
                $('#currency').attr('required', 'required');
            } else {
                $('.currency-div').hide();
                $('#currency').removeAttr('required').val('');
            }
                $('#currency option:first-child').prop('selected', true);
        }

        $(document.body).on("change", "#type", function() {
            checkCashbackType();
        });

        // Select first option by default
        $(document).ready(function() {
            $('#currency option:first-child').prop('selected', true);
        });

        // Override Categories
        $("#override-categories").change(function() {
            var value = $(this).val();
            $.ajax({
                type: 'PUT',
                url: $('#override-categories-form').attr('action'),
                data: $('#override-categories-form').serialize(),
                success: function(data) {
                    (function(NioApp, $) {
                        'use strict';
                        toastr.clear();
                        NioApp.Toast('Store setting updated Successfully.', 'success');
                    })(NioApp, jQuery);
                },
                error: function(data) {
                    console.log("error");
                    console.log(data);
                }
            });
        });

        // Override cashback
        $("#override-cashback").change(function() {
            var value = $(this).val();
            $.ajax({
                type: 'PUT',
                url: $('#override-cashback-form').attr('action'),
                data: $('#override-cashback-form').serialize(),
                success: function(data) {
                    (function(NioApp, $) {
                        'use strict';
                        toastr.clear();
                        NioApp.Toast('Store setting updated Successfully.', 'success');
                    })(NioApp, jQuery);
                    fetchCashbacks();
                },
                error: function(data) {
                    console.log("error");
                    console.log(data);
                }
            });
        });

        // Cashback and Percentage calculator for edit cashback
        function calcPercentage() {
            var network_commission = $('#sale_commission').val();
            var cashback = $('#cashback').val();
            var percentage = ((cashback / network_commission) * 100).toFixed(2);
            $('#custom_cashback_percentagee').val(percentage);
            $('#custom_cashback_percentage').val(percentage);
        }

        function calcCashback() {
            var percentage = $('#custom_cashback_percentagee').val();
            var network_commission = $('#sale_commission').val();
            var cashback = ((percentage / 100) * network_commission).toFixed(2);
            $('#cashback').val(cashback);
        }

        // Voucher type checker
        function checkVoucherType() {
            if ($('#promotion_type').val() == 'Coupon') {
                $('.coupon-div').show();
                $('#coupon_code').attr('required', 'required');
            } else {
                $('.coupon-div').hide();
                $('#coupon_code').removeAttr('required').val('');
            }
        }

        $(document.body).on("change", "#promotion_type", function() {
            checkVoucherType()
        });

        function initializeSelect2() {
            $('.select-2').select2({
                placeholder: function() {
                    $(this).data('placeholder');
                }
            });
        }

        $(document.body).on("change", ".key", function() {
            let html = '';
            $('.key_value').html('');
            html = `<div class="form-group">
                    <label class="form-label" for="vsale_commission">Value</label>
                    <div class="form-control-wrap">
                        <textarea class="form-control" id="value" value="" name="value" required></textarea>
                    </div>
                </div>`;
            $('.key_value').append(html);
        });

        $(document.body).on("change", "#store_override_network", function() {
            if ($('#store_override_network').is(":checked")) {
                $('.sote-override-network').hide();
            } else {
                $('.sote-override-network').show();
            }
            $("#store_form").submit();
        });
    </script>
    
@endpush
