@extends('layouts.admin-dashboard.app')
@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">SEO Rules</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                        data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalForm">
                                                    <em class="icon ni ni-plus"></em>
                                                    <span>Add SEO Rule</span>
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

                                <div class="card-inner px-0">
                                    <div class="nk-tb-list nk-tb-ulist" id="table-data">

                                        @include('admin-dashboard.seo.index_data')

                                    </div><!-- .nk-tb-list -->
                                </div><!-- .card-inner -->

                            </div><!-- .card-inner-group -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    @include('admin-dashboard.seo.modal')
@endsection
@push('scripts')
    <script>
        $('.modal').on('hidden.bs.modal', function (e) {
            $(this)
            .find("input,textarea,select")
            .val('')
            .end()
            .find("input[type=checkbox], input[type=radio]")
            .prop("checked", "")
            .end();
        });

        $(document).ready(function() {
            var counter = 0;
            seo_fields();

            function seo_fields() {
                counter++;
                html = `<div>
                            <div class="row gy-4">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label" for="key-${counter}">Choose Key</label>
                                        <div class="form-control-wrap">
                                            <select class="form-select form-control" id="key-${counter}" name="type[${counter}][key]">
                                                <option value="meta_title">Meta: Title</option>
                                                <option value="meta_description">Meta: Description</option>
                                                <option value="meta_keyword">Meta: Keyword</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label" for="default-01">Value</label>
                                        <div class="form-control-wrap">
                                            <textarea class="form-control" id="default-01" name="type[${counter}][value]" rows="3" placeholder="Value"></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-12" style="margin-left: 50px;">
                                <span>
                                    <em class="icon ni ni-minus-circle delBtn" style="float: right;" data-type_counter="${counter}"></em>
                                </span>
                            </div>
                        </div>`;

                    $('#corsi').append(html);
                    $(`#key-${counter}`).select2({
                        minimumResultsForSearch: -1
                    });
                    $('.seo-fields').attr('data-count', counter);
            }

            // Appened SEO fields in modal form
            $(document).on('click', '#append_fields', function() {
                // let counter = $('.seo-fields').attr('data-count');
                html = seo_fields();

                    $('#corsi').append(html);
                    $(`#key-${counter}`).select2({
                        minimumResultsForSearch: -1
                    });
                    $('.seo-fields').attr('data-count', counter);
            });

            // Delete appenended fields
            $(document).on('click', '.delBtn', function() {
                $(this).parent().parent().parent().remove();
            });

            $('#save_modal_form').on('submit', function (event) {
                event.preventDefault();
                let save_btn = $('#add-btn');
                save_btn.attr('disabled', 'disabled').button('refresh');

                let method = "POST";
                let url = "{{ route('admin.seo.store') }}";
                let fd = new FormData(this);
                console.log(this);
                $.ajax({
                    url: url,
                    type: method,
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function (response) {
                        $('#modalForm').modal('hide');
                        $("#table-data").load(location.href + " #table-data");
                        console.log(response);
                    },
                    error: function (error) {
                        save_btn.removeAttr('disabled', 'disabled').button('resfresh')
                        console.log(error.responseJSON.message);
                    }
                });
            });

            // Delete table record
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
