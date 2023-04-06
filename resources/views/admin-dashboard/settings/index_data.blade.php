<div class="nk-tb-item nk-tb-head">
    
    <div class="nk-tb-col"><span class="sub-text">Title</span></div>
    <div class="nk-tb-col text-center"><span class="sub-text">Key Identifier</span></div>
    <div class="nk-tb-col text-center"><span class="sub-text">Value</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($settings as $setting)
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
                    <span>{{$setting->title[0]}}</span>
                </div>
                <div class="user-info">
                    <span class="tb-lead">{{$setting->title}}</span>
                   
                </div>
            </div>
      
    </div>
    <div class="nk-tb-col  text-center">
        <span>{{$setting->type?? ''}}</span>
    </div>
    <div class="nk-tb-col  text-center">
        <span>{{$setting->value ?? 'unmapped'}}</span>
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
                            <li><a href="{{route('admin.settings.edit', $setting)}}"><em class="icon ni ni-edit"></em><span>Edit Setting</span></a></li>
                            @if(!$setting->default)
                            <li>
                                <form action="{{ route('admin.settings.destroy', $setting) }}" id="delete-form-{{$setting->id}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    
                                </form>
                                
                                <a onclick="$('#delete-form-{{$setting->id}}').submit();" style="cursor: pointer">
                                
                                <em class="icon ni ni-trash-fill"></em></em><span>Delete Setting</span></a></li>
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
        {!! $settings->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    