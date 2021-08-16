<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
@if(count($vouchers))
<div class="nk-tb-item nk-tb-head" >
    
    
    <div class="nk-tb-col "><span class="sub-text">Store</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Deal title</span></div>
    <div class="nk-tb-col tb-col-mb text-center"><span class="sub-text">Coupon</span></div>
    <div class="nk-tb-col tb-col-mb text-center"><span class="sub-text">Click Url</span></div>
    <div class="nk-tb-col tb-col-mb text-center"><span class="sub-text">Commission</span></div>
    {{-- <div class="nk-tb-col "><span class="sub-text">Status</span></div> --}}
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($vouchers as $voucher)
<div class="nk-tb-item" @if ($voucher->promotion_end_date < \Carbon\Carbon::now())
    style="background-color:#f1f1f1"
    @endif>
    
    
   
  
    <div class="nk-tb-col "><a href="{{route('admin.stores.show_store')}}?slug={{$voucher->store->slug}}" class="a_link">
        <span><b>{{$voucher->store->id ?? ''}} - {{$voucher->store->name ?? ''}}</b></span><br>
        <span>{{$voucher->store->network->name ?? ''}}</span></a><br>
        @if ($voucher->promotion_end_date < \Carbon\Carbon::now())
    <span class="text-danger">Expired</span>
    @endif
    </div>
    
    <div class="nk-tb-col ">
        <span>{{$voucher->link_name}}</span>
    </div>
 
    <div class="nk-tb-col text-center ">
        <span>{{$voucher->coupon_code}}</span>
    </div>
   
    <div class="nk-tb-col text-center">
        <h5><a href='{{$voucher->click_url ?? "#"}}' target="_blank" class="a_link"><em class="icon ni ni-link-alt"></em></a></h5>

    </div>
    <div class="nk-tb-col  text-center">
        <span>{{$voucher->sale_commission ?? ''}}</span>
    </div>
   
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">
           
            <li>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            <li><a class="edit-voucher" href="{{route('admin.vouchers.edit', $voucher)}}"><em class="icon ni ni-edit"></em><span>Edit Voucher</span></a></li>
                            {{-- <li><a href="{{route('admin.stores.images', $store)}}"><em class="icon ni ni-eye"></em><span>View Store Images</span></a></li> --}}
                            <li><a class='delete' form_id = "delete-voucher-{{$voucher->id}}" style="cursor: pointer"> <em class="icon ni ni-trash-fill"></em><span>Delete Voucher</span></a>
                                                    
                                <form action="{{ route('admin.vouchers.destroy', $voucher) }}" id="delete-voucher-{{$voucher->id}}" method="POST" class="m-0">
                                    @method('DELETE')
                                    @csrf
                                    
                                </form>
                            </li>
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
@else 
    <h3 class="m-auto text-center py-5">No results found</h3> 
@endif 