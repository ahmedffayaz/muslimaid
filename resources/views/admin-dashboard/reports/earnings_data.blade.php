
@if(count($coms))
<div class="row g-gs">
    <div class="col-xxl-6">
        <div class="card h-100">
            <div class="card-inner mb-n2">
                <div class="card-title-group">
                    <div class="card-title card-title-sm">
                        <h6 class="title">Earning Report</h6>
                    </div>
                </div>
            </div>
            <div class="nk-tb-list is-loose">
                <div class="nk-tb-item nk-tb-head">
                    <div class="nk-tb-col"><span>Store</span></div>
                    <div class="nk-tb-col"><span>User</span></div>
                    <div class="nk-tb-col"><span>Network</span></div>
                    <div class="nk-tb-col"><span>Exit Click ID</span></div>
                    <div class="nk-tb-col"><span>Network Commission</span></div>
                    <div class="nk-tb-col"><span>User Cashback</span></div>
                    <div class="nk-tb-col"><span>Earning</span></div>
                    <div class="nk-tb-col"><span>Date</span></div>
                    <div class="nk-tb-col text-right"><span>Status</span></div>
                </div><!-- .nk-tb-head -->
                @foreach($coms as $com)
                
                <div class="nk-tb-item">
                    <div class="nk-tb-col">
                        <div class="icon-text"><a href="{{route('admin.stores.show_store')}}?slug={{$com->store->slug}}">
                            
                            <span class="tb-lead"> <em class="text-primary icon ni ni-cart-fill mr-2"></em>{{$com->store->name}}</span></a>
                        </div>
                    </div>
                    <div class="nk-tb-col">
                        <span class="tb-sub"><span>{{$com->user->first_name ?? ''}} {{$com->user->last_name ?? ''}}</span></span>
                    </div>
                    <div class="nk-tb-col">
                        <span class="tb-sub"><span>{{$com->store->network->name}}</span></span>
                    </div>
                    <div class="nk-tb-col ">
                        <span>{{$com->exit_click_id}}</span>
                    </div>
                    
                
                    <div class="nk-tb-col ">
                        <span><span class="currency">{{ currency() }}</span>{{number_format((float)$com->network_commission, 2, '.', '')}}</span>
                    </div>
                    <div class="nk-tb-col ">
                        <span><span class="currency">{{ currency() }}</span>{{number_format((float)$com->amount, 2, '.', '')}}</span>
                    </div>
                    <div class="nk-tb-col ">
                        <span><span class="currency">{{ currency() }}</span>{{number_format((float)$com->network_commission - $com->amount, 2, '.', '')}}</span>
                    </div>
                    <div class="nk-tb-col ">
                        <span>{{$com->event_date}}</span>
                    </div>
                    
                    <div class="nk-tb-col  text-right">
                        @if(($com->statusMap->status ?? $com->status) == "confirmed")
                            <span class="tb-status badge badge-success">{{ $com->statusMap->status ?? $com->status}}</span>
                        @elseif(($com->statusMap->status ?? $com->status) == "paid")
                            <span class="tb-status badge badge-info">{{ $com->statusMap->status ?? $com->status}}</span>
                        @elseif(($com->statusMap->status ?? $com->status) == "failed")
                            <span class="tb-status badge badge-danger">{{ $com->statusMap->status ?? $com->status}}</span>
                        @elseif(($com->statusMap->status ?? $com->status) == "pending")
                            <span class="tb-status badge badge-warning">{{ $com->statusMap->status ?? $com->status}}</span>
                        @elseif(($com->statusMap->status ?? $com->status) == "processing donation")
                            <span class="tb-status badge badge-light">{{ $com->statusMap->status ?? $com->status}}</span>
                        @elseif(($com->statusMap->status ?? $com->status) == "donated")
                            <span class="tb-status badge badge-light">{{ $com->statusMap->status ?? $com->status}}</span>
                        @elseif(($com->statusMap->status ?? $com->status) == "processing")
                            <span class="tb-status badge badge-success">{{ $com->statusMap->status ?? $com->status}}</span>
                        @endif
                    </div>
                </div><!-- .nk-tb-item -->
                @endforeach
                <div class="nk-block-between-md g-3 card-inner">
                    <div class="pagination g" route="{{$route}}">
                        {!! $coms->links()!!}                             
                                            
                        </div> 
                    
                    
                </div><!-- .nk-block-between -->      
            </div><!-- .nk-tb-list -->
        </div><!-- .card -->
    </div><!-- .col -->
    
</div><!-- .row -->
@else 
    <h3 class="m-auto text-center py-5">No results found</h3> 
@endif               
@push('scripts')

<script>
$(document).ready(function(){

    $(document).on('click', '.pagination a', function(event){
    event.preventDefault(); 
    var route = $('.pagination').attr('route');
    var page = $(this).attr('href').split('page=')[1];

    if(route=='search'){
            $('#report_data').html(`<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
            </div></div>`);
            
        var _token = $("input[name=_token]").val();
        var store_id = $("select[name=store_id]").val();
        var status_id = $("select[name=status_id").val();
        var start_date = $("input[name=start_date]").val();
        var end_date = $("input[name=end_date]").val();
        $.ajax({
        url:'{{route("admin.reports.search_earnings")}}?page='+page,
        method:"POST",
        data:{_token:_token,store_id:store_id,start_date:start_date,end_date:end_date,status_id:status_id,page:page},
        success:function(data)
        {
        $('#report_data').html(data);
        $('html, body').animate({ scrollTop: 0 }, 'slow');
        }
        });
    

    }
    if(route=='index'){

        $('#report_data').html(`<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
            </div></div>`);

        var pageurl = "{{route('admin.reports.fetch_earnings')}}?page="
        var _token = $("input[name=_token]").val();
        $.ajax({
            url:pageurl+page,
            method:"POST",
            data:{_token:_token, page:page},
            success:function(data)
            {
            $('#report_data').html(data);
            $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
    }

    });

});
</script>
@endpush