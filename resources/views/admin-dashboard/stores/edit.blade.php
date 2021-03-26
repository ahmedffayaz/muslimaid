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
                                <h4 class="title nk-block-title">Edit Store</h4>
                                <div class="nk-block-des">
                                    <p>You can make style out your....</p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                    <h5 class="card-title">Store Info</h5>
                                </div>
                                <form action="{{route('admin.stores.update', $store)}}" class="gy-3 form-validate is-alter" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="full-name-1">Store Name</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="full-name-1" value="{{$store->name}}" name="store_name" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="default-06">Network</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-control" id="default-06" name="network_id" required>
                                                            @foreach ($networks as $network)
                                                            <option @if($store->network_id == $network->id) selected @endif value="{{$network->id}}">{{$network->name}}</option>
                                                                
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="default-06">Category</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-select" multiple="multiple" data-placeholder="Select Multiple options" class="form-control" id="default-06" name="category_id[]" required>
                                                            @foreach ($categories as $category)
                                                            <option 
                                                            @if(in_array($category->id, $store->categories->pluck('id')->toArray())) selected @endif 
                                                            value="{{$category->id}}">{{$category->name}}
                                                            </option>
                                                                
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="phone-no-1">Tracking url</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="phone-no-1" value="{{$store->tracking_url}}" name="tracking_url" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="pay-amount-1">Store url</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="pay-amount-1" value="{{$store->store_url}}" name="store_url" required>
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