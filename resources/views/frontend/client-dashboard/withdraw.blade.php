@extends('frontend.layouts.app')

@section('content')
    @php
        if (array_key_exists('min_cashout_amount', SiteSetting()->toArray())) {
            $min = SiteSetting()['min_cashout_amount'];
        } else {
            $min = 1;
        }
    @endphp
    @php
        $user = Auth::user();
        $cashout_status = $user
            ->cashouts()
            ->pluck('status')
            ->all();
    @endphp
    @php
        $cashback = $usercashback->all();
    @endphp
    <div class="row">
        <div class="container">
            <div class="col-md-12">
                <div class="block mt-5">
                    <div class="container">
                        <div class="row">

                            <div class="col-12 col-lg-3 d-flex">
                                @include('frontend.client-dashboard.side-nav')
                            </div>

                            <div class="col-12 col-lg-9 mt-4 mt-lg-0">

                                @include('flash::message')

                                <div class="row ">
                                    <div class="col-lg-8">
                                        <h2>Withdraw</h2>
                                        <p>You can withdraw your earned cashback in a variety of ways.</p>
                                    </div>
                                    <div class="col-lg-4 text-right">
                                        <h3>
                                            Balance
                                            {{ currency() }}
                                            {{ number_format((float) Auth::user()->availableBalance(), 2, '.', '') }}
                                        </h3>
                                        </p>Select a payment method</p>
                                    </div>
                                </div>
                                <div class="products-view__list products-list" data-layout="list" data-with-features="false" data-mobile-grid-columns="2">
                                    <div class="products-list__body">

                                        @if (SiteSetting()['payment_method_paypal'])
                                            <div class="products-list__item">
                                                <div class="product-card product-card--hidden-actions ">
                                                    <div class="product-card__image product-image pt-0 ">
                                                        <img class="product-image__img" style="width: 150px" src="{{ asset('frontend/images/logos/paypal-logo.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="product-card__info align-self-center">
                                                        <div class="product-card__name ">
                                                            <ul class="product-card__features-list">
                                                                <li>Receive payment using an email address</li>
                                                                <li>Minimum withdrawal {{ currency() }} {{ $min }}</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="product-card__actions align-self-center">
                                                        <div class="product-card__buttons mt-2">

                                                            @if (Auth::user()->availableBalance() < $min)
                                                                <p class="small">
                                                                    Your cashback balance is currently less than {{ currency() }} {{ $min }}
                                                                </p>
                                                            @endif

                                                            <form action="{{ route('account.cashout') }}" class="m-auto" id="paypal_withdraw_form" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="payment_method" value="paypal">
                                                                <button type="button"
                                                                    @if (Auth::user()->availableBalance() < $min) class="btn btn-success disabled"
                                                                    @elseif(in_array('pending', $cashout_status) || in_array('processing donation', $cashout_status))
                                                                        class="btn btn-success  popoverData disabled" rel="popover" data-placement="bottom" data-content="You already have a withdraw request" data-trigger="hover" 
                                                                    @else
                                                                        class="btn btn-success withdraw_submit" @endif>
                                                                    Withdraw
                                                                </button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if (SiteSetting()['payment_method_bank'])
                                            <div class="products-list__item">
                                                <div class="product-card product-card--hidden-actions ">
                                                    <div class="product-card__image product-image align-self-center">
                                                        <h4>Bank Transfer</h4>
                                                    </div>
                                                    <div class="product-card__info align-self-center">
                                                        <div class="product-card__name ">
                                                            <ul class="product-card__features-list">
                                                                <li>Free & secure</li>
                                                                <li>Minimum withdrawal £{{ $min }}</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="product-card__actions align-self-center">
                                                        <div class="product-card__buttons mt-2">

                                                            @if (Auth::user()->availableBalance() < $min)
                                                                <p class="small">
                                                                    Your cashback balance is currently less than {{ currency() }} {{ $min }}
                                                                </p>
                                                            @endif

                                                            <form action="{{ route('account.cashout') }}" class="m-auto" id="bank_withdraw_form" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="payment_method" value="bank">
                                                                <button type="button"
                                                                    @if (Auth::user()->availableBalance() < $min) class="btn btn-success disabled" 
                                                                    @elseif(in_array('pending', $cashout_status) || in_array('processing donation', $cashout_status))
                                                                        class="btn btn-success popoverData disabled withdraw_submit" rel="popover" data-placement="bottom" data-content="You already have a withdraw request" data-trigger="hover" 
                                                                    @else
                                                                        class="btn btn-success submit withdraw_submit" @endif>
                                                                    Withdraw
                                                                </button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if (SiteSetting()['payment_method_charity'])
                                            <div class="products-list__item">
                                                <div class="product-card product-card--hidden-actions ">
                                                    <div class="product-card__image product-image align-self-center">
                                                        <h4>Charity</h4>
                                                    </div>
                                                    <div class="product-card__info align-self-center">
                                                        <div class="product-card__name ">
                                                            <ul class="product-card__features-list">
                                                                <li>Free & secure</li>
                                                                <li>Minimum withdrawal £{{ $min }}</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="product-card__actions align-self-center">
                                                        <div class="product-card__buttons mt-2">
                                                            @if (Auth::user()->availableBalance() < $min)
                                                                <p class="small">
                                                                    Your cashback balance is currently less than {{ currency() }} {{ $min }}
                                                                </p>
                                                            @endif
                                                            <button
                                                                @if (Auth::user()->availableBalance() < $min) class="btn btn-success m-auto" disabled
                                                                @elseif(in_array('processing donation', $cashout_status) || in_array('pending', $cashout_status))
                                                                    class="btn btn-success m-auto popoverData disabled" rel="popover" data-placement="bottom" data-content="You already have a withdraw request" data-trigger="hover" 
                                                                @else
                                                                    class="btn btn-success m-auto"  id="charity-modal-show" @endif>
                                                                Withdraw
                                                            </button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div id="charity-modal" class="modal fade run-model" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg ">
                                                <div class="modal-content" id="quickview-modal-content">
                                                    <div class="quickview pt-5" style="padding: 20px">
                                                        <button class="quickview__close" type="button">
                                                            <svg width="20px" height="20px">
                                                                <use xlink:href="/frontend/images/sprite.svg#cross-20">
                                                                </use>
                                                            </svg>
                                                        </button>
                                                        <h3 class="text-center">Submit Cashout Request</h3>
                                                        <div class="container">

                                                            @include('flash::message')

                                                            @if ($errors->any())
                                                                <div class="alert alert-danger">
                                                                    <ul>
                                                                        @foreach ($errors->all() as $error)
                                                                            <li>{{ $error }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif

                                                            <form action="{{ route('account.CharityCashout') }}" id="charity_withdraw_form" class="form-validate" method="POST">
                                                                @csrf
                                                                <div class="row g-4 pt-4">
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <p>
                                                                                With Cashblack Giveback, when you choose to donate your cashback to one of yor affiliate
                                                                                charities, goodwill causes or community interests companies, we'll match your donation 100%
                                                                            </p>
                                                                            <h5 class="text-center">It Means When You Give, We Give</h5>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 py-3">
                                                                        <div class="form-inline">
                                                                            <div class="form-group">
                                                                                <label class="form-label pr-3 mr-3" for="charity_types_id">
                                                                                    <strong>Select Charity</strong>
                                                                                </label>
                                                                                <div class="form-control-wrap">
                                                                                    <div class="form-control-select">
                                                                                        <select class="form-select form-control" id="charity_types_id" name="charity_types_id"
                                                                                            required>
                                                                                            <option disabled selected>Any</option>
                                                                                            @foreach ($charities as $charity)
                                                                                                <option value="{{ $charity->id }}">
                                                                                                    {{ $charity->title }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="cart">
                                                                            <table class="cart__table cart-table" style="font-size: 12px">
                                                                                <thead class="cart-table__head">
                                                                                    <tr class="cart-table__row">
                                                                                        <input type="hidden" name="payment_method" value="charity">
                                                                                        <th class="cart-table__column cart-table__column--price text-center">
                                                                                            Store
                                                                                        </th>
                                                                                        <th class="cart-table__column cart-table__column--price text-center">
                                                                                            Order Amount
                                                                                        </th>
                                                                                        <th class="cart-table__column cart-table__column--price text-center">
                                                                                            Cashback
                                                                                        </th>
                                                                                        <th class="cart-table__column cart-table__column--price text-center">
                                                                                            Date
                                                                                        </th>
                                                                                        <th class="cart-table__column cart-table__column--price text-center">
                                                                                            Giveback Amount
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody class="cart-table__body">
                                                                                    @foreach ($usercashback as $cashback)
                                                                                        @if ($cashback->user_id == auth()->user()->id)
                                                                                            <input type="hidden" name="id" id="id" value="{{ $cashback->id }}">
                                                                                            <tr class="cart-table__row">
                                                                                                <td class="cart-table__column cart-table__column--product">
                                                                                                    <p class="cart-table__product-name text-center">
                                                                                                        {{ $cashback->store->name }}
                                                                                                    </p>
                                                                                                </td>
                                                                                                <td class="cart-table__column cart-table__column--product">
                                                                                                    <p class="cart-table__product-name text-center">
                                                                                                        {{ currency() }}
                                                                                                        {{ number_format((float) $cashback->order_value, 2, '.', '') }}
                                                                                                    </p>
                                                                                                </td>
                                                                                                <td class="cart-table__column cart-table__column--product">
                                                                                                    <p class="cart-table__product-name text-center value_amount">
                                                                                                        {{ currency() }}
                                                                                                        {{ number_format((float) $cashback->amount, 2, '.', '') }}
                                                                                                    </p>
                                                                                                </td>
                                                                                                <td class="cart-table__column cart-table__column--product pt-0">
                                                                                                    {{ Carbon\Carbon::parse($cashback->event_date)->isoFormat('Do MMMM YYYY') }}
                                                                                                </td>
                                                                                                <td class="cart-table__column cart-table__column--product text-center">
                                                                                                    <input type="checkbox" id="add_amount" class="add_amount checkbox"
                                                                                                        name="amount" value="{{ $cashback->amount }}" required>
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                            <div class="row justify-content-end pt-3">
                                                                                <div class="col-12 col-md-7 col-lg-6 col-xl-5">
                                                                                    <table class="cart__totals" style="width: 80%">
                                                                                        <thead class="cart__totals-header">
                                                                                            <tr>
                                                                                                <th>
                                                                                                    <strong>Total Donation:</strong>
                                                                                                </th>
                                                                                                <td>{{ currency() }}</td>
                                                                                                <td id="sum" class="text-center"></td>
                                                                                            </tr>
                                                                                        </thead>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group float-right">
                                                                                <button class="btn btn-primary withdraw_submit" type="button">Cashout Now</button>
                                                                            </div>
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
        $('#charity-modal-show').on('click', function() {
            $('#charity-modal').modal('show');
            $('.quickview__close').on('click', function() {
                $('#charity-modal').modal('hide');
            });
        });

        $('.popoverData').popover();

        $('.popoverOption').popover({
            trigger: "hover"
        });

        $('.add_amount').on('change', function() {
            var val = this.value;
            if ($(this).prop('checked') === true) {
                if ($('#sum').html() != '') {
                    val = parseInt(val) + parseInt($('#sum').html());
                }
                $('#sum').html(val);
            } else {
                val = parseInt($('#sum').html()) - parseInt(val);
                $('#sum').html(val);
            }

        });

        $(document).on('click', '.withdraw_submit', function(e) {
            var form_id = $(this).closest("form").attr('id')
            var action = $(this).closest("form").attr('action');
            var method = $(this).closest("form").attr('method');
            Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, submit it!'
                }).then(function(result) {
                    if (result.value) {
                        $("#" + form_id).submit();
                    } else {
                        window.toast({
                            type: 'error',
                            title: error.response.data.message
                        });
                    }
                })
                .catch(error => {
                    console.log(error)
                });
        });

        $('.form-validate').validate({
            errorClass: 'invalid-feedback d-block',
            rules: {
                charity_types_id: {
                    required: true,
                },
                amount: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                if ($(form).valid())
                    form.submit();
                return false;
            }
        });
    </script>
@endpush
