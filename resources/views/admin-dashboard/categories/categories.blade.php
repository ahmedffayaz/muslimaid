@extends('layouts.admin-dashboard.app')
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
        border-left: 1px solid;
        color: #dbdfea;
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
        top: 1.8em;
        left: 0;
        color: #dbdfea;
    }

    .tree ul li:last-child:before {
        background: #fff;
        height: auto;
        top: 1.8em;
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
@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="components-preview wide-md mx-auto">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">Categories</h3>
                                    <div class="nk-block-des text-soft">
                                        <p>You have total {{ count($allCategories) }} categories.</p>
                                    </div>
                                </div><!-- .nk-block-head-content -->
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu">
                                            <em class="icon ni ni-menu-alt-r"></em>
                                        </a>
                                        <div class="toggle-expand-content" data-content="pageMenu">
                                            <ul class="nk-block-tools g-3">
                                                <li>
                                                    <a href="javascript:void(0)" id="show-modal" class="btn btn-primary btn-sm" class="btn btn-white btn-outline-light">
                                                        <em class="icon ni ni-plus"></em>
                                                        <span>Add category</span>
                                                    </a>
                                                </li>
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
                                        <h5 class="mb-3">All Categories</h5>
                                        <ul id="tree1">
                                            @foreach ($categories as $category)
                                                <li class="border mt-2 pt-2 pb-1 px-2">
                                                    <span class="float-right">
                                                        <div class="actions">
                                                            <div class="drodown d-inline">
                                                                <a href="#" class="dropdown-toggle badge badge-info text-white" data-toggle="dropdown">
                                                                    <em class="icon ni ni-plus mr-1"></em>Options
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <ul class="link-list-opt no-bdr d-block ml-0">
                                                                        <a href="{{ route('admin.categories.picks', $category) }}" category-id='{{ $category->id }}'
                                                                            class='picks-edit'>
                                                                            <em class="icon ni ni-cart-fill"></em>
                                                                            Editor Picks
                                                                        </a>
                                                                        <a href="{{ route('admin.categories.edit', $category) }}" category-id='{{ $category->id }}'
                                                                            class='category-edit'>
                                                                            <em class="icon ni ni-edit"></em> Edit
                                                                        </a>
                                                                        <a class='category-delete' data-action="{{ route('admin.categories.destroy', $category) }}"
                                                                            data-id="{{ $category->id }}" style="cursor: pointer">
                                                                            <em class="icon ni ni-trash-fill"></em>
                                                                            Delete
                                                                        </a>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </span>
                                                    <em class="icon ni ni-db-fill text-primary"></em> {{ $category->name }}
                                                    <span class="ml-1">
                                                        @if ($category->picks->count())
                                                            <span class="badge badge-dim badge-pill badge-primary text-capitalize">
                                                                <em class="icon ni ni-done"></em> Editor Picks
                                                            </span>
                                                        @endif
                                                    </span>
                                                    <span class="ml-1">
                                                        {!! $category->enableGoogleMap() !!}
                                                    </span>
                                                    @if (count($category->childs))
                                                        @include('admin-dashboard.categories.child', [
                                                            'childs' => $category->childs,
                                                        ])
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

    <x-admin-dashboard.modal modalSize="modal-lg" headerAlignment="align-center" formWrapperClass="" />
@endsection
@push('scripts')
    <link rel="stylesheet" href="{{ asset('admin-dashboard/css/editors/quill.css?ver=2.2.0') }}">
    <script src="{{ asset('admin-dashboard/js/libs/editors/quill.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('admin-dashboard/js/editors.js?ver=2.2.0') }}"></script>
    <script>
        var quill = null;
        $(document).ready(function() {
            // Show create modal
            $(document).on('click', '#show-modal', function(event) {
                event.preventDefault();
                $.ajax({
                    url: "{{ route('admin.categories.create') }}",
                    type: 'GET',
                    success: function(response) {
                        $('.title').text('Create Category');
                        $('#form-wrapper').html(response);
                        $('#save-btn').text('Create');
                        $('#modal').modal('show');
                        NioApp.Select2('.form-select');
                        quillEditor()
                        logoType()
                        bannerType()
                        store()
                        validation();

                    }
                });
            });

            // Show edit modal
            $(document).on('click', '.category-edit', function(event) {
                event.preventDefault();
                $.ajax({
                    url: $(this).attr('href'),
                    type: 'GET',
                    success: function(response) {
                        $('.title').text('Edit Category');
                        $('#form-wrapper').html(response);
                        $('#save-btn').text('Update');
                        $('#modal').modal('show');
                        NioApp.Select2('.form-select');
                        quillEditor()
                        logoType()
                        bannerType()
                        store()
                        validation()
                    }
                });
            });

            // Show editor picks modal
            $(document).on('click', '.picks-edit', function(event) {
                event.preventDefault();
                var id = $(this).attr('category-id');
                url = $(this).attr('href');
                var _token = $("input[name=_token]").val();
                $.ajax({
                    url: url,
                    method: "GET",
                    data: {
                        _token: _token
                    },
                    success: function(data) {
                        $('.title').text('Editor Picks');
                        $('#form-wrapper').html(data);
                        $('#save-btn').text('Update');
                        $('#modal').modal('show');
                        initializeSelect2();
                        storePicks();
                    }
                });
            });

            function quillEditor() {
                quill = new Quill('#editor-container', {
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

            function logoType() {
                $(document).ready(function() {
                    if ($('#logo_type').val() == 'upload') {
                        $('.logo_upload').show();
                        NioApp.BS.fileinput('.custom-file-input');
                        $('.logo_link').hide();
                        $('#logo_link').removeAttr('required').val('');
                    } else if ($('#logo_type').val() == 'link') {
                        $('.logo_link').show();
                        $('#logo_link').attr('required', 'required');
                        $('.logo_upload').hide();
                    }
                });
                $(document.body).on("change", "#logo_type", function() {
                    if (this.value == 'upload') {
                        $('.logo_upload').show();
                        NioApp.BS.fileinput('.custom-file-input');
                        $('.logo_link').hide();
                        $('#logo_link').removeAttr('required').val('');
                    } else if (this.value == 'link') {
                        $('.logo_link').show();
                        $('#logo_link').attr('required', 'required');
                        $('.logo_upload').hide();
                    }
                });
            }

            function bannerType() {
                $(document).ready(function() {
                    if ($('#banner_type').val() == 'upload') {
                        $('.banner_upload').show();
                        $('.banner_link').hide();
                        $('#banner_link').removeAttr('required').val('');
                    } else if ($('#banner_type').val() == 'link') {
                        $('.banner_link').show();
                        $('#banner_link').attr('required', 'required');
                        $('.banner_upload').hide();
                    }
                });
                $(document.body).on("change", "#banner_type", function() {
                    if (this.value == 'upload') {
                        $('.banner_upload').show();
                        $('.banner_link').hide();
                        $('#banner_link').removeAttr('required').val('');
                    } else if (this.value == 'link') {
                        $('.banner_link').show();
                        $('#banner_link').attr('required', 'required');
                        $('.banner_upload').hide();
                    }
                });
            }

            // update category
            function store() {
                $('#category-form').on('submit', function(event) {
                    event.preventDefault();
                    let form = $(this);
                    if (!form.valid()) {
                        return false;
                    }
                    let btn = $('#save-btn')
                    btn.attr('disabled', 'disabled')
                        .append('<span class="spinner-border spinner-border-sm ml-1" role="status" aria-hidden="true"></span>');
                    let url = $(this).attr('action');
                    // Populate hidden form on submit
                    let desc = document.querySelector('input[name=description]');
                    desc.value = quill.root.innerHTML;

                    let method = 'POST';
                    let formData = new FormData(this);
                    let id = $('#id').val();
                    if (id) {
                        url = $(this).attr('action');
                        formData.append('_method', 'PUT');
                    }
                    $.ajax({
                        url: url,
                        type: method,
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function(response) {
                            $('#modal').modal('hide');
                            btn.removeAttr('disabled', 'disabled').button('refresh');
                            btn.children().remove('span.spinner-border.spinner-border-sm.ml-1').button('refresh');
                            $('#tree1').load(location.href + ' #tree1');
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(response.success, 'success');
                            })(NioApp, jQuery);
                        },
                        error: function(error) {
                            btn.removeAttr('disabled', 'disabled').button('refresh');
                            btn.children().remove('span.spinner-border.spinner-border-sm.ml-1').button('refresh');
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
            }

            // Update category picks
            function storePicks() {
                $('#category-picks-form').on('submit', function(event) {
                    event.preventDefault();
                    let btn = $('#save-btn')
                    btn.attr('disabled', 'disabled')
                        .append('<span class="spinner-border spinner-border-sm ml-1" role="status" aria-hidden="true"></span>');
                    let url = $(this).attr('action');
                    let method = 'POST';
                    let formData = new FormData(this);
                    $.ajax({
                        url: url,
                        type: method,
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function(response) {
                            $('#modal').modal('hide');
                            btn.removeAttr('disabled', 'disabled').button('refresh');
                            btn.children().remove('span.spinner-border.spinner-border-sm.ml-1').button('refresh');
                            $('#tree1').load(location.href + ' #tree1');
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(response.success, 'success');
                            })(NioApp, jQuery);
                        },
                        error: function(error) {
                            btn.removeAttr('disabled', 'disabled').button('refresh');
                            btn.children().remove('span.spinner-border.spinner-border-sm.ml-1').button('refresh');
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
            }
        });

        $(document).ready(function() {
            $(document).on('submit', '.search_form', function(event) {
                event.preventDefault();
                var _token = $("input[name=_token]").val();
                var type = $("select[name=type]").val();
                var status = $("select[name=status").val();
                var name = $("input[name=name]").val();
                $.ajax({
                    url: '{{ route('admin.users.search_users') }}',
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

        function initializeSelect2() {
            $('.select-2').select2({
                placeholder: function() {
                    $(this).data('placeholder');

                }
            });
        }

        // Delete category
        $(document).on('click', '.category-delete', function(event) {
            event.preventDefault();
            id = $(this).data('id')
            url = $(this).data('action');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            '_token': "{{ csrf_token() }}",
                            'id': id
                        },
                        success: function(response) {
                            $('#tree1').load(location.href + ' #tree1');
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(response.success, 'success');
                            })(NioApp, jQuery);
                        },
                        error: function(error) {
                            (function(NioApp, $) {
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(error.responseJSON.error, 'error');
                            })(NioApp, jQuery);
                        }
                    });
                } else {
                    (function(NioApp, $) {
                        'use strict';
                        toastr.clear();
                        NioApp.Toast('Something went wrong, try again', 'error');
                    })(NioApp, jQuery);
                }
            });
            // event.preventDefault();
        });

        function validation() {
            $('#category-form').validate({
                errorClass: 'invalid-feedback d-block',
                rules: {
                    name: {
                        required: true
                    },
                    logo_type: {
                        required: true
                    },
                    logo_link: {
                        url: true,
                        required: true
                    },
                    logo_upload: {
                        required: true
                    },
                    banner_upload: {
                        required: true
                    },
                    banner_type: {
                        required: true
                    },
                    banner_link: {
                        url: true,
                        required: true
                    },
                    status: {
                        required: true
                    },
                }
            });
        }
        $(document).on('change', '.get_parent', function(event) {
            id = $('.get_parent').val();
            url = '{{ route('admin.categories.sort') }}';
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    '_token': "{{ csrf_token() }}",
                    'category_id': id
                },
                success: function(data) {
                    var sortValue = parseFloat(data);
                    if (!isNaN(sortValue)) {
                        $('#sort').val(sortValue);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    </script>
@endpush
