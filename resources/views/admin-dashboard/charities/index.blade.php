@extends('layouts.admin-dashboard.app')
@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Charities</h3>
                                <div class="nk-block-des text-soft">
                                    <p>You have total {{ $charities->total() }} charities.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt"><a href="{{ route('admin.charities.create') }}" class="btn btn-primary btn-sm"><em
                                                        class="icon ni ni-plus"></em><span>Add Charity</span></a></li>
                                            <li class="nk-block-tools-opt"><a href="#add-charity-modal" class="btn btn-primary btn-sm" data-toggle="modal"><em
                                                        class="icon ni ni-plus"></em><span>Create Charity Type</span></a>
                                            </li>
                                            <li class="nk-block-tools-opt"><a href="{{ route('admin.charities.charity_type_view') }}" class="btn btn-primary btn-sm"><em
                                                        class="icon ni ni-plus"></em><span>View Charity Type</span></a></li>

                                            {{-- <li><a href="{{route('admin.stores.export')}}" id="export"
                                                class="btn btn-success btn-sm"
                                                class="btn btn-white btn-outline-light"><em
                                                    class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                        --}}
                                        </ul>
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="card card-preview mb-4">
                        <div class="card-inner">
                            <form action="{{ route('admin.charities.search') }}" class="is-alter search_form" method="POST">
                                @csrf
                                <div class="row g-4">
                                    {{-- <div class="col-lg-2"></div> --}}
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label" for="title">Search for Title</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="title" value="" name="title">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label" for="charity_types_id">All Charity Type</label>
                                            <div class="form-control-wrap ">
                                                <select class="form-select form-control" data-search="on" id="charity_types_id" name="charity_types_id">
                                                    <option value="0">Any</option>
                                                    @foreach ($charitiestypes as $charity)
                                                        <option value="{{ $charity->id }}">{{ $charity->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label class="form-label" for="status">Status</label>
                                            <div class="form-control-wrap ">
                                                <select class="form-select form-control" data-search="on" id="status" name="status">
                                                    <option value="-1">Any</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">In-active</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label class="form-label" for="country">All Country</label>
                                            <div class="form-control-wrap ">
                                                <select class="form-select form-control" data-search="on" id="country" name="country">
                                                    {{-- <option value="0">Any</option>
                                                @foreach ($charities as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach --}}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2 align-self-end ml-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success btn-block">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    @include('flash::message')
                    <div class="nk-block">
                        <div class="card card-stretch">
                            <div class="card-inner-group">

                                <div class="card-inner px-0">
                                    <div class="nk-tb-list nk-tb-ulist" id="table-data">

                                        @include('admin-dashboard.charities.index_data')

                                    </div><!-- .nk-tb-list -->
                                </div><!-- .card-inner -->

                            </div><!-- .card-inner-group -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                    <!-- Add Charity Modal -->
                    <div class="modal fade" tabindex="-1" id="add-charity-modal">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">

                                <div class="modal-header align-center">
                                    <div class="nk-file-title">

                                        <div class="nk-file-name">
                                            <div class="nk-file-name-text"><span class="title">Add Charity Type</span></div>
                                        </div>
                                    </div>
                                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                                </div>
                                <div class="p-4">
                                    <form action="{{ route('admin.admin-charities-store') }}" class="gy-3 form-validate is-alter " id="add-charity-form" method="POST">
                                        @csrf
                                        <div class="row g-4">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label">Title</label>
                                                    <div class="form-control-wrap">
                                                        <input id="title" type="text" class="form-control" name="title" placeholder="Title"
                                                            value="{{ old('title') }}" required>
                                                        @error('title')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="status">Status</label>
                                                    <div class="form-control-wrap ">
                                                        <div class="form-control-select">
                                                            <select class="form-control" id="status" name="status" required>
                                                                <option value="1">Active</option>
                                                                <option value="0">In-active</option>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#add-charity-form').on('submit', function(event) {
            event.preventDefault();
            let btn = $('#save-btn')
            btn.attr('disabled', 'disabled')
                .append('<span class="spinner-border spinner-border-sm ml-1" role="status"></span>');
            let url = "{{ route('admin.admin-charities-store') }}";
            let method = 'POST';
            let form = $('#add-charity-form');
            let formData = new FormData(form[0]);
            $.ajax({
                url: url,
                type: method,
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    $('#add-charity-modal').modal('hide');
                    form.trigger('reset');
                    btn.removeAttr('disabled', 'disabled').button('refresh');
                },
                error: function(error) {

                    btn.removeAttr('disabled', 'disabled').button('refresh');
                    btn.children().remove('span.spinner-border.spinner-border-sm.ml-1').button('refresh');
                }
            });
        });

        $('.form-validate').validate({
            errorClass: 'invalid-feedback d-block',
            rules: {
                title: {
                    required: true,

                },
                status: {
                    required: true,

                },
            },
            submitHandler: function(form) {
                if ($(form).valid())
                    form.submit();
                return false;
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete', function(event) {
                var form_id = $(this).attr('form_id');
                console.log(form_id)
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then(function(result) {
                    if (result.value) {
                        $('#' + form_id).submit();
                    }
                });
                event.preventDefault();
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            $(document).on('submit', '.search_form', function(event) {
                event.preventDefault();

                $('#table-data').html(`<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
        </div></div>`);

                var _token = $("input[name=_token]").val();
                var title = $("input[name=title").val();
                var charity_types_id = $("select[name=charity_types_id]").val();
                var status = $("select[name=status").val();
                var country = $("select[name=country").val();
                $.ajax({
                    url: '{{ route('admin.charities.search') }}',
                    method: "POST",
                    data: {
                        _token: _token,
                        title: title,
                        charity_types_id: charity_types_id,
                        country: country,
                        status: status
                    },
                    success: function(data) {
                        $('#table-data').html(data);
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                    }
                });

            });

        });
    </script>
@endpush
