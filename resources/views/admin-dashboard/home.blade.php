@extends('layouts.admin-dashboard.app')
<style>
    .analytics-icon{
        font-size:70px;
    }
        
    </style>
@section('content')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Dashboard</h3>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li>
                                            <div class="drodown">
                                                <a href="#" class="dropdown-toggle btn btn-white btn-dim btn-outline-light" data-toggle="dropdown">
                                                    <em class="d-none d-sm-inline icon ni ni-calender-date"></em>
                                                    
                                                    <span><span class="d-none d-md-inline">Last</span>@if($period == 1) 24 Hours @elseif($period == 7) 7 Days @elseif($period == 30) 30 Days @endif</span>
                                                        
                                                        <em class="dd-indc icon ni ni-chevron-right"></em></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <ul class="link-list-opt no-bdr">
                                                        <li><a href="{{route('admin.home.index',1)}}"><span>Last 24 Hours</span></a></li>
                                                        <li><a href="{{route('admin.home.index',7)}}"><span>Last 7 Days</span></a></li>
                                                        <li><a href="{{route('admin.home.index',30)}}"><span>Last 30 Days</span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                        {{-- <li class="nk-block-tools-opt"><a href="#" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span></a></li> --}}
                                    </ul>
                                </div>
                            </div>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                
                <div class="nk-block">
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-3 mb-3">
                                    <div class="card">
                                        <div class="card-body p-0">
                                           
                                            <div class="media p-3">
                                                <div class="media-body">
                                                    <h6 class="title">Revenue</h6>
                                                    <h3 class="mb-0 mt-2"> {{ Config::get('currency') }} {{$total_revenue}}</h3>
                                                   
                                                </div>
                                                <div class="align-self-center text-center analytics-icon" style="    display: contents;">
                                                    <em class="icon ni ni-coins text-info"></em>
                                                </div>
                                                
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
            
                                <div class="col-lg-3 mb-3">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <div class="media p-3">
                                                <div class="media-body">
                                                    <h6 class="title">Pending Revenue</h6>
                                                    <h3 class="mb-0 mt-2">{{ Config::get('currency') }} {{$pending_total_revenue}}</h3>
                                                </div>
                                                <div class="align-self-center text-center analytics-icon" style="    display: contents;">
                                                    <em class="icon ni ni-coins text-info"></em>
                                                </div>
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
            
                                <div class="col-lg-3 mb-3">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <div class="media p-3">
                                                <div class="media-body">
                                                    <h6 class="title">Cashbacks</h6>
                                                    <h3 class="mb-0 mt-2">{{count($coms)}}</h3>
                                                </div>
                                                <div class="align-self-center text-center analytics-icon" style="    display: contents;">
                                                    <em class="icon ni ni-growth-fill text-info"></em>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                                
            
                                <div class="col-lg-3 mb-3">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <div class="media p-3">
                                                <div class="media-body">
                                                    <h6 class="title">New Users</h6>
                                                    <h3 class="mb-0 mt-2">{{count($users)}}</h3>
                                                </div>
                                                <div class="align-self-center text-center analytics-icon" style="    display: contents;">
                                                    <em class="icon ni ni-users-fill text-info"></em>
                                                </div>
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        
                    </div>
                    <div class="row g-gs">
                        {{-- <div class="col-xxl-12 col-md-6">
                            <div class="card">
                                
                                <div class="nk-ecwg nk-ecwg3">
                                    <div class="card-inner pb-0">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">Clicks</h6>
                                            </div>
                                        </div>
                                        <div class="data">
                                            <div class="data-group">
                                                <div class="amount">329</div>
                                               
                                            </div>
                                        </div>
                                    </div><!-- .card-inner -->
                                    <div class="nk-ecwg3-ck">
                                        <canvas class="ecommerce-line-chart-s1" id="totalOrders"></canvas>
                                    </div>
                                </div><!-- .nk-ecwg -->
                            </div><!-- .card -->
                        </div><!-- .col -->
                        <div class="col-xxl-12 col-md-6">
                            <div class="card">
                                <div class="nk-ecwg nk-ecwg3">
                                    <div class="card-inner pb-0">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">Cashbacks</h6>
                                            </div>
                                        </div>
                                        <div class="data">
                                            <div class="data-group">
                                                <div class="amount">194</div>
                                                
                                            </div>
                                        </div>
                                    </div><!-- .card-inner -->
                                    <div class="nk-ecwg3-ck">
                                        <canvas class="ecommerce-line-chart-s1" id="totalCustomers"></canvas>
                                    </div>
                                </div><!-- .nk-ecwg -->
                            </div><!-- .card -->
                        </div><!-- .col --> --}}
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
                                          
                                        </a>
                                        <em class="icon bg-primary-dim ni ni-bag"></em>
                                        </li>
                                        <li class="item">
                                            <a href="{{route('admin.users.index')}}">
                                            <div class="info">
                                                <div class="title">Total Users</div>
                                                <div class="count">{{count($total_users)}}</div>
                                            </div>
                                        </a>
                                            <em class="icon bg-info-dim ni ni-users"></em>
                                        </li>
                                        <li class="item">
                                            <a href="{{route('admin.commissions.index')}}">
                                            <div class="info">
                                                <div class="title">Total Cashbacks</div>
                                                <div class="count">{{count($total_coms)}}</div>
                                            </div>
                                        </a>
                                            <em class="icon bg-pink-dim ni ni-box"></em>
                                        </li>
                                        <li class="item">
                                            <a href="{{route('admin.clicks.index')}}">
                                            <div class="info">
                                                <div class="title">Total Clicks</div>
                                                <div class="count">{{count($total_clicks)}}</div>
                                            </div>
                                        </a>
                                            <em class="icon bg-success-dim ni ni-arrow-up-right"></em>
                                        </li>
                                       
                                    </ul>
                                </div><!-- .card-inner -->
                            </div><!-- .card -->
                        </div><!-- .col -->
                        
                      
                       @if(count($coms))
                        <div class="col-lg-6">
                            <div class="card h-100">
                                <div class="card-inner">
                                    <div class="card-title-group mb-2">
                                        <div class="card-title">
                                            <h6 class="title">Latest Cashbacks</h6>
                                        </div>
                                        <div class="card-tools">
                                            <a  href="{{route('admin.commissions.index')}}">View All</a>
                                            
                                        </div>
                                    </div>
                                    <ul class="nk-top-products">
                                        @foreach ($coms->take(10) as $com)
                                            
                                        <li class="item">
                                            <div class="info">
                                                <div class="title"><em class="text-primary icon ni ni-cart-fill mr-2"></em> {{$com->store->name}} <span class="badge badge-dim badge-pill badge-outline-primary">{{$com->store->network->name}}</span></div>
                                                <div class="price"></div>
                                             
                                            </div>
                                            <div class="total">
                                                <div class="amount"> {{ Config::get('currency') }} {{$com->amount}}</div>
                                                <div class="count">
                                                    @php echo \Carbon\Carbon::createFromTimeStamp(strtotime($com->event_date))->diffForHumans() @endphp
                                                   </div>
                                                
                                            </div>
                                        </li>
                                        @endforeach
                                       
                                    </ul>
                                </div><!-- .card-inner -->
                            </div><!-- .card -->
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
                                        <div class="nk-tb-col "><span class="sub-text">Store</span></div>
                                        <div class="nk-tb-col text-center"><span class="sub-text">Status</span></div>
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
                                            <span>{!! Illuminate\Support\Str::limit($review->review, 40) !!}</span>
                                        </div>
                                        <div class="nk-tb-col"><a href="{{route('admin.stores.show',$review->store)}}">
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
                        @if(count($tickets))
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
                                    @foreach ($tickets->take(10) as $ticket)
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
                                  
                                </ul>
                            </div>
                            
                        </div>
                        @endif
                        {{-- <div class="col-xxl-6">
                            <div class="card card-full">
                                <div class="nk-ecwg nk-ecwg8 h-100">
                                    <div class="card-inner">
                                        <div class="card-title-group mb-3">
                                            <div class="card-title">
                                                <h6 class="title">Sales Statistics</h6>
                                            </div>
                                            <div class="card-tools">
                                                <div class="dropdown">
                                                    <a href="#" class="dropdown-toggle link link-light link-sm dropdown-indicator" data-toggle="dropdown">Weekly</a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                        <ul class="link-list-opt no-bdr">
                                                            <li><a href="#"><span>Daily</span></a></li>
                                                            <li><a href="#" class="active"><span>Weekly</span></a></li>
                                                            <li><a href="#"><span>Monthly</span></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="nk-ecwg8-legends">
                                            <li>
                                                <div class="title">
                                                    <span class="dot dot-lg sq" data-bg="#6576ff"></span>
                                                    <span>Total Order</span>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">
                                                    <span class="dot dot-lg sq" data-bg="#eb6459"></span>
                                                    <span>Canceled Order</span>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="nk-ecwg8-ck">
                                            <canvas class="ecommerce-line-chart-s4" id="salesStatistics"></canvas>
                                        </div>
                                        <div class="chart-label-group pl-5">
                                            <div class="chart-label">01 Jul, 2020</div>
                                            <div class="chart-label">30 Jul, 2020</div>
                                        </div>
                                    </div><!-- .card-inner -->
                                </div>
                            </div><!-- .card -->
                        </div><!-- .col --> --}}
                        
                        
                    </div><!-- .row -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
 <script>"use strict";

    !function (NioApp, $) {
      "use strict";
    

      var totalSales = {
    labels: ["01 Jan", "02 Jan", "03 Jan", "04 Jan", "05 Jan", "06 Jan", "07 Jan", "08 Jan", "09 Jan", "10 Jan", "11 Jan", "12 Jan", "13 Jan", "14 Jan", "15 Jan", "16 Jan", "17 Jan", "18 Jan", "19 Jan", "20 Jan", "21 Jan", "22 Jan", "23 Jan", "24 Jan", "25 Jan", "26 Jan", "27 Jan", "28 Jan", "29 Jan", "30 Jan"],
    dataUnit: 'Sales',
    lineTension: .3,
    datasets: [{
      label: "Sales",
      color: "#9d72ff",
      background: NioApp.hexRGB('#9d72ff', .25),
      data: [130, 105, 125, 115, 110, 95, 131, 110, 115, 120, 111, 97, 113, 107, 122, 100, 85, 110, 130, 107, 90, 105, 123, 115, 100, 117, 125, 95, 137, 101]
    }]
  };
  var totalOrders = {
    labels: ["01 Jan", "02 Jan", "03 Jan", "04 Jan", "05 Jan", "06 Jan", "07 Jan", "08 Jan", "09 Jan", "10 Jan", "11 Jan", "12 Jan", "13 Jan", "14 Jan", "15 Jan", "16 Jan", "17 Jan", "18 Jan", "19 Jan", "20 Jan", "21 Jan", "22 Jan", "23 Jan", "24 Jan", "25 Jan", "26 Jan", "27 Jan", "28 Jan", "29 Jan", "30 Jan"],
    dataUnit: 'Orders',
    lineTension: .3,
    datasets: [{
      label: "Orders",
      color: "#7de1f8",
      background: NioApp.hexRGB('#7de1f8', .25),
      data: [85, 125, 105, 115, 130, 106, 141, 110, 95, 120, 111, 105, 113, 107, 122, 100, 95, 110, 120, 107, 100, 105, 123, 115, 110, 117, 125, 75, 95, 101]
    }]
  };
  var totalCustomers = {
    labels: ["01 Jan", "02 Jan", "03 Jan", "04 Jan", "05 Jan", "06 Jan", "07 Jan", "08 Jan", "09 Jan", "10 Jan", "11 Jan", "12 Jan", "13 Jan", "14 Jan", "15 Jan", "16 Jan", "17 Jan", "18 Jan", "19 Jan", "20 Jan", "21 Jan", "22 Jan", "23 Jan", "24 Jan", "25 Jan", "26 Jan", "27 Jan", "28 Jan", "29 Jan", "30 Jan"],
    dataUnit: 'Customers',
    lineTension: .3,
    datasets: [{
      label: "Customers",
      color: "#83bcff",
      background: NioApp.hexRGB('#83bcff', .25),
      data: [92, 105, 125, 85, 110, 106, 131, 105, 110, 115, 135, 105, 120, 85, 122, 100, 125, 110, 120, 125, 85, 105, 123, 115, 90, 117, 125, 100, 95, 65]
    }]
  };

  function ecommerceLineS1(selector, set_data) {
    var $selector = selector ? $(selector) : $('.ecommerce-line-chart-s1');
    $selector.each(function () {
      var $self = $(this),
          _self_id = $self.attr('id'),
          _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

      var selectCanvas = document.getElementById(_self_id).getContext("2d");
      var chart_data = [];

      for (var i = 0; i < _get_data.datasets.length; i++) {
        chart_data.push({
          label: _get_data.datasets[i].label,
          tension: _get_data.lineTension,
          backgroundColor: _get_data.datasets[i].background,
          borderWidth: 2,
          borderColor: _get_data.datasets[i].color,
          pointBorderColor: 'transparent',
          pointBackgroundColor: 'transparent',
          pointHoverBackgroundColor: "#fff",
          pointHoverBorderColor: _get_data.datasets[i].color,
          pointBorderWidth: 2,
          pointHoverRadius: 4,
          pointHoverBorderWidth: 2,
          pointRadius: 4,
          pointHitRadius: 4,
          data: _get_data.datasets[i].data
        });
      }

      var chart = new Chart(selectCanvas, {
        type: 'line',
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
            titleFontSize: 10,
            titleFontColor: '#fff',
            titleMarginBottom: 4,
            bodyFontColor: '#fff',
            bodyFontSize: 10,
            bodySpacing: 4,
            yPadding: 6,
            xPadding: 6,
            footerMarginTop: 0,
            displayColors: false
          },
          scales: {
            yAxes: [{
              display: false,
              ticks: {
                beginAtZero: true,
                fontSize: 12,
                fontColor: '#9eaecf',
                padding: 0
              },
              gridLines: {
                color: NioApp.hexRGB("#526484", .2),
                tickMarkLength: 0,
                zeroLineColor: NioApp.hexRGB("#526484", .2)
              }
            }],
            xAxes: [{
              display: false,
              ticks: {
                fontSize: 12,
                fontColor: '#9eaecf',
                source: 'auto',
                padding: 0,
                reverse: NioApp.State.isRTL
              },
              gridLines: {
                color: "transparent",
                tickMarkLength: 0,
                zeroLineColor: NioApp.hexRGB("#526484", .2),
                offsetGridLines: true
              }
            }]
          }
        }
      });
    });
  } // init chart


  NioApp.coms.docReady.push(function () {
    ecommerceLineS1();
  });

      var salesStatistics = {
        labels: ["01 Jan", "02 Jan", "03 Jan", "04 Jan", "05 Jan", "06 Jan", "07 Jan", "08 Jan", "09 Jan", "10 Jan", "11 Jan", "12 Jan", "13 Jan", "14 Jan", "15 Jan", "16 Jan", "17 Jan", "18 Jan", "19 Jan", "20 Jan", "21 Jan", "22 Jan", "23 Jan", "24 Jan", "25 Jan", "26 Jan", "27 Jan", "28 Jan", "29 Jan", "30 Jan"],
        dataUnit: 'People',
        lineTension: .4,
        datasets: [{
          label: "Total orders",
          color: "#9d72ff",
          dash: 0,
          background: NioApp.hexRGB('#9d72ff', .15),
          data: [3710, 4820, 4810, 5480, 5300, 5670, 6660, 4830, 5590, 5730, 4790, 4950, 5100, 5800, 5950, 5850, 5950, 4450, 4900, 8000, 7200, 7250, 7900, 8950, 6300, 7200, 7250, 7650, 6950, 4750]
        }, {
          label: "Canceled orders",
          color: "#eb6459",
          dash: [5],
          background: "transparent",
          data: [110, 220, 810, 480, 600, 670, 660, 830, 590, 730, 790, 950, 100, 800, 950, 850, 950, 450, 900, 0, 200, 250, 900, 950, 300, 200, 250, 650, 950, 750]
        }]
      };
    
      function ecommerceLineS4(selector, set_data) {
        var $selector = selector ? $(selector) : $('.ecommerce-line-chart-s4');
        $selector.each(function () {
          var $self = $(this),
              _self_id = $self.attr('id'),
              _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;
    
          var selectCanvas = document.getElementById(_self_id).getContext("2d");
          var chart_data = [];
    
          for (var i = 0; i < _get_data.datasets.length; i++) {
            chart_data.push({
              label: _get_data.datasets[i].label,
              tension: _get_data.lineTension,
              backgroundColor: _get_data.datasets[i].background,
              borderWidth: 2,
              borderDash: _get_data.datasets[i].dash,
              borderColor: _get_data.datasets[i].color,
              pointBorderColor: 'transparent',
              pointBackgroundColor: 'transparent',
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: _get_data.datasets[i].color,
              pointBorderWidth: 2,
              pointHoverRadius: 4,
              pointHoverBorderWidth: 2,
              pointRadius: 4,
              pointHitRadius: 4,
              data: _get_data.datasets[i].data
            });
          }
    
          var chart = new Chart(selectCanvas, {
            type: 'line',
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
              maintainAspectRatio: false,
              tooltips: {
                enabled: true,
                rtl: NioApp.State.isRTL,
                callbacks: {
                  title: function title(tooltipItem, data) {
                    return data['labels'][tooltipItem[0]['index']];
                  },
                  label: function label(tooltipItem, data) {
                    return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']];
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
              },
              scales: {
                yAxes: [{
                  display: true,
                  stacked: _get_data.stacked ? _get_data.stacked : false,
                  position: NioApp.State.isRTL ? "right" : "left",
                  ticks: {
                    beginAtZero: true,
                    fontSize: 11,
                    fontColor: '#9eaecf',
                    padding: 10,
                    callback: function callback(value, index, values) {
                      return '$ ' + value;
                    },
                    min: 0,
                    stepSize: 3000
                  },
                  gridLines: {
                    color: NioApp.hexRGB("#526484", .2),
                    tickMarkLength: 0,
                    zeroLineColor: NioApp.hexRGB("#526484", .2)
                  }
                }],
                xAxes: [{
                  display: false,
                  stacked: _get_data.stacked ? _get_data.stacked : false,
                  ticks: {
                    fontSize: 9,
                    fontColor: '#9eaecf',
                    source: 'auto',
                    padding: 10,
                    reverse: NioApp.State.isRTL
                  },
                  gridLines: {
                    color: "transparent",
                    tickMarkLength: 0,
                    zeroLineColor: 'transparent'
                  }
                }]
              }
            }
          });
        });
      } // init chart
    
    
      NioApp.coms.docReady.push(function () {
        ecommerceLineS4();
      });
        var converted = '{{$converted}}';
        var notconverted ='{{$notconverted}}';

      var trafficSources = {
        labels: ["Converted", "Not Converted",],
        dataUnit: 'People',
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
    {!! $chart1->renderChartJsLibrary() !!}
    {!! $chart1->renderJs() !!}  
@endpush