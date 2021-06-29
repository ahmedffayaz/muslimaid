<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
<div class="nk-tb-item nk-tb-head">
    
    
    
    <div class="nk-tb-col"><span class="sub-text">Title</span></div>
    <div class="nk-tb-col"><span class="sub-text">Slug</span></div>
    <div class="nk-tb-col"><span class="sub-text">View</span></div>

    {{-- <div class="nk-tb-col"><span class="sub-text">Status</span></div> --}}
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($blogs as $blog)
<div class="nk-tb-item">
    
    <div class="nk-tb-col">
        <div class="tb-lead"><span><a href="{{route('admin.blogs.edit', $blog)}}">{{$blog->title}}</a></span></div>
    </div>
    <div class="nk-tb-col">
        <span>{{$blog->slug}}</span>
    </div>
    <div class="nk-tb-col">
        <span><a href="{{route('post',$blog->slug)}}" target="_blank"><em class="icon ni ni-link-alt"></em></a></span>
    </div>
 
    
    {{-- <div class="nk-tb-col text-center">
        <span>        {!! $review->status =='active'  ? '<span class="tb-status badge badge-success">active</span>' : '<span class="tb-status badge badge-danger">pending</span>'!!}
    </span>
    </div> --}}
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">
           
            <li>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            <li><a href="{{route('admin.blogs.edit', $blog)}}"><em class="icon ni ni-edit"></em><span>Edit Blog</span></a></li>
                           <li><a  form_id="delete-blog-{{$blog->id}}"  class="delete" style="cursor: pointer"> <em class="icon ni ni-trash-fill"></em><span>Delete Blog</span></a>
                                                    
                                <form action="{{ route('admin.blogs.destroy', $blog) }}" id="delete-blog-{{$blog->id}}" method="POST" class="m-0">
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
        {!! $blogs->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    