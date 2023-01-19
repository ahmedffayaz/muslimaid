@extends('frontend.layouts.app')

@section('content')
    <div class="block mt-5">
        <div class="container">
            <div class="row">

                <div class="col-12 col-lg-3 d-flex">
                    @include('frontend.client-dashboard.side-nav')
                </div>

                <div class="col-12 col-lg-9 mt-4 mt-lg-0">

                    @include('flash::message')

                    <div class="row ">
                        <div class="col-lg-12">
                            <h2>Payment methods</h2>
                            <p>
                                Your earned cashback can be withdrawn in a variety of means. All details will be stored safely and securely and can be changed at any time. To
                                request a payment please visit the <a href="{{ route('account.withdraw.index') }}">Withdraw</a> page.
                            </p>
                            <p>If you update these details any requested withdrawals will be cancelled while we undertake security checks.</p>
                        </div>
                    </div>

                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed text-dark pl-0" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false"
                                        aria-controls="collapseOne">
                                        Bank Transfer
                                    </button>
                                </h5>
                            </div>
                            @php $user = Auth::user(); @endphp
                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body pt-0">
                                    <form action="{{ route('account.payment_save') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="payment_method" value="bank">
                                        <div class="row g4 bank">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="account_name">Account Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="account_name" name="account_name" required
                                                            value="{{ $user->bankInfo->account_name ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="bank_title">Bank Title</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="bank_title" name="bank_title" required
                                                            value="{{ $user->bankInfo->bank_title ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="account_number">Accont Number</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="account_number" name="account_number" required
                                                            value="{{ $user->bankInfo->account_number ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="bank_sort_code">
                                                        Bank Sort Code</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="bank_sort_code" name="bank_sort_code" required
                                                            value="{{ $user->bankInfo->bank_sort_code ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="bic">BIC</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="bic" name="bic" required value="{{ $user->bankInfo->bic ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed text-dark pl-0" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                        Paypal
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body pt-0">
                                    <form action="{{ route('account.payment_save') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="payment_method" value="paypal">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="paypal_email">Paypal Email</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="paypal_email" name="paypal_email" required
                                                            value="{{ $user->paypalInfo->paypal_email ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <button type="submit" class="btn btn-primary">Save</button>
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
@endsection
