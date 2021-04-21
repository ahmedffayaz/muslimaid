<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
<div class="nk-tb-item nk-tb-head">
    
    <div class="nk-tb-col"><span class="sub-text">Category</span></div>
    <div class="nk-tb-col text-center"><span class="sub-text">Parent Category</span></div>
    <div class="nk-tb-col tb-col-mb text-center"><span class="sub-text">Mapped to</span></div>
    <div class="nk-tb-col tb-col-md text-center"><span class="sub-text">Status</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($categories as $imported_category)
<div class="nk-tb-item">
    
    <div class="nk-tb-col">
        <a href="html/user-details-regular.html">
            <div class="user-card">
                <div class="user-avatar 
                <?php
       
                $color = rand(1,5);
                if($color==1){echo 'bg-info';}
                elseif($color==2){echo 'bg-primary';}
                elseif($color==3){echo 'bg-danger';}
                elseif($color==4){echo 'bg-success';}
                elseif($color==5){echo 'bg-warning';}
                else{}
                ?>">
                    <span>{{$imported_category->name[0]}}</span>
                </div>
                <div class="user-info">
                    <span class="tb-lead">{{$imported_category->name}}<span class="dot dot-success d-md-none ml-1"></span></span>
                   
                </div>
            </div>
        </a>
    </div>
   
  
    
    <div class="nk-tb-col tb-col-md text-center">
        <span>{{$imported_category->parent->name ?? ''}}</span>
    </div>
    <div class="nk-tb-col tb-col-md text-center">
        <span>{{$imported_category->mappedTo->name ?? 'unmapped'}}</span>
    </div>
   
   
    <div class="nk-tb-col tb-col-md text-center">
        <span class="tb-status text-success">{{ $imported_category->status ? 'active' : 'inactive'}}</span>
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
                            <li><a href="{{route('admin.importedcategories.edit', $imported_category)}}"><em class="icon ni ni-edit"></em><span>Edit Category</span></a></li>
                    
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
                    