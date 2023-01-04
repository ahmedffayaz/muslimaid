@extends('layouts.admin-dashboard.app')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Categories <span class="badge badge-dim badge-pill badge-outline-primary">{{$network->name}}</span></h3>
                                <div class="nk-block-des text-soft">
                                    <p>You have total {{count($categories)}} categories.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            @if (strpos(strtolower($network->name), 'awin') !== false)
                                            <li>
                                                <a href="javascript:void(0);" class="btn btn-warning btn-sm" class="btn btn-white btn-outline-light" data-toggle="modal" data-target="#import-categories">
                                                    <em class="icon ni ni-upload"></em>
                                                    <span>Import Categories</span>
                                                </a>
                                            </li>
                                            @endif
                                            <li>
                                                <a href="{{route('admin.networks.index')}}" class="btn btn-primary btn-sm" class="btn btn-white btn-outline-light">
                                                    <span>Back</span>
                                                </a>
                                            </li>
                                            {{-- <li><a href="{{route('admin.users.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li> --}}
                                        </ul>
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    @include('flash::message')

                    <div class="nk-block">
                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner">
                                    <h5 class="title mb-3">All Categories</h5>
                                    <ul id="tree1">
                                        <div class="row">
                                            <div class="col-md-5">Name</div>
                                            <div class="col-md-5">Mapped to</div>
                                            <div class="col-md-2 text-right">Actions</div>
                                        </div>
                                        @foreach($categories as $importedcategory)
                                        <li>
                                            <div class="row  border mt-2 py-1">
                                                <div class="col-md-5">
                                                    <em class="icon ni ni-db-fill text-primary"></em> {{ $importedcategory->name }}
                                                </div>
                                                <div class="col-md-5 px-1">
                                                    <div class="text-left">{{ $importedcategory->mappedTo->name ?? '' }}</div>
                                                </div>
                                                <div class="col-md-2">
                                                    <span class="float-right">
                                                        <a href="{{route('admin.importedcategories.edit',$importedcategory)}}" category-id='{{$importedcategory->id}}' class='category-edit'><em class="icon ni ni-edit text-primary"></em></a>
                                                        <a onclick="$('#delete-form-{{$importedcategory->id}}').submit();" style="cursor: pointer"> <em class="icon ni ni-trash-fill text-danger"></em></a>
                                                        <form action="{{ route('admin.importedcategories.destroy', $importedcategory) }}" id="delete-form-{{$importedcategory->id}}" method="POST" class="m-0">
                                                            @method('DELETE')
                                                            @csrf

                                                        </form>
                                                    </span>
                                                </div>
                                            </div>
                                            @if(count($importedcategory->childs))
                                            @include('admin-dashboard.imported-categories.child',['childs' => $importedcategory->childs])
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                </div><!-- .card-inner -->
                            </div><!-- .card-inner-group -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- @@ Category Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="category-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Update Category</span></div>
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="categories" class=" p-4">
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->

<!-- import categories form modal -->
<div class="modal fade run-model" tabindex="-1" id="import-categories" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <a href="javascript:void(0);" class="close" data-dismiss="modal">
                <em class="icon ni ni-cross"></em>
            </a>
            <div class="modal-body modal-body-lg text-center">
                <div class="nk-modal">
                    <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-upload bg-warning"></em>
                    <h4 class="nk-modal-title">Import Categories</h4>
                    <div class="nk-modal-action">
                        <form action="{{ route('admin.networks.categories.import', $network->id) }}" method="post" id="categories-import-form" enctype="multipart/form-data">
                            @csrf()
                            <div class="form-group">
                                <div class="form-control-wrap" style="width: 70%; margin: 0 auto; text-align: left;">
                                    <div class="custom-file">
                                        <input type="file" name="csv" class="custom-file-input" id="categories-csv-input" accept=".csv" required>
                                        <label class="custom-file-label" for="categories-csv-input">Choose categories CSV</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button href="javascript:void(0);" class="btn btn-sm btn-primary" submit-btn>
                                    <span>Start Importer</span>&nbsp;
                                </button>
                            </div>
                            <div class="form-group">
                                <span class="alert alert-danger" style="display: none;"></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- .modal-body -->
            <div class="modal-footer bg-lighter">
                <div class="text-center w-100">
                    <p>Import categories using CSV file.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tree,
    .tree ul {
        margin: 0;
        padding: 0;
        list-style: none
    }

    .tree ul {
        margin-left: 1em;
        position: relative
    }

    .tree ul ul {
        margin-left: .5em
    }

    .tree ul:before {
        content: "";
        display: block;
        width: 0;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        border-left: 1px solid
    }

    .tree li {
        margin: 0;
        padding: 0 0 0 1em;
        line-height: 2em;
        color: #369;
        font-weight: 700;
        position: relative;
        text-transform: capitalize;
    }

    .tree ul li:before {
        content: "";
        display: block;
        width: 10px;
        height: 0;
        border-top: 1px solid;
        margin-top: -1px;
        position: absolute;
        top: 1em;
        left: 0
    }

    .tree ul li:last-child:before {
        background: #fff;
        height: auto;
        top: 1em;
        bottom: 0
    }

    .indicator {
        margin-right: 5px;
    }

    .tree li a {
        text-decoration: none;
        color: #369;
    }

    .tree li button,
    .tree li button:active,
    .tree li button:focus {
        text-decoration: none;
        color: #369;
        border: none;
        background: transparent;
        margin: 0px 0px 0px 0px;
        padding: 0px 0px 0px 0px;
        outline: 0;
    }
</style>

@endsection

@push('scripts')
<link rel="stylesheet" href="{{ asset('admin-dashboard/css/editors/quill.css?ver=2.2.0')}}">
<script src="{{ asset('admin-dashboard/js/libs/editors/quill.js?ver=2.2.0')}}"></script>
<script src="{{ asset('admin-dashboard/js/editors.js?ver=2.2.0')}}"></script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.category-edit', function(event) {
            event.preventDefault();

            var id = $(this).attr('category-id');
            pageurl = $(this).attr('href');
            var _token = $("input[name=_token]").val();
            $.ajax({

                url: pageurl,
                method: "GET",
                data: {
                    _token: _token
                },
                success: function(data) {
                    $('#category-modal').modal('show');
                    $('#categories').html(data);

                }
            });

        });
    });
</script>

<script>
    $(document).ready(function() {

        $(document).on('submit', '.search_form', function(event) {
            event.preventDefault();

            var _token = $("input[name=_token]").val();
            var type = $("select[name=type]").val();
            var status = $("select[name=status").val();
            var name = $("input[name=name]").val();
            $.ajax({
                url: '{{route("admin.users.search_users")}}',
                method: "POST",
                data: {
                    _token: _token,
                    type: type,
                    name: name,
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

<script>
    $.fn.extend({
        treed: function(o) {

            var openedClass = 'glyphicon-minus-sign';
            var closedClass = 'glyphicon-plus-sign';

            if (typeof o != 'undefined') {
                if (typeof o.openedClass != 'undefined') {
                    openedClass = o.openedClass;
                }
                if (typeof o.closedClass != 'undefined') {
                    closedClass = o.closedClass;
                }
            };

            /* initialize each of the top levels */
            var tree = $(this);
            tree.addClass("tree");
            tree.find('li').has("ul").each(function() {
                var branch = $(this);
                branch.prepend("");
                branch.addClass('branch');
                branch.on('click', function(e) {
                    if (this == e.target) {
                        var icon = $(this).children('i:first');
                        icon.toggleClass(openedClass + " " + closedClass);
                        $(this).children('ul').children().toggle();
                    }
                })
                // branch.children().children().toggle();
            });
            /* fire event from the dynamically added icon */
            tree.find('.branch .indicator').each(function() {
                $(this).on('click', function() {
                    $(this).closest('li').click();
                });
            });
            /* fire event to open branch if the li contains an anchor instead of text */
            tree.find('.branch>a').each(function() {
                $(this).on('click', function(e) {
                    $(this).closest('li').click();
                    e.preventDefault();
                });
            });
            /* fire event to open branch if the li contains a button instead of text */
            tree.find('.branch>button').each(function() {
                $(this).on('click', function(e) {
                    $(this).closest('li').click();
                    e.preventDefault();
                });
            });
        }
    });
    /* Initialization of treeviews */
    $('#tree1').treed();
</script>

<script>
    $(document).ready(function() {
        $('#categories-import-form').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let modal = $('#import-categories');

            modal.find('[data-dismiss="modal"]').hide();
            modal.find('[submit-btn]')
                .attr('disabled', 'disabled')
                .append(`<span class="spinner-border spinner-border-sm"></span>`);

            $.ajax({
                url: form.attr('action'),
                method: "post",
                data: new FormData(form[0]),
                contentType: false,
                processData: false,
                complete: function(data) {
                    window.location.reload();
                }
            });
        });
    });
</script>
@endpush