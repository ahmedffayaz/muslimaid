@extends('frontend.layouts.app')
@section('content')

<div class="block mt-5">
    <div class="container">
        <div class="card mb-0">
            <div class="card-body contact-us">
                <div class="contact-us__container">
                    <div class="row">
                       
                        <div class="col-12">
                            <h4 class="contact-us__header card-title">Submit a claim</h4>
                            <form action="{{route('account.tickets.update', $claim)}}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-row">
                                    <div class="form-group col-xl-8 col-md-8">
                                        <label for="amount">Order Amount</label>
                                        <input type="text" class="form-control" id="amount" name="amount" placeholder="Order Amount" required>
                                    </div>
                                    <div class="form-group col-xl-8 col-md-12">
                                        <label for="product">Product Purchased</label>
                                        <input type="text" class="form-control" id="product" name="product" placeholder="Product" required>
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