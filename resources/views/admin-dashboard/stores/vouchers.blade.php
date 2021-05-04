<div class="nk-tb-list nk-tb-ulist" style="table-layout: auto">

    @if(count($store->vouchers))
    <div class="nk-tb-item nk-tb-head">
        
        
        <div class="nk-tb-col tb-col-mb pl-0"><span class="sub-text">Deal title</span></div>
        <div class="nk-tb-col tb-col-mb"><span class="sub-text">Coupon</span></div>
        
        <div class="nk-tb-col tb-col-mb"><span class="sub-text">Click Url</span></div>
        <div class="nk-tb-col tb-col-mb text-center"><span class="sub-text">Commission</span></div>
        <div class="nk-tb-col "><span class="sub-text">Expiry Date</span></div>
        <div class="nk-tb-col nk-tb-col-tools text-right pr-0">
            <span class="sub-text">Edit</span>
        
        </div>
    </div><!-- .nk-tb-item -->
    @foreach ($store->vouchers->take(20) as $voucher)
    <div class="nk-tb-item" >
        <div class="nk-tb-col  pl-1">
            <span>{{$voucher->link_name}}</span>
        </div>
    
        <div class="nk-tb-col ">
            <span>{{$voucher->coupon_code}}</span>
        </div>
    
        <div class="nk-tb-col ">
        <h5><a href='{{$voucher->click_url ?? "#"}}' target="_blank" data-toggle = 'tooltip'
            data-placement='top' title='{{$voucher->click_url ?? "#"}}'><em class="icon ni ni-link-alt"></em></a></h5>

        </div>
        <div class="nk-tb-col  text-center">
            <span>{{$voucher->sale_commission ?? ''}}</span>
        </div>
        <div class="nk-tb-col  text-center">
            <span>{{$voucher->promotion_end_date ?? ''}}</span>
        </div>
    
        <div class="nk-tb-col nk-tb-col-tools pr-0">
            <a href="" voucher-id='{{$voucher->id}}' class='voucher-edit'><em class="icon ni ni-edit"></em></a>
            
        </div>
    </div><!-- .nk-tb-item -->
    
    @endforeach   
                                
                        

@else
    <p>No vouchers found</p>
@endif
</div>