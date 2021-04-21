@extends('layouts.admin-dashboard.app')
@section('content')
    

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview mx-auto">
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h4 class="title nk-block-title">Add Cashback</h4>
                                <div class="nk-block-des">
                                    <p>You can make style out your....</p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                    <h5 class="card-title">Cashback Info</h5>
                                </div>
                                <form action="{{route('admin.commissions.store_multiple')}}" class="gy-3 form-validate is-alter" method="POST">
                                    @csrf
                                    @method('POST')
                                <div class="fields-container">
                                    <div class="row ">
                                        <div class="col-lg-12 ml-auto mt-3">
                                            <span class="delete-row float-right"><em class="icon ni ni-cross-circle-fill text-danger"></em></span>

                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label class="form-label" for="exit_click_id">Exit Click</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-select form-control select-2"  data-search="on" name="exit_click_id[]" required>
                                                            <option value="0" disabled selected>Select Exit Click</option>
                                                           
                                                            @foreach ($clicks as $click)
                                                            <option value="{{$click->id}}">({{$click->id}}) ({{$click->user->first_name}} {{$click->user->last_name}}) ({{$click->store->name}})</option>
                                                                
                                                            @endforeach
                                                        </select>
                                                    </div>
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
                                        
                                    
                                       
                                       
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-12 ml-auto mt-3">
                                            <span class="delete-row float-right"><em class="icon ni ni-cross-circle-fill text-danger"></em></span>

                                        </div>

                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label class="form-label" for="exit_click_id">Exit Click</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-select form-control select-2" data-search="on" name="exit_click_id[]" required>
                                                            <option value="0" disabled selected>Select Exit Click</option>
                                                           
                                                            @foreach ($clicks as $click)
                                                            <option value="{{$click->id}}">({{$click->id}}) ({{$click->user->first_name}} {{$click->user->last_name}}) ({{$click->store->name}})</option>
                                                                
                                                            @endforeach
                                                        </select>
                                                    </div>
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
                                                        <select class="form-control form-select select-2"  name="status[]" required>
                                                            @foreach ($statuses as $status)
                                                            <option value="{{$status->id}}">{{$status->status}}</option>
                                                                
                                                            @endforeach
                                                            
                                                                
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                       
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-12 ml-auto mt-3">
                                            <span class="delete-row float-right"><em class="icon ni ni-cross-circle-fill text-danger"></em></span>

                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label class="form-label" for="exit_click_id">Exit Click</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-select form-control select-2"  data-search="on" name="exit_click_id[]" required>
                                                            <option value="0" disabled selected>Select Exit Click</option>
                                                           
                                                            @foreach ($clicks as $click)
                                                            <option value="{{$click->id}}">({{$click->id}}) ({{$click->user->first_name}} {{$click->user->last_name}}) ({{$click->store->name}})</option>
                                                                
                                                            @endforeach
                                                        </select>
                                                    </div>
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
                                                        <select class="form-control form-select select-2"  name="status[]" required>
                                                            @foreach ($statuses as $status)
                                                            <option value="{{$status->id}}">{{$status->status}}</option>
                                                                
                                                            @endforeach
                                                            
                                                                
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-12 ml-auto mt-3">
                                            <span class="delete-row float-right"><em class="icon ni ni-cross-circle-fill text-danger"></em></span>

                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label class="form-label" for="exit_click_id">Exit Click</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-select form-control select-2"  data-search="on" name="exit_click_id[]" required>
                                                            <option value="0" disabled selected>Select Exit Click</option>
                                                           
                                                            @foreach ($clicks as $click)
                                                            <option value="{{$click->id}}">({{$click->id}}) ({{$click->user->first_name}} {{$click->user->last_name}}) ({{$click->store->name}})</option>
                                                                
                                                            @endforeach
                                                        </select>
                                                    </div>
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
                                                        <select class="form-control form-select select-2"  name="status[]" required>
                                                            @foreach ($statuses as $status)
                                                            <option value="{{$status->id}}">{{$status->status}}</option>
                                                                
                                                            @endforeach
                                                            
                                                                
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                       
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-12 ml-auto mt-3 mt-3">
                                            <span class="delete-row float-right"><em class="icon ni ni-cross-circle-fill text-danger"></em></span>

                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label class="form-label" for="exit_click_id">Exit Click</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-select form-control select-2"  data-search="on" name="exit_click_id[]" required>
                                                            <option value="0" disabled selected>Select Exit Click</option>
                                                           
                                                            @foreach ($clicks as $click)
                                                            <option value="{{$click->id}}">({{$click->id}}) ({{$click->user->first_name}} {{$click->user->last_name}}) ({{$click->store->name}})</option>
                                                                
                                                            @endforeach
                                                        </select>
                                                    </div>
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
                                                        <select class="form-control form-select select-2"  name="status[]" required>
                                                            @foreach ($statuses as $status)
                                                            <option value="{{$status->id}}">{{$status->status}}</option>
                                                                
                                                            @endforeach
                                                            
                                                                
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                    </div>
                                </div>
                                <span class="btn btn-sm btn-success float-right" onclick="addRows();">Add Row</span>
                                <div class="row ">
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
                                <div class="form-control-select">
                                    <select class="form-select form-control select-2"  data-search="on" name="exit_click_id[]" required>
                                        <option value="0" disabled selected>Select Exit Click</option>
                                    
                                        @foreach ($clicks as $click)
                                        <option value="{{$click->id}}">({{$click->id}}) ({{$click->user->first_name}} {{$click->user->last_name}}) ({{$click->store->name}})</option>
                                            
                                        @endforeach
                                    </select>
                                </div>
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
    </script>
@endpush