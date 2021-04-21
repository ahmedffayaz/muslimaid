<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
<div class="nk-tb-item nk-tb-head">
    
    
    <div class="nk-tb-col "><span class="sub-text">Category</span></div>
    <div class="nk-tb-col "><span class="sub-text">Parent Category</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">No of Stores</span></div>
    <div class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($categories as $category)
<div class="nk-tb-item">
    
    
   
  
    <div class="nk-tb-col tb-col-md">
        <span><b>{{$category->name}}</b></span>
        
    </div>
    <div class="nk-tb-col tb-col-md">
        <span><b>{{$category->parent->name ??  ''}}</b></span>
        
    </div>
    
    <div class="nk-tb-col tb-col-md">
        <span>{{count($category->stores)}}</span>
    </div>
    <div class="nk-tb-col tb-col-md">
        {!! $category->status ? '<span class="tb-status text-success">active</span>' : '<span class="tb-status text-danger">inactive</span>'!!}

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
                            <li><a href="{{route('admin.categories.edit', $category)}}"><em class="icon ni ni-edit"></em><span>Edit Category</span></a></li>
                          
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
        {!! $categories->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    