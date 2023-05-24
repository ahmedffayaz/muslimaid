@extends('layouts.admin-dashboard.app')
@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="components-preview wide-md mx-auto">
                        <div class="nk-block nk-block-lg">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h4 class="title nk-block-title">Edit Store</h4>
                                    <div class="nk-block-des">
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-inner">
                                    <div class="card-head">
                                        <h5 class="card-title">Store Info</h5>
                                    </div>
                                    <form action="{{ route(getAdminPrefix() . '.stores.update', $store) }}" id="store_form" class="gy-3 form-validate is-alter" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row gy-4">
                                            <div class="col-lg-4 mx-auto">
                                                <div class="form-group">
                                                    <div class="text-center mb-4 logo">
                                                        <label for="logo-input">
                                                            <img id="blah" src="{{ $logoUrl }}" alt="store logo" class="rounded-circle" width="150px" />
                                                            <input id="logo-input" name="image" class="d-none" type='file' onchange="readURL(this);" />
                                                            <div class="edit"><em class="icon ni ni-edit"></em> Change logo</div>
                                                        </label><br>
                                                        <label class="form-label">Store logo</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-4">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="full-name-1">Store Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="full-name-1" value="{{ $store->name }}" name="store_name" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="default-06">Network</label>
                                                    <div class="form-control-wrap ">
                                                        <div class="form-control-select">
                                                            <select class="form-control" id="default-06" name="network_id" required>
                                                                @foreach ($networks as $network)
                                                                    <option @if ($store->network_id == $network->id) selected @endif value="{{ $network->id }}">{{ $network->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="pay-amount-1">Cashback</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="pay-amount-1" value="{{ $store->cashback->sale_commission }}"
                                                            name="store_cashback" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <input name="description" type="hidden">
                                                    <label class="form-label" for="phone-no-1">Description</label>
                                                    <!-- Create the editor container -->
                                                    <div id="editor-container">
                                                        {!! $store->description !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="default-06">Category</label>
                                                    <div class="form-control-wrap ">
                                                        <div class="form-control-select">
                                                            <select class="form-select" multiple="multiple" data-placeholder="Select Multiple options" class="form-control"
                                                                id="default-06" name="category_id[]" required>
                                                                @foreach ($categories as $category)
                                                                    <option @if (in_array($category->id, $store->categories->pluck('id')->toArray())) selected @endif value="{{ $category->id }}">{{ $category->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="phone-no-1">Tracking url</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="phone-no-1" value="{{ $store->cashback->click_url }}" name="tracking_url"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="pay-amount-1">Store url</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="pay-amount-1" value="{{ $store->store_url }}" name="store_url"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="default-06">Status</label>
                                                    <div class="form-control-wrap ">
                                                        <div class="form-control-select">
                                                            <select class="form-control" id="default-06" name="status" required>
                                                                <option @if ($store->status == 1) selected @endif value="1">Active</option>
                                                                <option @if ($store->status == 0) selected @endif value="0">In-active</option>
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
                        </div><!-- .nk-block -->
                    </div><!-- .components-preview -->
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <link rel="stylesheet" href="{{ asset('admin-dashboard/css/editors/quill.css?ver=2.2.0') }}">
    <script src="{{ asset('admin-dashboard/js/libs/editors/quill.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('admin-dashboard/js/editors.js?ver=2.2.0') }}"></script>
    <script>
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

        //   var form = document.querySelector('form');
        $("#store_form").submit(function(e) {
            // Populate hidden form on submit
            var desc = document.querySelector('input[name=description]');
            desc.value = quill.root.innerHTML;
        });
    </script>
    <script>
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
    </script>
@endpush
