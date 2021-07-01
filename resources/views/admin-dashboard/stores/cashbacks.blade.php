
 @if($store->override_cashback)
<a href="#cashback-modal" class="btn btn-primary btn-sm float-right" data-toggle="modal"><em class="icon ni ni-upload-cloud"></em> <span>Add cashback</span></a>
@endif
<div class="nk-tb-list nk-tb-ulist" style="table-layout: auto">
    @if(count($store->cashbacks))
    <div class="nk-tb-item nk-tb-head">
        
        
        <div class="nk-tb-col tb-col-mb pl-0"><span class="sub-text">Network Commission</span></div>
        <div class="nk-tb-col tb-col-mb pl-0"><span class="sub-text">Cashback</span></div>
        <div class="nk-tb-col tb-col-mb"><span class="sub-text">Type</span></div>
        
        <div class="nk-tb-col tb-col-mb"><span class="sub-text">Detail</span></div>
         <div class="nk-tb-col nk-tb-col-tools text-right pr-0">
            <span class="sub-text">Edit</span>
           
        </div>
    </div><!-- .nk-tb-item -->
    @foreach ($store->cashbacks as $cashback)

    
    <div class="nk-tb-item">
        <div class="nk-tb-col  pl-1">
            <span>@if($cashback->type=='fixed'){{$cashback->currency}} @endif{{$cashback->sale_commission}}@if($cashback->type=='percentage')%@endif</span>
        </div>
        <div class="nk-tb-col  pl-1">
            <span>@if($cashback->type=='fixed'){{$cashback->currency}} @endif
                @if($store->custom_cashback_percentage)
                {{($store->custom_cashback_percentage/100)*$cashback->sale_commission}}
                @else 
                {{(SiteSetting()['cashback_percentage']/100)*$cashback->sale_commission}}
                @endif
                @if($cashback->type=='percentage')%@endif</span>
        </div>
     
        <div class="nk-tb-col text-capitalize">
            <span>{{$cashback->type}}</span>
        </div>
      
        <div class="nk-tb-col ">
            <span>{{$cashback->detail}}</span>
        </div>
       
       
        <div class="nk-tb-col nk-tb-col-tools pr-2 text-right">
            @if($store->override_cashback)
            <a href="" cashback-id='{{$cashback->id}}' class='cashback-edit'><em class="icon ni ni-edit"></em></a>
            @endif
        </div>
    </div><!-- .nk-tb-item -->
        
    @endforeach
    @else
    <p>No cashbacks found</p>
    
    @endif
</div>