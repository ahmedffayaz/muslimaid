@if($store->storeAddress()->count() == 0)
<a href="#" class="btn btn-primary float-right btn-sm add-address" data-toggle="modal"><em class="icon ni ni-plus"></em> <span>Add Address</span></a>
@endif
<div class="nk-tb-list nk-tb-ulist mt-3" style="table-layout: auto">

    @if(count($store->storeAddress))
    <div class="nk-tb-item nk-tb-head">
        
        
        <div class="nk-tb-col pl-0"><span class="sub-text">City</span></div>
        <div class="nk-tb-col"><span class="sub-text">Postal Code</span></div>
        <div class="nk-tb-col"><span class="sub-text">Latitude</span></div>
        <div class="nk-tb-col"><span class="sub-text">Longitude</span></div>
        <div class="nk-tb-col "><span class="sub-text">Address</span></div>
        <div class="nk-tb-col nk-tb-col-tools pr-0">
            <span class="sub-text">Edit</span>
        
        </div>
    </div><!-- .nk-tb-item -->
    @foreach ($store->storeAddress->take(20) as $address)
    <div class="nk-tb-item" >
        <div class="nk-tb-col  pl-1">
            <span>{{$address->city}}</span>
        </div>
    
        <div class="nk-tb-col ">
            <span>{{$address->postal_code}}</span>
        </div>
        <div class="nk-tb-col ">
            <span>{{$address->latitude}}</span>
        </div>
        <div class="nk-tb-col ">
            <span>{{$address->longitude}}</span>
        </div>
        <div class="nk-tb-col ">
            <span>{{$address->address}}</span>
        </div>
    
        <div class="nk-tb-col nk-tb-col-tools pr-0">
            <a href="" address-id='{{$address->id}}' class='address-edit a_link'><em class="icon ni ni-edit"></em></a>
            <a href="" address-delete-id={{$address->id}} class="delete-address"><em class="icon ni ni-trash"></em><span></span></a>
        </div>
    </div><!-- .nk-tb-item -->
    
    @endforeach   
                                
                        

@else
    <p>No address found</p>
@endif
</div>