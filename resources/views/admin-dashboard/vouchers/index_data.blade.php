<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
<div class="nk-tb-item nk-tb-head">
    
    
    <div class="nk-tb-col "><span class="sub-text">Store Name</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Deal title</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Coupon</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Store Url</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Click Url</span></div>
    <div class="nk-tb-col tb-col-mb text-center"><span class="sub-text">Commission</span></div>
    {{-- <div class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></div> --}}
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($vouchers as $voucher)
<div class="nk-tb-item">
    
    
   
  
    <div class="nk-tb-col tb-col-md"><a href="{{route('admin.stores.show',$voucher->store ?? 0)}}">
        <span><b>{{$voucher->store->name ?? ''}}</b></span><br>
        <span>{{$voucher->store->network->name ?? ''}}</span></a>
    </div>
    
    <div class="nk-tb-col tb-col-md">
        <span>{{$voucher->link_name}}</span>
    </div>
 
    <div class="nk-tb-col tb-col-md">
        <span>{{$voucher->coupon_code}}</span>
    </div>
    <div class="nk-tb-col tb-col-md">
        <span>{{$voucher->store->store_url ?? ''}}</span>
    </div>
    <div class="nk-tb-col tb-col-md">
        <span>{{$voucher->click_url ?? "#"}}</span>
    </div>
    <div class="nk-tb-col tb-col-md text-center">
        <span>{{$voucher->sale_commission ?? ''}}</span>
    </div>
   
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">
           
            <li>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            <li><a href="{{route('admin.vouchers.edit', $voucher)}}"><em class="icon ni ni-edit"></em><span>Edit Voucher</span></a></li>
                            {{-- <li><a href="{{route('admin.stores.images', $store)}}"><em class="icon ni ni-eye"></em><span>View Store Images</span></a></li> --}}
                    
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div><!-- .nk-tb-item -->

@endforeach   
<div class="nk-block-between-md g-3 card-inner">
    <div class="pagination g" route="{{$route}}">
        {!! $vouchers->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    