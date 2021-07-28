<div class="nk-tb-item nk-tb-head">
    
    <div class="nk-tb-col"><span class="sub-text">User</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Amount</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Payment Method</span></div>
    <div class="nk-tb-col tb-col-lg"><span class="sub-text">Time</span></div>
    <div class="nk-tb-col "><span class="sub-text">Status</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->


@foreach ($cashouts as $cashout)
<div class="nk-tb-item">
    
    <div class="nk-tb-col">
     
            <div class="user-card @if($cashout->new_cashout) icon-status icon-status-info @endif ">
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
                    <span>@if($cashout->user->first_name == 'unnamed' || $cashout->user->last_name == 'unnamed') NA @else{{$cashout->user->first_name[0]}}{{$cashout->user->last_name[0]}}@endif
                        </span>
                </div>
                <div class="user-info">
                    <span class="tb-lead">{{$cashout->user->id}} @if($cashout->user->first_name != 'unnamed' || $cashout->user->last_name != 'unnamed')- {{$cashout->user->first_name}} {{$cashout->user->last_name}} @endif
                        </span>
                    <span>{{$cashout->user->email}}</span>
                </div>
            </div>
       
    </div>
    <div class="nk-tb-col tb-col-mb">
        <span class="tb-amount"><span class="currency">{{ currency()}}</span>{{number_format((float)$cashout->amount, 2, '.', '')}}</span>
    </div>
    <div class="nk-tb-col tb-col-mb">
        <span>{{$cashout->payment_method}}</span>
    </div>
    
    <div class="nk-tb-col ">
        <span>{{$cashout->created_at}}</span>  
    </div> 
   
    <div class="nk-tb-col ">
        <span>
            {!! $cashout->status =='paid'  ? '<span class="tb-status badge badge-success">paid</span>' : '<span class="tb-status badge badge-info">pending</span>'!!}
        </span>
    </div>
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">
            <li class="nk-tb-action">
                <a href="{{route('admin.cashouts.show',$cashout)}}" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="View Cashout">
                    <em class="icon ni ni-eye-fill"></em>
                </a>
            </li>
        </ul>
    </div>
</div><!-- .nk-tb-item -->

@endforeach   


<div class="nk-block-between-md g-3 card-inner">
    <div class="pagination g" route="users">
        {!!$cashouts->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    