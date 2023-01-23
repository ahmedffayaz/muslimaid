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
@include('layouts.admin-dashboard.includes.modal_lg')
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
                                <input type="text" class="form-control" id="exit_click_id" value="" name="exit_click_id[]" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="phone-no-1">Order Value</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="phone-no-1" value="" name="order_value[]">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="phone-no-1">Network Commission</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="phone-no-1" value="" name="network_commission[]" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="phone-no-1">Cashback Amount</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="phone-no-1" value="" name="amount[]" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="pay-amount-1">Event Date</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control date-picker" id="pay-amount-1" value="" name="event_date[]" required>
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

        $.ajax({
            url: "{{ route('admin.commissions.form') }}",
            type: 'GET',
            success: function (response) {
                $('#multiple_cashbacks_form').html(response);
                NioApp.Picker.date('.date-picker');
                initializeSelect2();
                storeMultipleCashbacks();
            }
        });

        // Show modal
        $('#show-modal').on('click', function (event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('admin.commissions.import.form') }}",
                type: 'GET',
                success: function (response) {
                    $('.modal-title').text('Import Cashbacks');
                    $('.modal-body').html(response);
                    $('#save-btn').text('Import');
                    $('#modal').modal('show');
                    importCSV()
                },
                error: function (response) {
                    console.log(response);
                }
            });
        });

        // Import CSV file
        function importCSV() {
            $('#save_modal_form').on('submit', function (event) {
                event.preventDefault();
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
                        storeMultipleCashbacks();
                    },
                    error: function (error) {
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

        function storeMultipleCashbacks () {
            $('#save_form').on("submit", function(event){
                event.preventDefault();
                Swal.fire({
                    title: 'Save cashbacks?',
                    text: "These cashbacks will be shown to user accounts right away!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.value) {
                        console.log(result);
                    }
                }).catch((error) => {
                    console.log(error);
                });
            });
        }
    });
    </script>
@endpush
