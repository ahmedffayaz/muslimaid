@extends('layouts.admin-dashboard.app')
@section('pageTitle', 'Appeals')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Appeals</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have total {{$appeals->total()}} appeals.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt"><a href="{{route(getAdminPrefix() . '.appeals.create')}}" class="btn btn-primary btn-sm" ><em class="icon ni ni-plus"></em><span>Add Appeal</span></a></li>

                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="card card-preview mb-4">
                    <div class="card-inner">
                        <form action="#" class="is-alter search_form" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label" for="title">Search for Title</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="title" value="" name="title">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
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

                                <div class="col-4 align-self-end ml-auto">
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
                        <div class="card-inner-group" id="table-data">
                            @include('admin-dashboard.appeals.index_data')
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
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

        $(document).ready(function() {
            $(document).on('click', '.delete', function(event) {
                var form_id = $(this).attr('form_id');
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
@endpush
