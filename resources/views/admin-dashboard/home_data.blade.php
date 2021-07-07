<span class="period d-none">{{$period}}</span>
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <div class="card">
                        <div class="card-body p-0">
                            
                            <div class="media p-3">
                                <a href="{{route('admin.reports.earnings')}}" class="media-body" style="display: contents;">
                                <div class="media-body">
                                    <h6 class="title">Revenue</h6>
                                    <h3 class="mb-0 mt-2"> {{ currency() }} {{$total_revenue}}</h3>
                                    
                                </div>
                                <div class="align-self-center text-center analytics-icon" >
                                    <em class="icon ni ni-coins text-info"></em>
                                </div>
                                
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 mb-3">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="media p-3">
                                <a href="{{route('admin.reports.earnings')}}" class="media-body" style="display: contents;">
                                <div class="media-body">
                                    <h6 class="title">Pending Revenue</h6>
                                    <h3 class="mb-0 mt-2">{{ currency() }} {{$pending_total_revenue}}</h3>
                                </div>
                                <div class="align-self-center text-center analytics-icon">
                                    <em class="icon ni ni-coins text-info"></em>
                                </div>
                                </a>
                                
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class="col-lg-3 mb-3">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="media p-3">
                                <a href="{{route('admin.commissions.index')}}" class="media-body" style="display: contents;">
                                <div class="media-body">
                                    <h6 class="title">Cashbacks</h6>

                                    <h3 class="mb-0 mt-2">{{count($coms)}}</h3>
                                </div>
                                <div class="align-self-center text-center analytics-icon">
                                    <em class="icon ni ni-growth-fill text-info"></em>
                                </div>
                                </a>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 mb-3">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="media p-3">
                                <a href="{{route('admin.users.index')}}" class="media-body" style="display: contents;">
                                <div class="media-body">
                                    <h6 class="title">New Users</h6>
                                    <h3 class="mb-0 mt-2">{{count($users)}}</h3>
                                </div>
                                <div class="align-self-center text-center analytics-icon">
                                    <em class="icon ni ni-users-fill text-info"></em>
                                </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>
    <div class="row g-gs">
        <div class="col-lg-6  d-table">
            <div class="card h-100">
                <div class="card-inner border-bottom">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">New/Pending Tickets</h6>
                        </div>
                        <div class="card-tools">
                            <a href="{{route('admin.tickets.index')}}" class="link">All Tickets</a>
                        </div>
                    </div>
                </div>
                <ul class="nk-support">
                @if(count($tickets))
                
                    @foreach ($tickets->take(5) as $ticket)
                    <li class="nk-support-item">
                        <a href="{{route('admin.tickets.show', $ticket)}}">
                        <div class="user-avatar text-uppercase
                                <?php

                                    $color = rand(1,5);
                                    if($color==1){echo 'bg-info-dim';}
                                    elseif($color==2){echo 'bg-primary-dim';}
                                    elseif($color==3){echo 'bg-danger-dim';}
                                    elseif($color==4){echo 'bg-success-dim';}
                                    elseif($color==5){echo 'bg-warning-dim';}
                                    else{}
                                    ?>">
                                        <span>{{$ticket->user->first_name[0]}}{{$ticket->user->last_name[0]}}</span>
                            </div>
                        </a>
                        <div class="nk-support-content">
                            <a href="{{route('admin.tickets.show', $ticket)}}">
                            <div class="title">
                                <span>{{$ticket->user->first_name}} {{$ticket->user->last_name}}</span>
                                @if($ticket->status =='open')
                                <span class="badge badge-dot badge-dot-xs badge-info ml-1">Open</span>
                            @elseif($ticket->status=='pending')
                                @if($ticket->lastReply->reply_by =='admin')
                                <span class="badge badge-dot badge-dot-xs badge-info ml-1">Replied</span>
                                
                                @endif
                                @if($ticket->lastReply->reply_by =='client')
                                <span class="badge badge-dot badge-dot-xs badge-warning ml-1">Awaiting your reply </span>
                                @endif

                            @elseif($ticket->status=='closed')
                            <span class="badge badge-dot badge-dot-xs badge-success ml-1">Closed </span>
                                

                            @endif
                                
                            </div>
                        </a>
                            <p>{{$ticket->title}}</p>
                            <span class="time">@php echo \Carbon\Carbon::createFromTimeStamp(strtotime($ticket->created_at))->diffForHumans() @endphp</span>
                        </div>
                    </li>
                    @endforeach
                    
                
                @else
                <li class="nk-support-item">No New/Pending Tickets</li>
                @endif
            </ul>
            </div>
            
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-inner">
                    <div class="card-title-group mb-2">
                        <div class="card-title">
                            <h6 class="title">Statistics</h6>
                        </div>
                    </div>
                    <ul class="nk-store-statistics">
                        <li class="item">
                            <a href="{{route('admin.stores.index')}}">
                            <div class="info">
                                <div class="title">Total Stores</div>
                                <div class="count">{{count($stores)}}</div>
                            </div>
                            
                        </a><a href="{{route('admin.stores.index')}}">
                        <em class="icon bg-primary-dim ni ni-bag"></em></a>
                        </li>
                        <li class="item">
                            <a href="{{route('admin.users.index')}}">
                            <div class="info">
                                <div class="title">Total Users</div>
                                <div class="count">{{count($total_users)}}</div>
                            </div>
                        </a><a href="{{route('admin.users.index')}}">
                            <em class="icon bg-info-dim ni ni-users"></em></a>
                        </li>
                        <li class="item">
                            <a href="{{route('admin.commissions.index')}}">
                            <div class="info">
                                <div class="title">Total Cashbacks</div>
                                <div class="count">{{count($total_coms)}}</div>
                            </div>
                        </a><a href="{{route('admin.commissions.index')}}">
                            <em class="icon bg-pink-dim ni ni-box"></em></a>
                        </li>
                        <li class="item">
                            <a href="{{route('admin.clicks.index')}}">
                            <div class="info">
                                <div class="title">Total Clicks</div>
                                <div class="count">{{count($total_clicks)}}</div>
                            </div>
                        </a><a href="{{route('admin.clicks.index')}}">
                            <em class="icon bg-success-dim ni ni-arrow-up-right"></em></a>
                        </li>
                        
                    </ul>
                </div><!-- .card-inner -->
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-lg-6 items-align-middle">
            <div class="card card-full overflow-hidden">
                <div class="nk-ecwg nk-ecwg4 h-100">
                    <div class="card-inner flex-grow-1">
                        <div class="card-title-group mb-4">
                            <div class="card-title">
                                <h6 class="title">Clicks Conversion rate @if(count($clicks)){{round((count($coms)*100)/count($clicks),0)}}% @else 0% @endif</h6>
                            </div>
                            <div class="card-tools">
                                
                            </div>
                        </div>
                        <div class="data-group">
                            <div class="nk-ecwg4-ck">
                                <canvas class="ecommerce-doughnut-s1" id="trafficSources"></canvas>
                            </div>
                            <ul class="nk-ecwg4-legends">
                                <li>
                                    <div class="title">
                                        <span class="dot dot-lg sq" data-bg="#9769ff"></span>
                                        <span>Converted</span>
                                    </div>
                                    <div class="amount amount-xs">{{$converted}}</div>
                                </li>
                                <li>
                                    <div class="title">
                                        <span class="dot dot-lg sq" data-bg="#ff63a5"></span>
                                        <span>Not converted</span>
                                    </div>
                                    <div class="amount amount-xs">{{$notconverted}}</div>
                                </li>
                                
                            </ul>
                        </div>
                    </div><!-- .card-inner -->
                    {{-- <div class="card-inner card-inner-md bg-light">
                        <div class="card-note">
                            <em class="icon ni ni-info-fill"></em>
                            <span>Traffic channels have beed generating the most traffics over past days.</span>
                        </div>
                    </div> --}}
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        
        
        
        
        @if(count($coms))
        <div class="col-lg-6">
            <div class="card card-full">
                <div class="card-inner-group">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">Latest Cashback</h6>
                            </div>
                            <div class="card-tools">
                              <a href="{{route('admin.commissions.index')}}" class="link ml-2">View All</a>
                            </div>
                        </div>
                    </div>
                    @foreach ($coms->take(10) as $com)
                    <div class="card-inner card-inner-md">
                        <div class="user-card">
                            <div class="user-avatar 
                                <?php

                                    $color = rand(1,5);
                                    if($color==1){echo 'bg-info-dim';}
                                    elseif($color==2){echo 'bg-primary-dim';}
                                    elseif($color==3){echo 'bg-danger-dim';}
                                    elseif($color==4){echo 'bg-success-dim';}
                                    elseif($color==5){echo 'bg-warning-dim';}
                                    else{}
                                    ?>">
                                        <span>{{$com->user->first_name[0]}}{{$com->user->last_name[0]}}</span>
                            </div>
                            <div class="user-info">
                                <a href="{{route('admin.users.show',$com->user)}}"><span class="lead-text">{{$com->user->first_name}} {{$com->user->last_name}}</span></a>
                                <span class="sub-text">{{$com->store->name}} <span class="badge badge-dim badge-pill badge-outline-primary ml-1">{{$com->store->network->name}}</span></span>
                            </div>
                            <div class="user-action nk-top-products">
                                <div class="total">
                                    <div class="amount"> {{ currency() }} {{$com->amount}}</div>
                                    <div class="count">
                                        @php echo \Carbon\Carbon::createFromTimeStamp(strtotime($com->event_date))->diffForHumans() @endphp
                                        </div>
                                    
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    @endforeach
                    @if($coms->count() > 10)
                    <span class="p-3 text-center d-block">
                        +{{$coms->count()-10}} More  <a href="{{route('admin.commissions.index')}}" class="link ml-1">View All</a>
                    
                    </span>
                   @endif
                    
                </div>
            </div>
            
        </div><!-- .col -->
        
        @endif
        @if(count($users))
        <div class="col-lg-6">
            <div class="card card-full">
                <div class="card-inner-group">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">New Users</h6>
                            </div>
                            <div class="card-tools">
                                <a href="{{route('admin.users.index')}}" class="link">View All</a>
                            </div>
                        </div>
                    </div>
                    @foreach ($users->take(10) as $user)
                    <div class="card-inner card-inner-md">
                        <div class="user-card">
                            <div class="user-avatar 
                                <?php

                                    $color = rand(1,5);
                                    if($color==1){echo 'bg-info-dim';}
                                    elseif($color==2){echo 'bg-primary-dim';}
                                    elseif($color==3){echo 'bg-danger-dim';}
                                    elseif($color==4){echo 'bg-success-dim';}
                                    elseif($color==5){echo 'bg-warning-dim';}
                                    else{}
                                    ?>">
                                        <span>{{$user->first_name[0]}}{{$user->last_name[0]}}</span>
                            </div>
                            <div class="user-info">
                                <a href="{{route('admin.users.show',$user)}}"><span class="lead-text">{{$user->first_name}} {{$user->last_name}}</span></a>
                                <span class="sub-text">{{$user->email}}</span>
                            </div>
                            <div class="user-action">
                                <div class="drodown">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger mr-n1" data-toggle="dropdown" aria-expanded="false"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="{{route('admin.users.show',$user)}}"><em class="icon ni ni-eye"></em><span>View user</span></a></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if($users->count() > 10)
                    <span class="p-3 text-center d-block">
                        +{{$users->count()-10}} More  <a href="{{route('admin.commissions.index')}}" class="link ml-1">View All</a>
                    
                    </span>
                   @endif
                    
                </div>
            </div>
            
        </div><!-- .col -->
        @endif
        @if(count($reviews))
        <div class="col-lg-6">
            <div class="card card-full">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">Pending Reviews</span></h6>
                        </div>
                        <div class="card-tools">
                            <a  href="{{route('admin.reviews.index')}}">View All</a>
                            
                        </div>
                    </div>
                </div>
                <div class="nk-tb-list mt-n2">
                    <div class="nk-tb-item nk-tb-head">



                        <div class="nk-tb-col"><span class="sub-text">Reviwer</span></div>
                        <div class="nk-tb-col"><span class="sub-text">Review</span></div>
                        <div class="nk-tb-col"><span class="sub-text">Store</span></div>
                        <div class="nk-tb-col"><span class="sub-text">Status</span></div>
                        {{-- <div class="nk-tb-col "><span class="sub-text">Status</span></div> --}}
                        <div class="nk-tb-col nk-tb-col-tools text-right">
                            <span class="sub-text">Action</span>
                            
                        </div>
                    </div><!-- .nk-tb-item -->
                    @foreach ($reviews->take(10) as $review)
                    <div class="nk-tb-item">
                        
                        <div class="nk-tb-col">
                            <span>{{$review->reviewer}}</span>
                        </div>
                        <div class="nk-tb-col">
                            <span>{{ substr(strip_tags($review->review),0,50) }}..</span>
                        </div>
                        
                        
                      
                        <div class="nk-tb-col">
                            <a href="{{route('admin.stores.show_store')}}?slug={{$review->store->slug}}">
                            <span><b>{{$review->store->name ?? ''}}</b></span><br>
                            <span>{{$review->store->network->name ?? ''}}</span></a>
                        </div>
                        <div class="nk-tb-col text-center">
                            <span> {!! $review->status =='active'  ? '<span class="tb-status badge badge-success">active</span>' : '<span class="tb-status badge badge-danger">pending</span>'!!}
                        </span>
                        </div>
                        <div class="nk-tb-col nk-tb-col-tools">
                            <ul class="nk-tb-actions gx-1">
                                
                                <li>
                                    <div class="drodown">
                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <ul class="link-list-opt no-bdr">
                                                <li><a href="{{route('admin.reviews.edit', $review)}}"><em class="icon ni ni-edit"></em><span>Edit Review</span></a></li>
                                                <li><a  onclick="$('#delete-review-{{$review->id}}').submit();"  style="cursor: pointer"> <em class="icon ni ni-trash-fill"></em><span>Delete Review</span></a>
                                                                        
                                                    <form action="{{ route('admin.reviews.destroy', $review) }}" id="delete-review-{{$review->id}}" method="POST" class="m-0">
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
                </div>
            </div><!-- .card -->
        </div>
        @endif
        
        
    </div><!-- .row -->

           


@push('scripts')
 <script>"use strict";

    !function (NioApp, $) {
      "use strict";
            var converted = '{{$converted}}';
        var notconverted ='{{$notconverted}}';

      var trafficSources = {
        labels: ["Converted", "Not Converted",],
        dataUnit: 'Clicks',
        legend: false,
        datasets: [{
          borderColor: "#fff",
          background: ["#9769ff", "#ff63a5"],
          data: [converted, notconverted]
        }]
      };
     
    
      function ecommerceDoughnutS1(selector, set_data) {
        var $selector = selector ? $(selector) : $('.ecommerce-doughnut-s1');
        $selector.each(function () {
          var $self = $(this),
              _self_id = $self.attr('id'),
              _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;
    
          var selectCanvas = document.getElementById(_self_id).getContext("2d");
          var chart_data = [];
    
          for (var i = 0; i < _get_data.datasets.length; i++) {
            chart_data.push({
              backgroundColor: _get_data.datasets[i].background,
              borderWidth: 2,
              borderColor: _get_data.datasets[i].borderColor,
              hoverBorderColor: _get_data.datasets[i].borderColor,
              data: _get_data.datasets[i].data
            });
          }
    
          var chart = new Chart(selectCanvas, {
            type: 'doughnut',
            data: {
              labels: _get_data.labels,
              datasets: chart_data
            },
            options: {
              legend: {
                display: _get_data.legend ? _get_data.legend : false,
                rtl: NioApp.State.isRTL,
                labels: {
                  boxWidth: 12,
                  padding: 20,
                  fontColor: '#6783b8'
                }
              },
              rotation: -1.5,
              cutoutPercentage: 70,
              maintainAspectRatio: false,
              tooltips: {
                enabled: true,
                rtl: NioApp.State.isRTL,
                callbacks: {
                  title: function title(tooltipItem, data) {
                    return data['labels'][tooltipItem[0]['index']];
                  },
                  label: function label(tooltipItem, data) {
                    return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + _get_data.dataUnit;
                  }
                },
                backgroundColor: '#1c2b46',
                titleFontSize: 13,
                titleFontColor: '#fff',
                titleMarginBottom: 6,
                bodyFontColor: '#fff',
                bodyFontSize: 12,
                bodySpacing: 4,
                yPadding: 10,
                xPadding: 10,
                footerMarginTop: 0,
                displayColors: false
              }
            }
          });
        });
      } // init chart
    
    
      NioApp.coms.docReady.push(function () {
        ecommerceDoughnutS1();
      });
    }(NioApp, jQuery);</script> 
    {{-- {!! $chart1->renderChartJsLibrary() !!}
    {!! $chart1->renderJs() !!}   --}}
@endpush