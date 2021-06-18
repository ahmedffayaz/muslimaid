<style>.status {
    position: absolute;
    top: 0;
    right: 0;
}
.store-header{
    display: flex;
    justify-content: center;
    align-items: center;
    
    height: 220px
}
.store-header  .drodown {
    position: absolute;
    top: 10px;
    left: 5px;
}
.project-title .title a {
    font-size: 14px;
    color: black;
}.project-title .title a:hover {

    color: #9769ff;
}.card-img, .card-img-top {
    max-height: 150px;
    max-width: 150px;
}
.card-header {
    background-color: floralwhite;
}
</style>
@foreach ($stores as $store)
<div class="col-sm-6 col-lg-3 col-xxl-3">
    <div class="card h-100">
        <div class="card-header store-header">
            <div class="drodown">
                <a href="#" class="dropdown-toggle btn btn-sm btn-icon btn-trigger mt-n1 mr-n1" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <ul class="link-list-opt no-bdr">
                        <li><a href="{{route('admin.stores.show', $store)}}"><em class="icon ni ni-edit"></em><span>Edit Store</span></a></li>
                        {{-- <li><a href="{{route('admin.stores.images', $store)}}"><em class="icon ni ni-eye"></em><span>View Store Images</span></a></li> --}}
                        <li><a  onclick="$('#delete-store-{{$store->id}}').submit();"  style="cursor: pointer"> <em class="icon ni ni-trash-fill"></em><span>Delete Store</span></a>
                                                
                        <form action="{{ route('admin.stores.destroy', $store) }}" id="delete-store-{{$store->id}}" method="POST" class="m-0">
                            @method('DELETE')
                            @csrf
                            
                        </form>
                    </li>
                    </ul>
                </div>
            </div><a href="{{route('admin.stores.show', $store)}}"><img @if($store->logo->first())
                src="{{asset('storage/stores/images/'.$store->logo->first()->image)}}"
                @else
                src="{{asset('frontend/images/products/product-16.jpg')}}" @endif class="card-img-top" alt=""></a>
            
            <div class="status">@if($store->status == 'error') 
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
                @endif</div>
        </div>
      
        <div class="card-inner">
            <div class="project">
                <div class="project-head mb-0">
                    
                    <span  class="project-title">
                        <div class="project-info">
                            <h6 class="title mb-2"><a href="{{route('admin.stores.show', $store)}}">{{$store->id}} - {{$store->name}}</a></h6>
                            <div>
                                <span>Network: {{$store->network->name}}</span>
                            </div>
                           <div> Commission: 
                            @if(isset($store->cashback->type))
                                <span>@if($store->cashback->type=='fixed'){{$store->cashback->currency}} @endif {{$store->cashback->sale_commission ?? ''}}@if($store->cashback->type=='percentage')%@endif </span>
                            @endif
                           </div>
                           <div>
                            Clicks: {{$store->clicks->count()}}
                           </div>
                            
                            
                        </div>
                    </span>
                </div>
                {{-- <div class="project-details">
                    <p>{{$slide->description}}</p>
                    <p>@if($slide->store->cashback->type=='fixed'){{$slide->store->cashback->currency}} @endif{{$slide->store->cashback->sale_commission}}@if($slide->store->cashback->type=='percentage')%@endif Cashback</p>
                </div> --}}
               
                <div class="project-meta">
                    {{-- <a  href="{{route('admin.stores.show', $store)}}"><em class="icon ni ni-edit text-primary"></em></a>

                    <a href="" onclick="$('#delete-store-{{$store->id}}').submit();"  style="cursor: pointer"> <em class="icon ni ni-trash-fill text-danger"></em></a>
                                                    
                        <form action="{{ route('admin.stores.destroy', $store) }}" id="delete-store-{{$store->id}}" method="POST" class="m-0">
                            @method('DELETE')
                            @csrf
                            
                        </form> --}}
                </div>
            </div>
        </div>
    </div>
</div>

@endforeach   
<div class="nk-block-between-md g-3 card-inner">
    <div class="pagination g" route="{{$route}}">
        {!! $stores->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    