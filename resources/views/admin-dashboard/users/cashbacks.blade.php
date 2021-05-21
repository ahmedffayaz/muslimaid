<div class="nk-tb-list nk-tb-ulist mt-3" style="table-layout: auto">

    @if(count($cashbacks))
    <div class="nk-tb-item nk-tb-head">
        
        
    
    <div class="nk-tb-col pl-0"><span class="sub-text">Store</span></div>
    {{-- <div class="nk-tb-col text-center"><span class="sub-text">Order Value ({{ Config::get('currency') }})</span></div> --}}
    {{-- <div class="nk-tb-col text-center"><span class="sub-text">Network Commission ({{ Config::get('currency') }})</span></div> --}}
    <div class="nk-tb-col tb-col-mb text-center"><span class="sub-text">Network Commission ({{ Config::get('currency') }})</span></div>
    <div class="nk-tb-col tb-col-mb text-center"><span class="sub-text">Cashback ({{ Config::get('currency') }})</span></div>
    <div class="nk-tb-col  text-center"><span class="sub-text">Exit Click Id</span></div>
    <div class="nk-tb-col  text-center"><span class="sub-text">Event Time</span></div>
    <div class="nk-tb-col  text-right"><span class="sub-text">Status</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right pr-0">
        <span class="sub-text">Action</span>
       
    </div>
    </div><!-- .nk-tb-item -->
    @foreach ($cashbacks as $commission)
    <div class="nk-tb-item">
    
    
        <div class="nk-tb-col pl-1">
            <span><b>{{$commission->store->name ?? ''}}</b></span>
            
        </div>
        
        <div class="nk-tb-col  text-center">
            <span><span class="currency">{{ Config::get('currency') }} </span>{{$commission->network_commission}}</span>
        </div>
        <div class="nk-tb-col  text-center">
            <span><span class="currency">{{ Config::get('currency') }} </span>{{$commission->amount}}</span>
        </div>
        <div class="nk-tb-col  text-center">
            <span>{{$commission->exit_click_id}}</span>
        </div>
        <div class="nk-tb-col  text-right">
            <span>{{$commission->event_date}}</span>
        </div>
        <div class="nk-tb-col  text-right">
            <span class="tb-status text-info"> {{ $commission->statusMap->status ?? $commission->status}}</span>
        </div>
        
       
      
        <div class="nk-tb-col nk-tb-col-tools pr-1">
            <ul class="nk-tb-actions gx-1">
               
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                <li><a href="{{route('admin.commissions.edit', $commission)}}"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
      
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div><!-- .nk-tb-item -->
    
    @endforeach   
    <div class="nk-block-between-md px-0 card-inner">
        <div class="pagination g" route="" id="cashback-paginate">
            {!!$cashbacks->links()!!}                             
                             
            </div> 
        
        
    </div><!-- .nk-block-between --> 
                                
                        

@else
    <p>No cashbacks found</p>
@endif
</div>