<a href="#add-seorule-modal" class="btn btn-primary float-right btn-sm" data-toggle="modal"><em class="icon ni ni-plus"></em> <span>Add SEO Rule</span></a>

<div class="nk-tb-list nk-tb-ulist mt-3" style="table-layout: auto">
    @if($store)
    <div class="nk-tb-item nk-tb-head"> 
        <div class="nk-tb-col pl-0"><span class="sub-text">Key</span></div>
        <div class="nk-tb-col"><span class="sub-text">Value</span></div>
        <div class="nk-tb-col"><span class="sub-text">Action</span></div>
        </div><!-- .nk-tb-item -->
        @foreach ($store->storeRuleData->take(20) as $seo)
        <div class="nk-tb-item" >
            <div class="nk-tb-col  pl-1">
                <span>{{ $seo->key }}</span>
            </div>
        
            <div class="nk-tb-col ">
                <span>{{ $seo->value }}</span>
            </div>  
            <div class="nk-tb-col ">
                <a href="" seo-id='{{$seo->id}}' class='seo-edit a_link'><em class="icon ni ni-edit"></em></a>
                <a href="" seo_delete-id={{$seo->id}} class="delete-seo"><em class="icon ni ni-trash"></em><span></span></a>
                
            </div>
    </div><!-- .nk-tb-item -->   
    @endforeach   
     
@else
    <p>No seo rule found</p>
@endif
</div>