<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
<div class="nk-tb-item nk-tb-head">
    
    <div class="nk-tb-col"><span class="sub-text">Name</span></div>
    <div class="nk-tb-col text-center"><span class="sub-text">Code</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($languages as $language)
<div class="nk-tb-item">
    
    <div class="nk-tb-col">
        
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
                    <span>{{$language->code}}</span>
                </div>
                <div class="user-info">
                    <span class="tb-lead">{{$language->name}}<span class="dot dot-success d-md-none ml-1"></span></span>
                   
                </div>
            </div>
      
    </div>
   
  
    
    <div class="nk-tb-col tb-col-md text-center">
        <span>{{$language->code?? ''}}</span>
    </div>
    
   
   
    
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">
        
            <li>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            {{-- <li><a href="{{route('admin.settings.edit', $language)}}"><em class="icon ni ni-edit"></em><span>Edit Setting</span></a></li> --}}
                            <li>
                                <form action="{{ route('admin.languages.destroy', $language->id) }}" id="delete-form-{{$language->id}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    
                                </form>
                                
                                <a onclick="$('#delete-form-{{$language->id}}').submit();" style="cursor: pointer">
                                
                                <em class="icon ni ni-trash-fill"></em></em><span>Delete Language</span></a></li>
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
        {!! $languages->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    