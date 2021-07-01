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
                            <h3 class="nk-block-title page-title">Earnings</h3>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            
                            
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="row mb-4">
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">Earning</span>
                                        <h3 class="mb-0 mt-2">{{ currency() }} {{$total_revenue}}</h3>
                                       
                                    </div>
                                    <div class="align-self-center text-center analytics-icon" style="    display: contents;">
                                        <em class="icon ni ni-coins text-info"></em>
                                    </div>
                                    
                                   
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">Pending Revenue</span>
                                        <h3 class="mb-0 mt-2">{{ currency() }} {{$pending_total_revenue}}</h3>
                                    </div>
                                    <div class="align-self-center text-center analytics-icon" style="    display: contents;">
                                        <em class="icon ni ni-coins text-info"></em>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">Cashbacks</span>
                                        <h3 class="mb-0 mt-2">{{$coms->total()}}</h3>
                                    </div>
                                    <div class="align-self-center text-center analytics-icon" style="    display: contents;">
                                        <em class="icon ni ni-growth-fill text-info"></em>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">Conversion Rate</span>
                                        <h3 class="mb-0 mt-2">@if(count($clicks)){{round(($coms->total()*100)/count($clicks),0)}}% @else 0% @endif</h3>
                                    </div>
                                    <div class="align-self-center text-center analytics-icon" style="    display: contents;">
                                        <em class="icon ni ni-reload text-info"></em>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-preview mb-4">
                    <div class="card-inner">
                        <form action="{{route('admin.reports.search_earnings')}}" class="form-validate is-alter earnings_form" method="POST">
                            @csrf
                            <div class="row g-4 justify-content-md-center">
                            
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label" for="store_id">Store</label>
                                        <div class="form-control-wrap ">
                                            <select class="form-select form-control" data-search="on" id="store_id" name="store_id">
                                                <option value="0">All</option>
                                                @foreach ($stores as $store)
                                                <option value="{{$store->id}}">{{$store->id}} - {{$store->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label" for="status">Status</label>
                                        <div class="form-control-wrap ">
                                            <select class="form-select form-control" data-search="on" id="status" name="status_id">
                                                <option value="0">Any</option>
                                                
                                                @foreach ($statuses as $status)
                                                <option value="{{$status->id}}">{{$status->status}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label" for="pay-amount-1">From</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control date-picker" id="pay-amount-1" value="" name="start_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label" for="pay-amount-1">To</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control date-picker" id="pay-amount-1" value="" name="end_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 align-self-end">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-block">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="nk-block" id="report_data">
                    @include('admin-dashboard.reports.earnings_data')
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function(){
    
     $(document).on('submit', '.earnings_form', function(event){
        event.preventDefault(); 
          
        var _token = $("input[name=_token]").val();
        var store_id = $("select[name=store_id]").val();
        var status_id = $("select[name=status_id").val();
        var start_date = $("input[name=start_date]").val();
        var end_date = $("input[name=end_date]").val();
        $.ajax({
          url:'{{route("admin.reports.search_earnings")}}',
          method:"POST",
          data:{_token:_token,store_id:store_id,start_date:start_date,end_date:end_date,status_id:status_id},
          success:function(data)
          {
           $('#report_data').html(data);
           $('html, body').animate({ scrollTop: 0 }, 'slow');
          }
        });
        
     });
    
    });
    
    </script>  
      
@endpush