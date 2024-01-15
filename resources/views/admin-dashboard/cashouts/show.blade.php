@extends('layouts.admin-dashboard.app')
<style>
    .nk-tb-list {
        table-layout: fixed;
    }

    .nk-files-view-grid .nk-file-icon-type {
        width: 150px;
        padding: 2rem 0 .5rem 0;
    }

    @media (min-width: 1200px) {
        .nk-files-view-grid .nk-file {
            width: calc(25% - 16px) !important;
        }
    }

    .nk-files-view-grid .nk-file {
        background-color: #f5f6fa7a !important;
    }

    .stores .select2 {
        width: 300px !important;
    }

    .select2-selection__choice {
        text-transform: capitalize;
    }
</style>

@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="components-preview wide-md mx-auto">
                        <div class="nk-block-head nk-block-head-lg pb-2">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title fw-normal"></h3>
                                    <div class="nk-block-des">

                                    </div>
                                </div>
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                            data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                        <div class="toggle-expand-content" data-content="pageMenu">
                                            <ul class="nk-block-tools g-3">
                                            </ul>
                                        </div>
                                    </div><!-- .toggle-wrap -->
                                </div><!-- .nk-block-head-content -->
                            </div>
                        </div>

                        @include('flash::message')

                        <div class="nk-block nk-block-lg">
                            <div class="card card-preview">
                                <div class="card-inner">
                                    <div class="nk-block">
                                        <div class="nk-block-head">
                                            <h5 class="title">Cashout Information</h5>
                                        </div><!-- .nk-block-head -->
                                        <div class="profile-ud-list">
                                            <div class="profile-ud-item">
                                                <div class="profile-ud wider">
                                                    <span class="profile-ud-label">User</span>
                                                    <span class="profile-ud-value"><a
                                                            href="{{ route(getAdminPrefix() . '.users.show_user') }}?user_id={{ $cashout->user->id }}"
                                                            class="a_link">{{ $cashout->user->id }} - @if ($cashout->user->first_name != 'unnamed' || $cashout->user->last_name != 'unnamed')
                                                                {{ $cashout->user->first_name }}
                                                                {{ $cashout->user->last_name }} -
                                                            @endif
                                                            {{ $cashout->user->email }}</a></span>
                                                </div>
                                            </div>
                                            <div class="profile-ud-item">
                                                <div class="profile-ud wider">
                                                    <span class="profile-ud-label">Withdraw Amount</span>
                                                    <span class="profile-ud-value">{{ currency($cashout->amount) }}</span>
                                                </div>
                                            </div>
                                            <div class="profile-ud-item">
                                                <div class="profile-ud wider">
                                                    <span class="profile-ud-label">Request Date</span>
                                                    <span
                                                        class="profile-ud-value">{{ formatDateTimezone($cashout->created_at, 'Do MMMM YYYY') }}</span>
                                                </div>
                                            </div>
                                            <div class="profile-ud-item">
                                                <div class="profile-ud wider">
                                                    <span class="profile-ud-label">Status</span>
                                                    <span class="profile-ud-value">{{ ucfirst($cashout->status) }}</span>
                                                </div>
                                            </div>

                                        </div><!-- .profile-ud-list -->
                                    </div><!-- .nk-block -->

                                    <div class="nk-block">
                                        <div class="nk-block-head nk-block-head-line">
                                            <h6 class="title overline-title text-base">Payment Details</h6>
                                        </div><!-- .nk-block-head -->
                                        <div class="profile-ud-list">
                                            <div class="profile-ud-item">
                                                <div class="profile-ud wider">
                                                    <span class="profile-ud-label">Payment Method</span>
                                                    <span
                                                        class="profile-ud-value">{{ ucfirst($cashout->payment_method) }}</span>
                                                </div>
                                            </div>
                                        </div><!-- .profile-ud-list -->
                                    </div><!-- .nk-block -->

                                    @if (!empty($cashout->user->deleted_at))
                                        <div class="nk-block">
                                            <div class="nk-block-head nk-block-head-line">
                                                <div class="alert alert-fill alert-icon alert-warning d-block">
                                                    <em class="icon ni ni-alert-circle"></em>
                                                    <strong>The user has been deleted</strong>, their pending cashout will
                                                    be transferred to the admin account for further processing..
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="nk-divider divider"></div>
                                    <div class="nk-block">
                                        <div class="nk-block-head nk-block-head-line">
                                            <h6 class="title overline-title text-base">Meta Data</h6>
                                        </div><!-- .nk-block-head -->
                                        <div class="profile-ud-list">
                                            @if (count($cashout->metaData) != 0 && isset($cashout->appeal))
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span
                                                            class="profile-ud-label">{{ ucfirst(str_replace('_', ' ', $cashout->appeal->type)) }}</span>
                                                        <span class="profile-ud-value">{{ $cashout->appeal->title }}</span>
                                                    </div>
                                                </div>
                                            @elseif (count($cashout->metaData) != 0 && isset($cashout->charity))
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span
                                                            class="profile-ud-label">{{ ucfirst(str_replace('_', ' ', $cashout->charity->type)) }}</span>
                                                        <span
                                                            class="profile-ud-value">{{ $cashout->charity->title }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                @foreach ($cashout->metaData as $data)
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span
                                                                class="profile-ud-label">{{ ucfirst(str_replace('_', ' ', $data->type)) }}</span>
                                                            <span class="profile-ud-value">{{ $data->value }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div><!-- .profile-ud-list -->
                                    </div><!-- .nk-block -->
                                    @if (count($cashout->cashbacks))
                                        <div class="nk-divider divider"></div>
                                        <div class="nk-block">
                                            <div class="nk-block-head nk-block-head-line">
                                                <h6 class="title overline-title text-base">Cashback</h6>
                                            </div><!-- .nk-block-head -->
                                            <div class="profile-ud-list">
                                                @foreach ($cashout->cashbacks as $cashback)
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label">Store</span>
                                                            @if ($cashback->store_id)
                                                                <span class="profile-ud-value"><a
                                                                        href="{{ route(getAdminPrefix() . '.stores.show_store') }}?slug={{ $cashback->store->slug }}">
                                                                        {{ $cashback->store->name }}</a></span>
                                                            @else
                                                                <span class="profile-ud-value">
                                                                    {{ ucfirst(str_replace('_', ' ', $cashback->type)) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label">Cashback Amount</span>
                                                            <span
                                                                class="profile-ud-value">{{ currency($cashback->amount) }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div><!-- .profile-ud-list -->
                                        </div><!-- .nk-block -->
                                    @endif
                                    @if ($cashout->bonus)
                                        <div class="nk-divider divider"></div>
                                        <div class="nk-block">
                                            <div class="nk-block-head nk-block-head-line">
                                                <h6 class="title overline-title text-base">Bonus</h6>
                                            </div><!-- .nk-block-head -->
                                            <div class="profile-ud-list">
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Bonus</span>
                                                        <span class="profile-ud-value">Sign up bonus</span>
                                                    </div>
                                                </div>

                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Amount</span>
                                                        <span
                                                            class="profile-ud-value">{{ currency($cashout->bonus->amount) }}</span>
                                                    </div>
                                                </div>
                                            </div><!-- .profile-ud-list -->
                                        </div><!-- .nk-block -->
                                    @endif
                                </div><!-- .card-inner -->
                                <div class="card-inner">

                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tabItem5">
                                            <div class="nk-block">
                                                <div class="nk-block-head">
                                                </div><!-- .nk-block-head -->

                                                <form
                                                    action="{{ route(getAdminPrefix() . '.cashouts.update', $cashout) }}"
                                                    class="gy-3 form-validate is-alter" id="form_withdraw"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row g-4">
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="default-06">Status <span
                                                                        class="text-danger">*</span></label>
                                                                <div class="form-control-wrap ">
                                                                    <div class="form-control-select">
                                                                        <select class="form-control status" name="status"
                                                                            required>
                                                                            @if ($cashout->status != 'paid' && $cashout->status != 'donated')
                                                                                <option
                                                                                    @if ($cashout->status == 'pending' || $cashout->status == 'processing donation') selected @endif>
                                                                                    Pending
                                                                                </option>
                                                                            @endif
                                                                            @if ($cashout->status == 'paid' || $cashout->status == 'pending')
                                                                                <option
                                                                                    @if ($cashout->status == 'paid') selected @endif
                                                                                    value="paid">Paid</option>
                                                                            @endif
                                                                            @if ($cashout->status == 'processing donation' || $cashout->status == 'donated')
                                                                                <option
                                                                                    @if ($cashout->status == 'donated') selected @endif
                                                                                    value="donated">Donated</option>
                                                                            @endif
                                                                        </select>
                                                                        <div class="status-error text-danger mt-1"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <button type="button"
                                                                    class="btn btn-lg btn-primary withdraw_submit">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .card-preview -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        let deletedUser = "{{ $cashout->user->deleted_at }}";
        let cashoutStatus = "{{ $cashout->status }}";

        let message = '';
        if (deletedUser !== '' && deletedUser != null && (cashoutStatus == 'processing' || cashoutStatus === 'pending' || cashoutStatus === 'processing donation')) {
            message = `<div class="alert alert-fill alert-icon alert-warning d-block">
                        <em class="icon ni ni-alert-circle"></em>
                        <strong>The user has been deleted</strong>, their pending cashout will be transferred to the admin account for further processing..
                    </div>`;
        }

        // Display/hide error message on change class status event
        $('.status').on('change', function() {
            var status = $(this).val(); // Get the selected value

            // Check if the selected status is 'donated' or 'paid'
            if (status === 'donated' || status === 'paid') {
                $('.status-error').html(''); // Remove the HTML content
            } else {
                $('.status-error').html('Please select status.'); // Show the error message
            }
        });

        $(document).on('click', '.withdraw_submit', function(e) {
            console.log($('.status').val());
            // Display error message on on submit form if status not donated or paid
            var status = $('.status').val();
            if (status !== 'donated' && status !== 'paid') {
                $('.status-error').html('Please select status.');
                return;
            }

            var form_id = "form_withdraw";
            var action = $(this).attr("action");
            var method = $(this).attr("method");

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                footer: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit it!',
                focusConfirm: false,
            }).then(function(result) {
                if (result.value) {
                    $("#" + form_id).submit();
                } else {
                    window.toast({
                        type: 'error',
                        title: error.response.data.message
                    });
                }
            });
        });
    </script>
@endpush
