<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
<div class="nk-tb-item nk-tb-head">
    
    
    <div class="nk-tb-col "><span class="sub-text">Store Name</span></div>
    {{-- <div class="nk-tb-col tb-col-mb"><span class="sub-text">Network</span></div> --}}
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Category</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Store Url</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Tracking Url</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Commission</span></div>
    <div class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($stores as $store)
<div class="nk-tb-item">
    
    
   
  
    <div class="nk-tb-col tb-col-md">
        <span><a href="{{route('admin.stores.show',$store)}}"><div class="tb-lead">{{$store->name}}</div></a></span><br>
        <span>{{$store->network->name}}</span>
    </div>
    
    <div class="nk-tb-col tb-col-md">
        <span>@foreach ($store->categories as $category)
            {{$category->name}},
        @endforeach</span>
    </div>
    <div class="nk-tb-col tb-col-md">
        <span>{{$store->store_url}}</span>
    </div>
    <div class="nk-tb-col tb-col-md">
        <span>{{$store->cashback->click_url ?? "#"}}</span>
    </div>
    <div class="nk-tb-col tb-col-md">
        <span>{{$store->cashback->sale_commission ?? ''}}</span>
    </div>
   
   
   
   
    <div class="nk-tb-col tb-col-md">
        {!! $store->status ? '<span class="tb-status text-success">active</span>' : '<span class="tb-status text-danger">inactive</span>'!!}
    </div>
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">
            {{-- <li class="nk-tb-action-hidden">
                <a href="#" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Wallet">
                    <em class="icon ni ni-wallet-fill"></em>
                </a>
            </li>
            <li class="nk-tb-action-hidden">
                <a href="#" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Send Email">
                    <em class="icon ni ni-mail-fill"></em>
                </a>
            </li>
            <li class="nk-tb-action-hidden">
                <a href="#" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Suspend">
                    <em class="icon ni ni-user-cross-fill"></em>
                </a>
            </li> --}}
            <li>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            <li><a href="{{route('admin.stores.edit', $store)}}"><em class="icon ni ni-edit"></em><span>Edit Store</span></a></li>
                            <li><a href="{{route('admin.stores.images', $store)}}"><em class="icon ni ni-eye"></em><span>View Store Images</span></a></li>
                    
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
        {!! $stores->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    