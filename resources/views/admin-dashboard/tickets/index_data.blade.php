<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
<div class="nk-tb-item nk-tb-head">
    
    
    <div class="nk-tb-col "><span class="sub-text">Ticket ID</span></div>
    <div class="nk-tb-col "><span class="sub-text">Ticket title</span></div>
    <div class="nk-tb-col "><span class="sub-text">Category</span></div>
    <div class="nk-tb-col "><span class="sub-text">User</span></div>
    <div class="nk-tb-col text-center"><span class="sub-text">Status</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>
       
    </div>
</div><!-- .nk-tb-item -->
@foreach ($tickets as $ticket)
<div class="nk-tb-item">
    
    
   
    <div class="nk-tb-col">
        <span><a href="{{route('admin.tickets.show',$ticket)}}"><div class="tb-lead @if(count($ticket->newReply) || $ticket->new_ticket) icon-status icon-status-info @endif">{{$ticket->id}}</div></a></span><br>
        
    </div>
   
    <div class="nk-tb-col  ">
        <span class=""><a href="{{route('admin.tickets.show',$ticket)}}"><div class="tb-lead">{{$ticket->title}}</div></a></span>
    </div>
    
    
    <div class="nk-tb-col">
        <span>{{$ticket->category->name ?? ''}}</span>
    </div>
    <div class="nk-tb-col">
        <span><div class="tb-lead">{{$ticket->user->first_name}} {{$ticket->user->last_name}}</div></span>
    </div>
    

   
   
   
   
    <div class="nk-tb-col text-center">
        @if($ticket->status =='open')
            <span class="badge badge-primary">Open</span>
        @elseif($ticket->status=='pending')
            @if($ticket->lastReply->reply_by =='admin') <span class="badge badge-info">Replied </span>@endif
            @if($ticket->lastReply->reply_by =='client') <span class="badge badge-warning">Awaiting your reply </span>@endif

        @elseif($ticket->status=='closed')
            <span class="badge badge-success">{{$ticket->lastReply}}</span>

        @endif

    </div>
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">
            <li>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            <li><a href="{{route('admin.tickets.show', $ticket)}}"><em class="icon ni ni-eye"></em><span>View Ticket</span></a></li>
                            {{-- <li><a href="{{route('admin.tickets.show', $ticket)}}"><em class="icon ni ni-check"></em><span>Mark as Closed</span></a></li> --}}
                            {{-- <li><a href="{{route('admin.stores.images', $store)}}"><em class="icon ni ni-eye"></em><span>View Store Images</span></a></li> --}}
                    
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
        {!! $tickets->links()!!}                             
                         
        </div> 
    
    
</div><!-- .nk-block-between -->                                 
                    