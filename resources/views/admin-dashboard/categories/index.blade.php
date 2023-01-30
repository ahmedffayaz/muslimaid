@extends('layouts.admin-dashboard.app')
<style>
    .nk-tb-list {
        table-layout: fixed;
    }

    /* Remove default bullets */
    ul,
    #myUL {
        list-style-type: none;
    }

    /* Remove margins and padding from the parent ul */
    #myUL {
        margin: 0;
        padding: 0;
    }

    /* Style the caret/arrow */
    .caret {
        cursor: pointer;
        user-select: none;
        /* Prevent text selection */
    }

    /* Create the caret/arrow with a unicode, and style it */
    .caret::before {
        content: "\25B6";
        color: black;
        display: inline-block;
        margin-right: 6px;
    }

    /* Rotate the caret/arrow icon when clicked on (using JavaScript) */
    .caret-down::before {
        transform: rotate(90deg);
    }

    /* Hide the nested list */
    .nested {
        display: none;
    }

    /* Show the nested list when the user clicks on the caret/arrow (with JavaScript) */
    .active {
        display: block;
    }
</style>
@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Categories</h3>
                                <div class="nk-block-des text-soft">
                                    <p>You have total {{ count($categories) }} categories.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#"
                                        class="btn btn-icon btn-trigger toggle-expand mr-n1"data-target="pageMenu">
                                        <em class="icon ni ni-menu-alt-r"></em>
                                    </a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <a href="{{ route('admin.categories.create') }}"
                                                    class="btn btn-primary btn-sm">
                                                    <em class="icon ni ni-plus"></em>
                                                    <span>Add Category</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.categories.export') }}" id="export"
                                                    class="btn btn-success btn-sm" class="btn btn-white btn-outline-light">
                                                    <em class="icon ni ni-download-cloud"></em>
                                                    <span>Export</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="card card-preview mb-4">
                        <div class="card-inner">
                            <div id="accordion-1" class="accordion accordion-s2">
                                <div class="accordion-item">
                                    <a href="#" class="accordion-head collapsed" data-toggle="collapse"
                                        data-target="#accordion-item-1-1">
                                        <h6 class="title">Search</h6>
                                        <span class="accordion-icon"></span>
                                    </a>
                                    <div class="accordion-body collapse" id="accordion-item-1-1" data-parent="#accordion-1">
                                        <div class="accordion-inner">
                                            <div>
                                                <form action="" class="form-validate is-alter search_form"
                                                    method="POST">
                                                    @csrf
                                                    <div class="row g-4 justify-content-md-center">
                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <div class="form-control-wrap ">
                                                                    <label class="form-label" for="title">Category
                                                                        Title</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="text" class="form-control"
                                                                            id="title" value="" name="title">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label class="form-label" for="parent_id">Parent</label>
                                                                <div class="form-control-wrap ">
                                                                    <select class="form-select form-control"
                                                                        data-search="on" id="parent_id" name="parent_id">
                                                                        <option value="0">All</option>
                                                                        @foreach ($store_categories as $category)
                                                                            <option value="{{ $category->id }}">
                                                                                {{ $category->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 align-self-end">
                                                            <div class="form-group">
                                                                <button type="submit"
                                                                    class="btn btn-success btn-block">Search</button>
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
                    @include('flash::message')
                    <div class="nk-block">
                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner px-0">
                                    <div class="nk-tb-list nk-tb-ulist" id="table-data">
                                        @include('admin-dashboard.categories.index_data')
                                    </div><!-- .nk-tb-list -->
                                </div><!-- .card-inner -->
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
        $(document).ready(function() {
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var route = $('.pagination').attr('route');
                var page = $(this).attr('href').split('page=')[1];

                if (route == 'index') {
                    pageurl = "{{ route('admin.categories.fetch') }}?page="
                    var _token = $("input[name=_token]").val();
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
                        }
                    });
                }

                if (route == 'search') {
                    var _token = $("input[name=_token]").val();
                    var parent_id = $("select[name=parent_id]").val();
                    var title = $("input[name=title]").val();
                    $.ajax({
                        url: '{{ route('admin.categories.search_categories') }}?page=' + page,
                        method: "POST",
                        data: {
                            _token: _token,
                            parent_id: parent_id,
                            title: title
                        },
                        success: function(data) {
                            $('#table-data').html(data);
                            $('html, body').animate({
                                scrollTop: 0
                            }, 'slow');
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            $(document).on('submit', '.search_form', function(event) {
                event.preventDefault();
                var _token = $("input[name=_token]").val();
                var parent_id = $("select[name=parent_id]").val();
                var title = $("input[name=title]").val();
                $.ajax({
                    url: '{{ route('admin.categories.search_categories') }}',
                    method: "POST",
                    data: {
                        _token: _token,
                        parent_id: parent_id,
                        title: title
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
