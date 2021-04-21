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
                                <h4 class="title nk-block-title">Edit Cashback</h4>
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
                                <form action="{{route('admin.commissions.update', $commission)}}" class="gy-3 form-validate is-alter" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-4">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-label" for="default-06">Exit Click</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-select" data-placeholder="Select user" data-search="on" name="exit_click_id" required>
                                                            <option value="0" disabled selected>Select Exit Click</option>
                                                           
                                                            @foreach ($clicks as $click)

                                                            <option @if($click->id == $commission->exit_click_id ) selected @endif value="{{$click->id}}">({{$click->id}}) ({{$click->user->first_name}} {{$click->user->last_name}}) ({{$click->store->name}})</option>
                                                                
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="phone-no-1">Order Value</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="phone-no-1" value="{{$commission->order_value}}" name="order_value">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="phone-no-1">Network Commission</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="phone-no-1" value="{{$commission->network_commission}}" name="network_commission" >
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="phone-no-1">Cashback Amount</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="phone-no-1" value="{{$commission->amount}}" name="amount" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="default-06">Status</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-control form-select" name="status" required>
                                                            @foreach ($statuses as $status)
                                                            <option @if($status->id == $commission->status) selected @endif value="{{$status->id}}">{{$status->status}}</option>
                                                                
                                                            @endforeach
                                                            
                                                                
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                       
                                
                                       
                                       
                                        <div class="col-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-lg btn-primary">Update</button>
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