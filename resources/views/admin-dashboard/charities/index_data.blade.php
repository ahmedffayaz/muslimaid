<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
@if(count($charities))
<div class="nk-tb-item nk-tb-head">
    
    
    <div class="nk-tb-col "><span class="sub-text">Title</span></div>
    <div class="nk-tb-col "><span class="sub-text">Description</span></div>
    <div class="nk-tb-col text-center"><span class="sub-text">Status</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($charities as $charity)
<div class="nk-tb-item">
    <div class="nk-tb-col">
        <div class="tb-lead"><span><a href="{{route('admin.charities.edit',$charity)}}" class="a_link">{{$charity->title}}</a></span></div>
    </div>
    <div class="nk-tb-col">
        <span>{{strip_tags($charity->description)}}</span>
    </div>
    <div class="nk-tb-col text-center">
        {!!$charity->status ==1  ? '<span class="tb-status badge badge-success">Active</span>' : '<span class="tb-status badge badge-warning">In-active</span>'!!}
 
    </div>
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">  
            <li>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            <li><a href="{{route('admin.charities.edit',$charity)}}"><em class="icon ni ni-edit"></em><span>Edit Charity</span></a></li>
                            <li><a  class='delete'  form_id = "delete-{{$charity->id}}"   style="cursor: pointer"> <em class="icon ni ni-trash-fill"></em><span>Delete Charity</span></a>
                                                    
                                <form action="{{ route('admin.charities.destroy',$charity) }}" id="delete-{{$charity->id}}" method="POST" class="m-0">
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
        {!! $charities->links()!!}                             
                         
    </div>    
</div><!-- .nk-block-between -->     
@else 
    <h3 class="m-auto text-center py-5">No Charities found</h3> 
@endif                             
                    