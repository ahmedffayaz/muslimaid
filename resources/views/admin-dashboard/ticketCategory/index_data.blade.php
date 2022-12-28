<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
<div class="nk-tb-item nk-tb-head">
    <div class="nk-tb-col"><span class="sub-text">Name</span></div>
    <div class="nk-tb-col"><span class="sub-text">Description</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($ticketCategory as $category)
<div class="nk-tb-item">
    <div class="nk-tb-col">
        <div class="tb-lead"><span><a href="{{route('admin.pages.edit', $category)}}" class="a_link">{{$category->name}}</a></span></div>
    </div>
    <div class="nk-tb-col">
        <span>{{$category->description}}</span>
    </div>
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">
            <li>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            <li><a href="{{route('admin.ticketCategory.edit', $category)}}"><em class="icon ni ni-edit"></em><span>Edit </span></a></li>
                            <li><a  class='delete'  form_id = "delete-{{$category->id}}"   style="cursor: pointer"> <em class="icon ni ni-trash-fill"></em><span>Delete</span></a>                       
                                <form action="{{ route('admin.ticketCategory.destroy', $category) }}" id="delete-{{$category->id}}" method="POST" class="m-0">
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
    <div class="pagination g" >
        {!! $ticketCategory->links()!!}                                           
    </div>    
</div><!-- .nk-block-between -->                                 
                    