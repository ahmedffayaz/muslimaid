@extends('layouts.admin-dashboard.app')
@section('content')
    

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h4 class="title nk-block-title">Payment Info</h4>
                                <div class="nk-block-des">
                                    <p>You can make style out your....</p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                    <h5 class="card-title">Payment Info</h5>
                                </div>
                                <form action="{{route('admin.users.payment_save')}}" class="gy-3 form-validate is-alter" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="default-06">Payment Method</label>
                                                <div class="form-control-wrap ">
                                                    <div class="">
                                                        <select class="form-control form-select" id="payment_method" name="payment_method" required>
                                                            @php
                                                               $method =  $user->paymentInfo->payment_method ?? '';
                                                            @endphp
                                                            <option value="paypal" @if($method ==  'paypal') selected @endif>Paypal</option>
                                                            <option value="bank" @if($method ==  'bank') selected @endif>Bank</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 paypal-email">
                                            <div class="form-group">
                                                <label class="form-label" for="paypal_email">Paypal Email</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="paypal_email" name="paypal_email" required value="{{$user->paymentInfo->paypal_email ?? ''}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g4 bank">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="account_name">Account Name</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="account_name" name="account_name" required value="{{$user->paymentInfo->account_name ?? ''}}"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="bank_title">Bank Title</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="bank_title" name="bank_title" required value="{{$user->paymentInfo->bank_title ?? ''}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="account_number">Accont Number</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="account_number" name="account_number" required value="{{$user->paymentInfo->account_number ?? ''}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="bank_sort_code">
                                                    Bank Sort Code</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="bank_sort_code" name="bank_sort_code" required value="{{$user->paymentInfo->bank_sort_code ?? ''}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="bic">BIC</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="bic" name="bic" required value="{{$user->paymentInfo->bic ?? ''}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                      <div class="row g-4">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-lg btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!-- .nk-block -->
                    
                  
                    
                </div><!-- .components-preview -->
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    $('.bank').hide();

//     $(document).ready(function() {
//     alert($("#payment_method").val());
// });

    $(document).ready(function() {
        if ($('#payment_method').val() == 'bank') {
    $('.paypal-email').hide();
    $('.bank').show();
    $('#account_name').attr('required', 'required');
    $('#bank_title').attr('required', 'required');
    $('#account_number').attr('required', 'required');
    $('#bank_sort_code').attr('required', 'required');
    $('#bic').attr('required', 'required');
    $('#paypal_email').removeAttr('required');
    $('#first_name').removeAttr('required');
    $('#last_name').removeAttr('required');
    $('#address').removeAttr('required');
    $('#city').removeAttr('required');
    $('#postcode').removeAttr('required');

}
else if ($('#payment_method').val() == 'paypal') {
    $('.paypal-email').show();
    $('.bank').hide();
    $('#paypal_email').attr('required', 'required');
    $('#first_name').removeAttr('required');
    $('#last_name').removeAttr('required');
    $('#address').removeAttr('required');
    $('#city').removeAttr('required');
    $('#postcode').removeAttr('required');
    $('#account_name').removeAttr('required');
    $('#bank_title').removeAttr('required');
    $('#account_number').removeAttr('required');
    $('#bank_sort_code').removeAttr('required');
    $('#bic').removeAttr('required');


}

});
    
    $(document.body).on("change","#payment_method",function(){
        if (this.value == 'bank') {
    $('.paypal-email').hide();
    $('.bank').show();
    $('#account_name').attr('required', 'required');
    $('#bank_title').attr('required', 'required');
    $('#account_number').attr('required', 'required');
    $('#bank_sort_code').attr('required', 'required');
    $('#bic').attr('required', 'required');
    $('#paypal_email').removeAttr('required');
    $('#first_name').removeAttr('required');
    $('#last_name').removeAttr('required');
    $('#address').removeAttr('required');
    $('#city').removeAttr('required');
    $('#postcode').removeAttr('required');

}
else if (this.value == 'paypal') {
    $('.paypal-email').show();
    $('.bank').hide();
    $('#paypal_email').attr('required', 'required');
    $('#first_name').removeAttr('required');
    $('#last_name').removeAttr('required');
    $('#address').removeAttr('required');
    $('#city').removeAttr('required');
    $('#postcode').removeAttr('required');
    $('#account_name').removeAttr('required');
    $('#bank_title').removeAttr('required');
    $('#account_number').removeAttr('required');
    $('#bank_sort_code').removeAttr('required');
    $('#bic').removeAttr('required');


}
});

</script>   
@endpush