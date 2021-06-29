@extends('layouts.frontend.app')
@section('content')

<div class="block mt-5">
    <div class="container">
        <div class="card mb-0">
            <div class="card-body contact-us">
                <div class="contact-us__container">
                    <div class="row">
                       
                        <div class="col-12">
                            @include('flash::message')
                            <h4 class="contact-us__header card-title">Submit a claim</h4>
                            <h5>To start the claim process, select the retailer and type of claim you wish to raise</h5>

                                <p>Please select the retailer you would like to enquire about:</p>
                            <form action="{{route('account.claim.step2')}}" method="POST">
                                @csrf
                                <div class="form-row mb-4">
                                    <div class="form-groug col-xl-8 col-md-12">
                                        <label for="store_id" class="mb-2">Select retailer</label>
                                        <select id="store_id" class="form-control form-control-select2" name="store_id" required>
                                            <option selected disabled value="">Select a retailer..</option>
                                            @foreach ($clicks->unique('store_id') as $click)
                                                <option value="{{$click->store->id}}">{{$click->store->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form group col-xl-8 col-md-12">
                                        <label for="store_id" class="mb-2">Select type of claim</label>
                                        <div class="payment-methods">
                                            <ul class="payment-methods__list">
                                                <li class="payment-methods__item payment-methods__item--active">
                                                    <label class="payment-methods__item-header">
                                                        <span class="payment-methods__item-radio input-radio">
                                                            <span class="input-radio__body">
                                                                <input class="input-radio__input" name="claim_type" value="missing cashback" type="radio" checked required>
                                                                <span class="input-radio__circle"></span>
                                                            </span>
                                                        </span>
                                                        <span class="payment-methods__item-title">Missing cashback<br>
                                                            <small>You made a purchase more than 1 days ago but it's not showing in your account</small>
                                                        </span>
                                                    </label>
                                                    <div class="payment-methods__item-container">
                                                        <div class="payment-methods__item-description text-muted">
                                                            <h6>Please note</h6>
                                                            <p>As your purchase didn't originally track this now becomes a manual process that on average can take up to 6 months to confirm your cashback.</p>
                                                            
                                                            <p>To help us process your claim as quickly and efficiently as possible we will require certain information from your email confirmation receipt, please have this to hand when submitting a claim.</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="payment-methods__item">
                                                    <label class="payment-methods__item-header">
                                                        <span class="payment-methods__item-radio input-radio">
                                                            <span class="input-radio__body">
                                                                <input class="input-radio__input" name="claim_type" value="declined cashback" type="radio" required>
                                                                <span class="input-radio__circle"></span>
                                                            </span>
                                                        </span>
                                                        <span class="payment-methods__item-title">Declined cashback<br>
                                                            <small>Your cashback was declined</small></span>
                                                    </label>
                                                    <div class="payment-methods__item-container">
                                                        <div class="payment-methods__item-description text-muted">
                                                            <h6>Please note</h6>
                                                            <p>Cashback can be declined for a variety of reasons such as:</p>

                                                            <p><ul><li>You used an unauthorised voucher</li>
                                                            <li>You didn't meet the terms set on the retailer page</li>
                                                            <li>Your purchase wasn't covered by the cashback rates</li></ul></p>
                                                            <p>If you feel that the above doesn't apply to you please continue to make a claim.</p>

                                                            <p>To help us process your claim as quickly and efficiently as possible we will require certain information from your email confirmation receipt, please have this to hand when submitting a claim.</p>

                                                            <p>The claim process is a manual process that on average can take up to 4 months for the retailer to review their decision.</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="payment-methods__item">
                                                    <label class="payment-methods__item-header">
                                                        <span class="payment-methods__item-radio input-radio">
                                                            <span class="input-radio__body">
                                                                <input class="input-radio__input" name="claim_type" value="incorrect amount" type="radio" required>
                                                                <span class="input-radio__circle"></span>
                                                            </span>
                                                        </span>
                                                        <span class="payment-methods__item-title">Incorrect amount<br>
                                                            <small>Your cashback is at tracked, confirmed or paid status but it's the wrong amount</small></span>
                                                    </label>
                                                    <div class="payment-methods__item-container">
                                                        <div class="payment-methods__item-description text-muted">
                                                            <h6>Please note</h6>
                                                            <p> Cashback can be lower than expected for the following reasons:</p>
                                                            
                                                            <p><ul><li>Some retailers don't pay cashback on VAT or additional charges or taxes</li>
                                                                <li>Some retailers don't pay cashback on delivery charges</li>
                                                                <li>Some retailers track purchases at a low rate and then uplift them to the correct rate on confirmation</li>
                                                                <li>You used an unauthorised voucher code in your purchase (In some cases a retailer may make a discretionary cashback payment for use of codes)</li></ul></p>
                                                            <p> If you feel that the above doesn't apply to you please continue to make a claim.</p>
                                                            
                                                            <p>The claim process is a manual process that on average can take up to 4 months for the retailer to review their decision.</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                
                                            </ul>
                                        </div>  
                                    </div>   
                                </div>
                                <button type="submit" class="btn btn-primary">Next</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection