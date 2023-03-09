<div class="nk-tb-list nk-tb-ulist mt-3" style="table-layout: auto">

    @if (count($cashbacks))
        <div class="nk-tb-item nk-tb-head">

            <div class="nk-tb-col pl-0"><span class="sub-text">Store</span></div>
            <div class="nk-tb-col tb-col-mb text-center"><span class="sub-text">Network Commission ({{ getCurrencySymbol() }})</span></div>
            <div class="nk-tb-col tb-col-mb text-center"><span class="sub-text">Cashback ({{ getCurrencySymbol() }})</span></div>
            <div class="nk-tb-col  text-center"><span class="sub-text">Exit Click Id</span></div>
            <div class="nk-tb-col  text-center"><span class="sub-text">Event Time</span></div>
            <div class="nk-tb-col  text-right"><span class="sub-text">Status</span></div>
            <div class="nk-tb-col nk-tb-col-tools text-right pr-0">
                <span class="sub-text">Action</span>

            </div>
        </div><!-- .nk-tb-item -->
        @foreach ($cashbacks as $commission)
            <div class="nk-tb-item">

                <div class="nk-tb-col pl-1">
                    @if ($commission->store)
                        <span><b>{{ $commission->store->name ?? '' }}</b></span>
                    @else
                        <span><b>{{ $commission->type }}</b></span>
                    @endif
                </div>

                <div class="nk-tb-col  text-center">
                    @if ($commission->network_commission)
                        <span><span class="currency">{{ currency($commission->network_commission) }} </span></span>
                    @else
                        <span>-</span>
                    @endif
                </div>
                <div class="nk-tb-col  text-center">
                    <span><span class="currency">{{ currency($commission->amount) }} </span></span>
                </div>
                <div class="nk-tb-col  text-center">
                    @if ($commission->exit_click_id)
                        <span>{{ $commission->exit_click_id }}</span>
                    @else
                        <span>-</span>
                    @endif
                </div>
                <div class="nk-tb-col  text-right">
                    @if ($commission->event_date)
                        <span>{{ $commission->event_date }}</span>
                    @else
                        <span>{{ $commission->created_at }}</span>
                    @endif
                </div>
                <div class="nk-tb-col  text-right">
                    <span class="tb-status text-info">
                        @if ($commission->statusMap->status == 'confirmed')
                            <span class="badge badge-success">{{ $commission->statusMap->status }}</span>
                        @elseif($commission->statusMap->status == 'paid')
                            <span class="badge badge-success">{{ $commission->statusMap->status }}</span>
                        @elseif($commission->statusMap->status == 'failed')
                            <span class="badge badge-danger">{{ $commission->statusMap->status }}</span>
                        @elseif($commission->statusMap->status == 'pending')
                            <span class="badge badge-info">{{ $commission->statusMap->status }}</span>
                        @elseif($commission->statusMap->status == 'processing')
                            <span class="badge badge-success">{{ $commission->statusMap->status }}</span>
                        @elseif($commission->statusMap->status == 'processing donation')
                            <span class="badge badge-info">{{ $commission->statusMap->status }}</span>
                        @elseif($commission->statusMap->status == 'donated')
                            <span class="badge badge-success">{{ $commission->statusMap->status }}</span>
                        @endif
                    </span>
                </div>

                <div class="nk-tb-col nk-tb-col-tools pr-1">
                    <ul class="nk-tb-actions gx-1">

                        <li>
                            <div class="drodown">
                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a href="{{ route('admin.commissions.edit', $commission) }}" cashback-id='{{ $commission->id }}' class='cashback-edit'><em
                                                    class="icon ni ni-edit"></em><span>Edit</span></a></li>

                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div><!-- .nk-tb-item -->
        @endforeach
        <div class="nk-block-between-md px-0 card-inner">
            <div class="pagination g" route="" id="cashback-paginate">
                {!! $cashbacks->links() !!}

            </div>

        </div><!-- .nk-block-between -->
    @else
        <p>No cashbacks found</p>
    @endif
</div>

<!-- @@ Cashback Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="cashback-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">

                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Edit Cashback</span></div>
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="cashback" class=" p-4">

            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->

<script>
    $(document).ready(function() {
        $(document).on('click', '.cashback-edit', function(event) {
            event.preventDefault();
            $('#cashback').html(`<div class="text-center">
                <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>`);

            var id = $(this).attr('cashback-id');
            var pageurl = $(this).attr('href');
            var _token = $("input[name=_token]").val();
            $.ajax({

                url: pageurl,
                method: "GET",
                data: {
                    _token: _token
                },
                success: function(data) {
                    $('#cashback-modal').modal('show');
                    $('#cashback').html(data);
                    initializeSelect2()
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {

        $(document).on('submit', '#update_cashback_form', function(e) {
            e.preventDefault();
            var form_action = $(this).attr('action');
            var formdata = new FormData(this);
            $.ajax({
                url: form_action,
                method: "POST",
                data: formdata,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.updated) {
                        $('#cashback-modal').modal('hide');
                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            NioApp.Toast(data.message, 'success');
                        })(NioApp, jQuery);
                        fetchCashbacks();
                    } else {
                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            if (data.message) {
                                NioApp.Toast(data.message, 'error');
                            } else {
                                NioApp.Toast('Something went wrong! Unable to update the cashback.', 'error');
                            }
                        })(NioApp, jQuery);
                    }
                },
                error: function(error) {
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
    });

    function initializeSelect2() {
        $('.form-select').select2({
            placeholder: function() {
                $(this).data('placeholder');
            }
        });
    }
</script>
