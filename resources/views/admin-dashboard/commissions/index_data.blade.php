<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
<div class="nk-tb-item nk-tb-head">
    
    
    <div class="nk-tb-col " style="width: 25%"><span class="sub-text" >User</span></div>
    <div class="nk-tb-col" style="width: 20%"><span class="sub-text">Store</span></div>
    {{-- <div class="nk-tb-col text-center"><span class="sub-text">Order Value ({{ currency() }})</span></div> --}}
    {{-- <div class="nk-tb-col text-center"><span class="sub-text">Network Commission ({{ currency() }})</span></div> --}}
    <div class="nk-tb-col tb-col-mb text-center"><span class="sub-text">Network Commission ({{ currency() }})</span></div>
    <div class="nk-tb-col tb-col-mb text-center"><span class="sub-text">Cashback ({{ currency() }})</span></div>
    <div class="nk-tb-col  text-center"><span class="sub-text">Exit Click Id</span></div>
    <div class="nk-tb-col  text-center"><span class="sub-text">Event Time</span></div>
    <div class="nk-tb-col  text-right"><span class="sub-text">Status</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($coms as $commission)
<div class="nk-tb-item">
    
    
   
  
    <div class="nk-tb-col "style="width: 25%">
       
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
            ?>
            
            ">
                <span>{{$commission->exitClick->user->first_name[0] ?? 'N'}}{{$commission->exitClick->user->last_name[0] ?? 'A'}}</span>
            </div>
            <div class="user-info">
                <span class="tb-lead">{{$commission->exitClick->user->id ?? ''}} @if($commission->user->first_name != 'unnamed' || $commission->user->last_name != 'unnamed')- {{$commission->user->first_name}} {{$commission->user->last_name}} @endif{{$commission->exitClick->user->first_name ?? ''}}</span>
                <span>{{$commission->exitClick->user->email ?? ''}}</span>
            </div>
        </div>
        
    </div>
    <div class="nk-tb-col" style="width: 20%">
        <span><b>{{$commission->store->id ?? ''}} - {{$commission->store->name ?? ''}}</b></span>
        
    </div>
    
    <div class="nk-tb-col  text-center">
        <span><span class="currency">{{ currency() }} </span>{{$commission->network_commission}}</span>
    </div>
    <div class="nk-tb-col  text-center">
        <span><span class="currency">{{ currency() }} </span>{{$commission->amount}}</span>
    </div>
    <div class="nk-tb-col  text-center">
        <span>{{$commission->exit_click_id}}</span>
    </div>
    <div class="nk-tb-col  text-right">
        <span>{{$commission->event_date}}</span>
    </div>
    <div class="nk-tb-col  text-right">
        <span class="tb-status text-info"> {{ $commission->statusMap->status ?? $commission->status}}</span>
    </div>
    
   
  
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">
           
            <li>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            <li><a href="{{route('admin.commissions.edit', $commission)}}"  cashback-id='{{$commission->id}}' class='cashback-edit'><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                            <li><a href="{{route('admin.commissions.history', $commission)}}"  class='cashback-history'><em class="icon ni ni-eye"></em><span>Status History</span></a></li>
                            <li><a  onclick="$('#delete-commission-{{$commission->id}}').submit();"  style="cursor: pointer"> <em class="icon ni ni-trash-fill"></em><span>Delete</span></a>
                                                    
                                <form action="{{ route('admin.commissions.destroy', $commission) }}" id="delete-commission-{{$commission->id}}" method="POST" class="m-0">
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
        {!! $coms->onEachSide(1)->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    