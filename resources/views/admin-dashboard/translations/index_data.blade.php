@if(count($translations))
<div class="nk-tb-item nk-tb-head">
    
    <div class="nk-tb-col"><span class="sub-text">Group</span></div>
    <div class="nk-tb-col text-center"><span class="sub-text">Key</span></div>
    <div class="nk-tb-col text-center"><span class="sub-text">English</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($translations as $translation)
<div class="nk-tb-item">
    
    <div class="nk-tb-col">
        <a href="{{route(getAdminPrefix() . '.translations.show',$translation->id)}}">
            <div class="user-card">
                
                <div class="user-info">
                    <span class="tb-lead">{{$translation->group}}</span>
                   
                </div>
            </div>
        </a>
    </div>
   
  
    
    <div class="nk-tb-col  text-center">
        <span>{{$translation->key?? ''}}</span>
    </div>

    <div class="nk-tb-col  text-center">
        <span>{{$translation->text['en'] ?? ''}}</span>
    </div>
    
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">
        
            <li>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            <li><a href="{{route(getAdminPrefix() . '.translations.edit', $translation->id)}}"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                            <li>
                                <form action="{{ route(getAdminPrefix() . '.translations.destroy', $translation->id) }}" id="delete-form-{{$translation->id}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    
                                </form>
                                
                                <a onclick="$('#delete-form-{{$translation->id}}').submit();" style="cursor: pointer">
                                
                                <em class="icon ni ni-trash-fill"></em></em><span>Delete</span></a></li>
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
        {!! $translations->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    
@else 
    <h3 class="m-auto text-center py-5">No results found</h3> 
@endif  
                    