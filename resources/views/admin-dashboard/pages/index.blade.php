@extends('layouts.admin-dashboard.app')
@section('pageTitle', 'Pages')

@section('content')
    <div class="nk-content ">
        <div class="container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">

                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Pages</h3>
                                <div class="nk-block-des text-soft">
                                    <p>You have total {{ $count }} pages.</p>
                                </div>
                            </div>

                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <a href="{{ route(getAdminPrefix() . '.pages.create') }}" class="btn btn-primary btn-sm">
                                                    <em class="icon ni ni-plus"></em><span>Add Page</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card card-preview mb-4">
                        <div class="card-inner">
                            <form action="" class="form-validate is-alter search_form" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="page-title">Page Title</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="page-title" value="" name="page_title">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="page-type">Page Type</label>
                                            <div class="form-control-wrap ">
                                                <select class="form-select form-control" data-search="on" id="page-type" name="page_type">
                                                    <option value="0">All</option>
                                                    <option value="system">System</option>
                                                    <option value="special">Special</option>
                                                    <option value="general">General</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="status">Status</label>
                                            <div class="form-control-wrap ">
                                                <select class="form-control form-select" id="status" name="status" required>
                                                    <option value="-1">Any</option>
                                                    <option value="active">Active</option>
                                                    <option value="in-active">In-active</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 align-self-end col-md-12">
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
                        <div class="card">
                            <div class="card-inner-group" id="table-data">
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
        $(document).ready(function (){
            $('#table-data').html(spinner(true));
            var token = "{{ csrf_token() }}"
            $.ajax({
                url: "{{ route(getAdminPrefix(). '.pages.fetch') }}",
                method: "POST",
                data: {
                    _token: token,
                },
                success: function(response){
                    $('#table-data').html(response);
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                    NioApp.BS.tooltip('[data-toggle="tooltip"]');
                }
            })
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var route = $('.pagination').attr('route');
                var page = $(this).attr('href').split('page=')[1];
                if (route == 'index') {

                    $('#table-data').html(spinner(true));

                    pageurl = "{{ route(getAdminPrefix() . '.pages.fetch') }}?page="
                    var _token = "{{ csrf_token() }}"
                    $.ajax({
                        url: pageurl + page,
                        method: "POST",
                        data: {
                            _token: _token,
                            page: page
                        },
                        success: function(data) {
                            $('#table-data').html(data);
                            $('html, body').animate({
                                scrollTop: 0
                            }, 'slow');
                            NioApp.BS.tooltip('[data-toggle="tooltip"]');
                        }
                    });
                }
                if (route == 'search') {
                    $('#table-data').html(spinner(true));

                    var _token = "{{ csrf_token() }}";
                    var page_title = $("input[name=page_title]").val();
                    var page_type = $("select[name=page_type]").val();
                    var status = $("select[name=status]").val();
                    $.ajax({
                        url: "{{ route(getAdminPrefix() . '.pages.fetch') }}?page=" + page,
                        method: "POST",
                        data: {
                            _token: _token,
                            page_title: page_title,
                            page_type: page_type,
                            status: status
                        },
                        success: function(data) {
                            $('#table-data').html(data);
                            $('html, body').animate({
                                scrollTop: 0
                            }, 'slow');
                            NioApp.BS.tooltip('[data-toggle="tooltip"]');
                        }
                    });
                }
            });
            $(document).on('submit', '.search_form', function(event) {
                event.preventDefault();

                $('#table-data').html(spinner(true));

                var _token = $("input[name=_token]").val();
                var page_title = $("input[name=page_title]").val();
                var page_type = $("select[name=page_type]").val();
                var status = $("select[name=status]").val();
                $.ajax({
                    url: "{{ route(getAdminPrefix() . '.pages.fetch') }}",
                    method: "POST",
                    data: {
                        _token: _token,
                        page_title: page_title,
                        page_type: page_type,
                        status: status
                    },
                    success: function(data) {
                        $('#table-data').html(data);
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                        NioApp.BS.tooltip('[data-toggle="tooltip"]');
                    }
                });
            });

            $(document).on('click', '.delete', function(event) {
                event.preventDefault();

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
            });
        });

        jQuery.validator.addMethod("regex", function(value, element) {
            return this.optional(element) || /^[\w. ]+$/i.test(value);
        }, "Letters, numbers, and underscores only please");

        $('#form-validate').validate({
            rules: {
                title: {
                    required: true,
                    regex: true
                }
            }
        });
    </script>
@endpush
