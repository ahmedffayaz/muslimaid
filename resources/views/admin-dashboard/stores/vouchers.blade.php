
<a href="#add-voucher-modal" class="btn btn-primary float-right btn-sm" data-toggle="modal"><em class="icon ni ni-plus"></em> <span>Add Voucher</span></a>

<div class="nk-tb-list nk-tb-ulist mt-3" style="table-layout: auto">

    @if(count($store->vouchers))
    <div class="nk-tb-item nk-tb-head">
        
        
        <div class="nk-tb-col pl-0"><span class="sub-text">Name</span></div>
        <div class="nk-tb-col"><span class="sub-text">Coupon</span></div>
        
        <div class="nk-tb-col"><span class="sub-text">Deeplink URL</span></div>
        <div class="nk-tb-col "><span class="sub-text">Expiry Date</span></div>
        <div class="nk-tb-col nk-tb-col-tools pr-0">
            <span class="sub-text">Edit</span>
        
        </div>
    </div><!-- .nk-tb-item -->
    @foreach ($store->vouchers->take(20) as $voucher)
    <div class="nk-tb-item" >
        <div class="nk-tb-col  pl-1">
            <span>{{$voucher->name}}</span>
        </div>
    
        <div class="nk-tb-col ">
            <span>{{$voucher->coupon_code}}</span>
        </div>
    
        <div class="nk-tb-col ">
        <h5><a href='{{$voucher->deeplink_url ?? "#"}}' target="_blank" data-toggle = 'tooltip'
            data-placement='top' title='{{$voucher->deeplink_url ?? "#"}}' class="a_link"><em class="icon ni ni-link-alt"></em></a></h5>

        </div>
        <div class="nk-tb-col ">
            <span>{{Carbon\Carbon::parse($voucher->promotion_end_date)->isoFormat('Do MMMM YYYY') ?? ''}}
                </span>
        </div>
    
        <div class="nk-tb-col nk-tb-col-tools pr-0">
            <a href="" voucher-id='{{$voucher->id}}' class='voucher-edit a_link'><em class="icon ni ni-edit"></em></a>
            <a href="" voucher-delete-id="{{$voucher->id}}" class="delete-voucher"><em class="icon ni ni-trash"></em><span></span></a>

        </div>
    </div><!-- .nk-tb-item -->
    
    @endforeach   
                                
                        

@else
    <p>No vouchers found</p>
@endif
</div>