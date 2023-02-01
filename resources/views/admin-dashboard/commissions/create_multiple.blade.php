@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title" style="font-size: 1.25rem; letter-spacing: -0.01rem; font-family: 'DM Sans', sans-serif; font-weight: 500;">Add Cashback</h3>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt">
                                            <a href="#" class="btn btn-success btn-sm" id="show-modal">
                                                <em class="icon ni ni-download-cloud"></em>
                                                <span>Import Cashbacks</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                @include('flash::message')
                <div class="components-preview mx-auto">
                    <div class="nk-block nk-block-lg">
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                    <h5 class="card-title">Cashback Info</h5>
                                </div>
                                <div id="multiple_cashbacks_form">
                                </div>
                            </div>
                        </div>
                    </div><!-- .nk-block -->
                </div><!-- .components-preview -->
            </div>
        </div>
    </div>
</div>
@include('layouts.admin-dashboard.includes.modal_file_upload')
@endsection

@push('scripts')<script>
    function addRows(){
        row = ` <div class="row ">
            <div class="col-lg-12 ml-auto mt-3">
                    <span class="delete-row float-right"><em class="icon ni ni-cross-circle-fill text-danger"></em></span>

                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="exit_click_id">Exit Click</label>
                            <div class="form-control-wrap ">
                                <input type="number" class="form-control" min="0.0" step="1" id="exit_click_id" name="exit_click_id[]" placeholder="Exit Click ID" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="phone-no-1">Order Value</label>
                            <div class="form-control-wrap">
                                <input type="number" class="form-control" min="0.0" step="1" id="phone-no-1" placeholder="Order Value" name="order_value[]">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="phone-no-1">Network Commission</label>
                            <div class="form-control-wrap">
                                <input type="number" class="form-control" step="1"  min="0.0" id="phone-no-1"  placeholder="Network Commission" name="network_commission[]" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="phone-no-1">Cashback Amount</label>
                            <div class="form-control-wrap">
                                <input type="number" class="form-control" min="0.0"  id="phone-no-1"  placeholder="Cashback Amount" name="amount[]" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="pay-amount-1">Event Date</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control date-picker" id="pay-amount-1" placeholder="01/25/2000" name="event_date[]" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="status">Status</label>
                            <div class="form-control-wrap ">
                                <div class="form-control-select">
                                    <select class="form-control form-select select-2" name="status[]" required>
                                        @foreach ($statuses as $status)
                                        <option value="{{$status->id}}">{{$status->status}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;

        $('.fields-container').append(row);
        initializeSelect2()
    }

    $(document.body).on('click', '.delete-row' ,function(){
        $(this).parents('.row').remove();

    });

    function initializeSelect2() {
        $('.select-2').select2({
            placeholder: function(){
                $(this).data('placeholder');
            }
        });
    }

    $(document).ready(function(){
        // show default 5 fields
        $.ajax({
            url: "{{ route('admin.commissions.form') }}",
            type: 'GET',
            success: function (response) {
                $('#multiple_cashbacks_form').html(response);
                NioApp.Picker.date('.date-picker');
                initializeSelect2();
                confirmMultipleCashbacks();
            }
        });

        // Show modal
        $('#show-modal').on('click', function (event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('admin.commissions.import.form') }}",
                type: 'GET',
                success: function (response) {
                    $('.title').text('Import Cashbacks');
                    $('#upload-file-form').html(response);
                    $('#save-btn').text('Import');
                    $('#modal').modal('show');
                    importCSV()
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });

        // Import CSV file
        function importCSV() {
            $('#save_modal_form').on('submit', function (event) {
                event.preventDefault();
                let btn = $('#show-modal')
                    btn.attr('disabled', 'disabled')
                        .append('<span class="spinner-border spinner-border-sm ml-1" role="status" aria-hidden="true"></span>');
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $('#multiple_cashbacks_form').html(response);
                        NioApp.Picker.date('.date-picker');
                        initializeSelect2();
                        $('#modal').modal('hide');
                        btn.removeAttr('disabled', 'disabled').button('refresh');
                        btn.children().remove('span.spinner-border.spinner-border-sm.ml-1').button('refresh');
                        confirmMultipleCashbacks();
                    },
                    error: function (error) {
                        console.log(error);
                        btn.removeAttr('disabled', 'disabled').button('refresh');
                        btn.children().remove('span.spinner-border.spinner-border-sm.ml-1').button('refresh');
                        if (error.responseJSON.error) {
                            (function(NioApp, $){
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(error.responseJSON.error, 'error');
                            })(NioApp, jQuery);
                        } else {
                            (function(NioApp, $){
                                'use strict';
                                toastr.clear();
                                NioApp.Toast(Object.values(error.responseJSON.errors)[0], 'error');
                            })(NioApp, jQuery);
                        }
                    }
                });
            });
        }

        // Multiple cashbacks submit confirmation
        function confirmMultipleCashbacks () {
            $('#save_form').on("submit", function(event){
                event.preventDefault();
                Swal.fire({
                    title: 'Save cashbacks?',
                    text: "These cashbacks will be shown to user accounts right away!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'OK'
                }).then((response) => {
                    if (response.value) {
                        let btn = $('#multiple-cashbacks-form-btn');
                        btn.attr('disabled', 'disabled')
                            .append('<span class="spinner-border spinner-border-sm ml-1" role="status" aria-hidden="true"></span>');
                        let url = $(this).attr('action');
                        let formData = new FormData(this);
                        storeMultipleCashbacks(url, formData);
                    }
                });
            });
        }

        // Insert multiple cashbacks in db
        function storeMultipleCashbacks(url, formData) {
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    window.location.href = response;
                },
                error: function (error) {
                    let btn = $('#multiple-cashbacks-form-btn');
                    btn.removeAttr('disabled', 'disabled').button('resfresh');
                    btn.children().remove('span.spinner-border.spinner-border-sm.ml-1').button('refresh');
                    if (error.responseJSON.error) {
                        (function(NioApp, $){
                            'use strict';
                            toastr.clear();
                            NioApp.Toast(error.responseJSON.error, 'error');
                        })(NioApp, jQuery);
                    } else {
                        (function(NioApp, $){
                            'use strict';
                            toastr.clear();
                            NioApp.Toast(Object.values(error.responseJSON.errors)[0], 'error');
                        })(NioApp, jQuery);
                    }
                }
            });
        }
    });
    </script>
@endpush
