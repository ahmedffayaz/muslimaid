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
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <h4 class="title nk-block-title">Add Store</h4>
                                    <div class="nk-block-des">
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-inner">
                                    <div class="card-head">
                                        <h5 class="card-title">Store Info</h5>
                                    </div>
                                    <form action="{{ route(getAdminPrefix() . '.stores.store') }}" class="gy-3 form-validate store_form is-alter" method="POST">
                                        @csrf
                                        <div class="row g-4">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="store_name">Store Name <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="store_name" name="store_name" placeholder="Store name"
                                                            value="{{ old('store_name') }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="network_id">Network <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap ">
                                                        <select class="form-select form-control" id="network_id" name="network_id" required>
                                                            @foreach ($networks as $network)
                                                                <option value="{{ $network->id }}" {{ old('network_id') == $network->id ? 'selected' : '' }}>{{ $network->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="store_url">Store url <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="store_url" value="{{ old('store_url') }}" name="store_url"
                                                            placeholder="Store url" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="tracking_url">Tracking URL <span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="tracking_url" value="{{ old('tracking_url') }}" name="tracking_url"
                                                            placeholder="https://example.com/item/abc-id-1345" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="deeplink_url">Deeplink URL</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="deeplink_url" value="{{ old('deeplink_url') }}" name="deeplink_url"
                                                            placeholder="https://example.com/item/abc-id-1345">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <input name="description" type="hidden">
                                                    <label class="form-label" for="description">Description</label>
                                                    <!-- Create the editor container -->
                                                    <div id="editor-container">
                                                    </div>
                                                    @error('description')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
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
                        </div><!-- .nk-block -->
                    </div><!-- .components-preview -->
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function (){
            initializeTinyMCEEditor('editor-container');
        });

        var form = document.querySelector('form');
        $(".store_form").submit(function(e) {
            // Populate hidden form on submit
            var editor = tinymce.get('editor-container')
            var desc = document.querySelector('input[name=description]');
            desc.value = editor.getContent();
        });
    </script>

    <script>
        $('.form-validate').validate({
            errorClass: 'invalid-feedback d-block',
            rules: {
                store_name: {
                    required: true,
                },
                store_url: {
                    required: true,
                    url: true
                },
                tracking_url: {
                    required: true,
                    url: true
                },
                deeplink_url: {
                    url: true
                }
            },
            submitHandler: function(form) {
                if ($(form).valid())
                    form.submit();
                return false;
            }
        });
    </script>
@endpush
