<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
<div class="nk-tb-item nk-tb-head">
    
    
    <div class="nk-tb-col "><span class="sub-text">Store ID</span></div>
    <div class="nk-tb-col " ><span class="sub-text">Store Name</span></div>
    {{-- <div class="nk-tb-col "><span class="sub-text">Network</span></div> --}}
    {{-- <div class="nk-tb-col "><span class="sub-text">Category</span></div> --}}
    <div class="nk-tb-col "><span class="sub-text">Store Url</span></div>
    {{-- <div class="nk-tb-col "><span class="sub-text">Tracking Url</span></div> --}}
    <div class="nk-tb-col "><span class="sub-text">Commission</span></div>
    <div class="nk-tb-col  text-center"><span class="sub-text">Cashbacks</span></div>
    <div class="nk-tb-col text-center"><span class="sub-text">Status</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($stores as $store)
<div class="nk-tb-item">
    
    
   
    <div class="nk-tb-col">
        <span><a href="{{route('admin.stores.show_store')}}?slug={{$store->slug}}"><div class="tb-lead">{{$store->id}}
            <img width="40px" class="float-right" @if($store->logo->first())
            src="{{asset('storage/stores/images/'.$store->logo->first()->image)}}"
            @else
            src="{{asset('frontend/images/products/product-16.jpg')}}" @endif alt=""></div></a></span><br>
        
    </div>
    <div class="nk-tb-col">
        <span><a href="{{route('admin.stores.show_store')}}?slug={{$store->slug}}"><div class="tb-lead">{{$store->name}} </div></a></span>
    <span>{{$store->network->name}}</span>
    </div>
   
    
    
    <div class="nk-tb-col">
        <h5><a href='{{$store->store_url}}' target="_blank" class="a_link"><em class="icon ni ni-link-alt"></em></a></h5>
    </div>
    
    
    <div class="nk-tb-col">@if(isset($store->cashback->type))
        <span>@if($store->cashback->type=='fixed'){{$store->cashback->currency}} @endif {{$store->cashback->sale_commission ?? ''}}@if($store->cashback->type=='percentage')%@endif </span>
   @endif
    </div>
    <div class="nk-tb-col text-center">
        <span>{{count($store->cashbacks)}}</span>
    </div>
   
   
   
   
    <div class="nk-tb-col text-center">
        @if($store->status == 'error') 
        <span class="badge badge-danger text-capitalize" data-toggle="tooltip" data-placement="top" title="{{$store->status_description}}">
            {{$store->status}}
        </span>
        @elseif($store->status == 'active')
        <span class="badge badge-success text-capitalize">
            {{$store->status}}
        </span>
        @elseif($store->status == 'pending review')
        <span class="badge badge-info text-capitalize">
            {{$store->status}}
        </span>
        @elseif($store->status == 'disabled' || $store->status == 'closed')
        <span class="badge badge-warning text-capitalize">
            {{$store->status}}
        </span>
        @endif
        
       
    </div>
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">
            <li>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            <li><a href="{{route('admin.stores.show_store')}}?slug={{$store->slug}}"><em class="icon ni ni-edit"></em><span>Edit Store</span></a></li>
                            {{-- <li><a href="{{route('admin.stores.images', $store)}}"><em class="icon ni ni-eye"></em><span>View Store Images</span></a></li> --}}
                            <li><a  onclick="$('#delete-store-{{$store->id}}').submit();"  style="cursor: pointer"> <em class="icon ni ni-trash-fill"></em><span>Delete Store</span></a>
                                                    
                            <form action="{{ route('admin.stores.destroy', $store) }}" id="delete-store-{{$store->id}}" method="POST" class="m-0">
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
        {!! $stores->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    