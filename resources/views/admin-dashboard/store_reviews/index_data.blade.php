<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
<div class="nk-tb-item nk-tb-head">
    
    
    <div class="nk-tb-col "><span class="sub-text">Store Name</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Reviwer</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Review</span></div>
    <div class="nk-tb-col tb-col-mb text-center"><span class="sub-text">Status</span></div>
    {{-- <div class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></div> --}}
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($reviews as $review)
<div class="nk-tb-item">
    
    
   
  
    <div class="nk-tb-col tb-col-md"><a href="{{route('admin.stores.show',$review->store)}}">
        <span><b>{{$review->store->name ?? ''}}</b></span><br>
        <span>{{$review->store->network->name ?? ''}}</span></a>
    </div>
    
    <div class="nk-tb-col tb-col-md">
        <span>{{$review->reviewer}}</span>
    </div>
 
    <div class="nk-tb-col tb-col-md">
        <span>{!!$review->review!!}</span>
    </div>
    <div class="nk-tb-col tb-col-md text-center">
        <span>        {!! $review->status =='active'  ? '<span class="tb-status text-success">active</span>' : '<span class="tb-status text-danger">inactive</span>'!!}
    </span>
    </div>
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">
           
            <li>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            <li><a href="{{route('admin.reviews.edit', $review)}}"><em class="icon ni ni-edit"></em><span>Edit Review</span></a></li>
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
        {!! $reviews->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    