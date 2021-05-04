<div class="nk-tb-list nk-tb-ulist mt-3" style="table-layout: auto">

    @if(count($user->clicks))
    <div class="nk-tb-item nk-tb-head">
   
    <div class="nk-tb-col pl-0"><span class="sub-text">Store</span></div>
    <div class="nk-tb-col tb-col-mb text-right"><span class="sub-text">Exit Url</span></div>
    <div class="nk-tb-col  text-right"><span class="sub-text">Time</span></div>
    <div class="nk-tb-col  text-right pr-1"><span class="sub-text">Status</span></div>
    </div><!-- .nk-tb-item -->
    @foreach ($user->clicks as $click)
    <div class="nk-tb-item">
    
        <div class="nk-tb-col pl-1">
            <span><b>{{$click->store->name ?? ''}}</b></span>
            
        </div>
        
        <div class="nk-tb-col text-right">
            <h5><a href='{{$click->exit_url ?? '#'}}' target="_blank"><em class="icon ni ni-link-alt"></em></a></h5>
    
            <span></span>
        </div>
        <div class="nk-tb-col text-right">
            <span>{{$click->created_at}}</span>
        </div>
        <div class="nk-tb-col text-right pr-1">
            {!! $click->cashback ? '<span class="tb-status text-success">converted</span>' : '<span class="tb-status text-danger">Not converted</span>'!!}
    
        </div>
        
    </div><!-- .nk-tb-item -->
    
    @endforeach   
                                
                        

@else
    <p>No clicks found</p>
@endif
</div>