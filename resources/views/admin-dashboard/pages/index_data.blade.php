<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
<div class="nk-tb-item nk-tb-head">
    
    
    
    <div class="nk-tb-col"><span class="sub-text">Title</span></div>
    <div class="nk-tb-col"><span class="sub-text">Slug</span></div>
    <div class="nk-tb-col"><span class="sub-text">View</span></div>

    <div class="nk-tb-col"><span class="sub-text">Status</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($pages as $page)
<div class="nk-tb-item">
    
    
   
  
   
    
    <div class="nk-tb-col">
        <div class="tb-lead"><span><a href="{{route('admin.pages.edit', $page)}}" class="a_link">{{$page->title}}</a></span></div>
    </div>
    <div class="nk-tb-col">
        <span>{{$page->slug}}</span>
    </div>
    <div class="nk-tb-col">
        <span><a href="{{route('page',$page->slug)}}" target="_blank" class="a_link"><em class="icon ni ni-link-alt"></em></a></span>
    </div>
 
    
    <div class="nk-tb-col">
        {!! $page->status ==1  ? '<span class="tb-status badge badge-success">Active</span>' : '<span class="tb-status badge badge-warning">In-active</span>'!!}
 
    </div>
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">
           
            <li>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            <li><a href="{{route('admin.pages.edit', $page)}}"><em class="icon ni ni-edit"></em><span>Edit Page</span></a></li>
                            @if(!$page->default)
                            <li><a  class='delete'  form_id = "delete-page-{{$page->id}}"   style="cursor: pointer"> <em class="icon ni ni-trash-fill"></em><span>Delete Page</span></a>
                                                    
                                <form action="{{ route('admin.pages.destroy', $page) }}" id="delete-page-{{$page->id}}" method="POST" class="m-0">
                                    @method('DELETE')
                                    @csrf
                                    
                                </form>
                            </li>
                            @endif
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
        {!! $pages->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    