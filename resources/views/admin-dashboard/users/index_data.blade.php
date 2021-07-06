@if(count($users))
<div class="nk-tb-item nk-tb-head">
    
    <div class="nk-tb-col"><span class="sub-text">User</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Balance</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Reg Type</span></div>
    <div class="nk-tb-col tb-col-lg"><span class="sub-text">Reg date</span></div>
    <div class="nk-tb-col "><span class="sub-text">Status</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($users as $user)
<div class="nk-tb-item">
    
    <div class="nk-tb-col">
       <a href="{{route('admin.users.show_user')}}?user_id={{$user->id}}">
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
                    <span>@if($user->first_name == 'unnamed' || $user->last_name == 'unnamed') NA @else{{$user->first_name[0]}}{{$user->last_name[0]}}@endif</span>
                </div>
                <div class="user-info">
                    <span class="tb-lead">{{$user->id}} @if($user->first_name != 'unnamed' || $user->last_name != 'unnamed')- {{$user->first_name}} {{$user->last_name}} @endif<span class="dot dot-success d-md-none ml-1"></span></span>
                    <span>{{$user->email}}</span>
                </div>
            </div>
        </a>
       
    </div>
    <div class="nk-tb-col tb-col-mb">
        <span class="tb-amount"><span class="currency">{{ currency() }} </span>{{$user->balance->sum('amount')}}</span>
    </div>
    <div class="nk-tb-col tb-col-mb">
        <span>{{$user->registration_type}}</span>
    </div>
    
    <div class="nk-tb-col ">
        <span>{{$user->created_at}}</span>
    </div>
   
   
    <div class="nk-tb-col ">
       {!! $user->status ? '<span class="tb-status text-success">active</span>' : '<span class="tb-status text-danger">in-active</span>'!!}
 
    </div>
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">
            <li>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            {{-- <li><a href="{{route('admin.users.password',$user)}}"><em class="icon ni ni-lock-alt-fill"></em><span>Change Password</span></a></li> --}}
                            <li><a href="{{route('admin.users.show_user')}}?user_id={{$user->id}}"><em class="icon ni ni-edit"></em><span>Edit user</span></a></li>
                            {{-- <li><a href="{{route('admin.users.paymentinfo',$user)}}"><em class="icon ni ni-money"></em><span>Payment Info</span></a></li> --}}
                            <li>
                                <form action="{{ route('admin.users.destroy', $user) }}" id="delete-form-{{$user->id}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    
                                </form>
                                
                                <a  class='delete'  form_id = "delete-form-{{$user->id}}" style="cursor: pointer">
                                
                                <em class="icon ni ni-trash-fill"></em></em><span>Delete User</span></a></li>
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
        {!!$users->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    

@else 
    <h3 class="m-auto text-center py-5">No results found</h3> 
@endif 