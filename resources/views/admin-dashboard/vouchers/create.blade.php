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
                                <h4 class="title nk-block-title">Add Voucher</h4>
                                <div class="nk-block-des">
                                    <p>You can make style out your....</p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                    <h5 class="card-title">Voucher</h5>
                                </div>
                                <form action="{{route('admin.vouchers.store')}}" class="gy-3 form-validate is-alter voucher_form" method="POST">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="full-name-1">Title</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="full-name-1" name="link_name" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="default-06">Store</label>
                                                <div class="form-control-wrap ">
                                                    
                                                        <select class="form-select form-control" data-search="on" id="default-06" name="store_id" required>
                                                            @foreach ($stores as $store)
                                                            <option value="{{$store->id}}">{{$store->name}}</option>
                                                            @endforeach
                                                        </select>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="pay-amount-1">Click url</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="pay-amount-1" name="click_url" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="phone-no-1">Destination url</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="phone-no-1" name="destination" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <input name="description" type="hidden">
                                                <label class="form-label" for="phone-no-1">Description</label>
                                                <!-- Create the editor container -->
                                                <div  id="editor-container">
                                                  
                                                </div>
                                               
                                            </div>
                                        </div>
                                       
                                       
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="pay-amount-1">Promotion Type</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="pay-amount-1" value="" name="promotion_type" required>
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="pay-amount-1">Link ID</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="pay-amount-1" value="" name="link_id" required>
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="pay-amount-1">Coupon Code</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="pay-amount-1" value="" name="coupon_code" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="pay-amount-1">Sale commission</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="pay-amount-1" value="" name="sale_commission" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="pay-amount-1">Promotion Start Date</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control date-picker" id="pay-amount-1" value="" name="promotion_start_date" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="pay-amount-1">Promotion End Date</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control date-picker" id="pay-amount-1" value="" name="promotion_end_date" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                                               
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
<link rel="stylesheet" href="{{ asset('admin-dashboard/css/editors/quill.css?ver=2.2.0')}}">
    <script src="{{ asset('admin-dashboard/js/libs/editors/quill.js?ver=2.2.0')}}"></script>
    <script src="{{ asset('admin-dashboard/js/editors.js?ver=2.2.0')}}"></script>
    <script>
    var quill = new Quill('#editor-container', {
        modules: {
          toolbar: [
            ['bold', 'italic'],
            ['link', 'blockquote', 'code-block', 'image'],
            [{ list: 'ordered' }, { list: 'bullet' }]
          ]
        },
        placeholder: 'Compose an epic...',
        theme: 'snow'
      });
      
    //   var form = document.querySelector('form');
      $(".voucher_form").submit(function(e) {
          
        // Populate hidden form on submit
        var desc = document.querySelector('input[name=description]');
        desc.value = quill.root.innerHTML;
       
        
      });</script>
@endpush