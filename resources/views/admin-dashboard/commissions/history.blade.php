
   

<div class="card h-100">
    
    <div class="card-inner">
        <div class="timeline">
        
            <ul class="timeline-list">
                @foreach ($history as $item)
                <li class="timeline-item">
                    <div class="timeline-status bg-primary is-outline"></div>
                    <div class="timeline-date ">{{Carbon\Carbon::parse($item->created_at)->isoFormat('Do MMM')}}<em class="icon ni ni-alarm-alt"></em></div>
                    <div class="timeline-data">
                        <h6 class="timeline-title text-capitalize"> {{$item->status->status}}</h6>
                        <div class="timeline-des">
                            <span class="time">{{Carbon\Carbon::parse($item->created_at)->isoFormat('Do MMMM YYYY, h:mm:ss a ')}}</span>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>