<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
<div class="nk-tb-item nk-tb-head">
    
    
    <div class="nk-tb-col "><span class="sub-text">Store ID</span></div>
    <div class="nk-tb-col "><span class="sub-text">Store Name</span></div>
    {{-- <div class="nk-tb-col "><span class="sub-text">Network</span></div> --}}
    {{-- <div class="nk-tb-col "><span class="sub-text">Category</span></div> --}}
    <div class="nk-tb-col "><span class="sub-text">Store Url</span></div>
    {{-- <div class="nk-tb-col "><span class="sub-text">Tracking Url</span></div> --}}
    <div class="nk-tb-col "><span class="sub-text">Commission</span></div>
    <div class="nk-tb-col  text-center"><span class="sub-text">Editor Pick Category</span></div>
    <div class="nk-tb-col text-center"><span class="sub-text">Status</span></div>
    {{-- <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div> --}}
</div><!-- .nk-tb-item -->
@foreach ($picks as $pick)
<div class="nk-tb-item">
    
    
   
    <div class="nk-tb-col">
        <span><a href="{{route('admin.stores.show',$pick)}}"><div class="tb-lead">{{$pick->id}}</div></a></span><br>
        
    </div>
    <div class="nk-tb-col">
        <span><a href="{{route('admin.stores.show',$pick)}}"><div class="tb-lead">{{$pick->name}}</div></a></span>
        <span>{{$pick->network->name}}</span>
    </div>
   
    
    
    <div class="nk-tb-col">
        <h5><a href='{{$pick->store_url}}' target="_blank"><em class="icon ni ni-link-alt"></em></a></h5>
    </div>
    
    
    <div class="nk-tb-col">@if(isset($pick->cashback->type))
        <span>@if($pick->cashback->type=='fixed'){{$pick->cashback->currency}} @endif {{$pick->cashback->sale_commission ?? ''}}@if($pick->cashback->type=='percentage')%@endif </span>
   @endif
    </div>
    <div class="nk-tb-col text-center">
        <span>@foreach ($pick->editorPicks as $pickcat)
            {{$pickcat->category->name}},
        @endforeach</span>
    </div>
   
   
   
   
    <div class="nk-tb-col text-center">
        @if($pick->status == 'error') 
        <span class="badge badge-danger text-capitalize" data-toggle="tooltip" data-placement="top" title="{{$pick->status_description}}">
            {{$pick->status}}
        </span>
        @elseif($pick->status == 'active')
        <span class="badge badge-success text-capitalize">
            {{$pick->status}}
        </span>
        @elseif($pick->status == 'pending review')
        <span class="badge badge-info text-capitalize">
            {{$pick->status}}
        </span>
        @elseif($pick->status == 'disabled' || $pick->status == 'closed')
        <span class="badge badge-warning text-capitalize">
            {{$pick->status}}
        </span>
        @endif        
    </div>
    {{-- <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">
            <li>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            <li><a href="{{route('admin.stores.show', $pick)}}"><em class="icon ni ni-edit"></em><span>Edit Store</span></a></li>
                            <li><a  onclick="$('#delete-store-{{$pick->id}}').submit();"  style="cursor: pointer"> <em class="icon ni ni-trash-fill"></em><span>Delete Store</span></a>
                                                    
                            <form action="{{ route('admin.stores.destroy', $pick) }}" id="delete-store-{{$pick->id}}" method="POST" class="m-0">
                                @method('DELETE')
                                @csrf
                                
                            </form>
                        </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div> --}}
</div><!-- .nk-tb-item -->

@endforeach   
<div class="nk-block-between-md g-3 card-inner">
    <div class="pagination g" route="{{$route}}">
        {!! $picks->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    