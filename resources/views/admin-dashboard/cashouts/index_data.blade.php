<div class="nk-tb-item nk-tb-head">
    
    <div class="nk-tb-col"><span class="sub-text">User</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Amount</span></div>
    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Cashout Type</span></div>
    <div class="nk-tb-col tb-col-lg"><span class="sub-text">Time</span></div>
    <div class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->


@foreach ($cashouts as $cashout)
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
                    <span>{{$cashout->user->first_name[0]}}{{$cashout->user->last_name[0]}}</span>
                </div>
                <div class="user-info">
                    <span class="tb-lead">{{$cashout->user->first_name}} {{$cashout->user->last_name}} <span class="dot dot-success d-md-none ml-1"></span></span>
                    <span>{{$cashout->user->email}}</span>
                </div>
            </div>
        </a>
    </div>
    <div class="nk-tb-col tb-col-mb">
        <span class="tb-amount">{{$cashout->cashout_type}} <span class="currency">&#163;</span></span>
    </div>
    <div class="nk-tb-col tb-col-mb">
        <span>{{$cashout->cashout_type}}</span>
    </div>
    
    <div class="nk-tb-col tb-col-md">
        <span>{{$cashout->created_at}}</span>   
   
    <div class="nk-tb-col tb-col-md">
        <span class="tb-status text-success">{{ $cashout->status ? 'active' : 'inactive'}}</span>
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
                            <li><a href="#"><em class="icon ni ni-focus"></em><span>Quick View</span></a></li>
                            <li><a href="#"><em class="icon ni ni-eye"></em><span>View Details</span></a></li>
                            <li><a href="#"><em class="icon ni ni-repeat"></em><span>Transaction</span></a></li>
                            <li><a href="#"><em class="icon ni ni-activity-round"></em><span>Activities</span></a></li>
                            <li class="divider"></li>
                            <li><a href="#"><em class="icon ni ni-shield-star"></em><span>Reset Pass</span></a></li>
                            <li><a href="#"><em class="icon ni ni-shield-off"></em><span>Reset 2FA</span></a></li>
                            <li><a href="#"><em class="icon ni ni-na"></em><span>Suspend User</span></a></li>
                        </ul>
                    </div>
                </div>
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
                    