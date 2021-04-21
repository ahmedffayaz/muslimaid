<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
<div class="nk-tb-item nk-tb-head">
    
    
    <div class="nk-tb-col "><span class="sub-text">User</span></div>
    <div class="nk-tb-col text-center"><span class="sub-text">Store</span></div>
    <div class="nk-tb-col tb-col-mb text-right"><span class="sub-text">Exit Url</span></div>
    <div class="nk-tb-col tb-col-md text-right"><span class="sub-text">Time</span></div>
    <div class="nk-tb-col tb-col-md text-right"><span class="sub-text">Status</span></div>
    {{-- <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div> --}}
</div><!-- .nk-tb-item -->
@foreach ($clicks as $click)
<div class="nk-tb-item">
    
    
   
  
    <div class="nk-tb-col tb-col-md">
        
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
                    <span>{{$click->user->first_name[0]}}{{$click->user->last_name[0]}}</span>
                </div>
                <div class="user-info">
                    <span class="tb-lead">{{$click->user->first_name}} {{$click->user->last_name}}<span class="dot dot-success d-md-none ml-1"></span></span>
                    <span>{{$click->user->email}}</span>
                </div>
            </div>
        
       
        
    </div>
    <div class="nk-tb-col tb-col-md text-center">
        <span><b>{{$click->store->name}}</b></span>
        
    </div>
    
    <div class="nk-tb-col tb-col-md text-right">
        <span>{{$click->exit_url}}</span>
    </div>
    <div class="nk-tb-col tb-col-md text-right">
        <span>{{$click->created_at}}</span>
    </div>
    <div class="nk-tb-col tb-col-md text-right">
        {!! $click->status ? '<span class="tb-status text-success">active</span>' : '<span class="tb-status text-danger">inactive</span>'!!}

    </div>
    
</div><!-- .nk-tb-item -->

@endforeach   
<div class="nk-block-between-md g-3 card-inner">
    <div class="pagination g" route="{{$route}}">
        {!! $clicks->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    