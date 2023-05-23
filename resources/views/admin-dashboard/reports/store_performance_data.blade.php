@if(count($clicks))
<div class="row g-gs">    
    <div class="col-xxl-6">
        <div class="card h-100">
            <div class="card-inner mb-n2 border-0">
                <div class="card-title-group">
                    <div class="card-title card-title-sm">
                        <h6 class="title">Most visited stores</h6>
                    </div>
                    
                </div>
            </div>
            <div class="nk-tb-list is-loose">
                <div class="nk-tb-item nk-tb-head">
                    <div class="nk-tb-col"><span>Store</span></div>
                    <div class="nk-tb-col"><span>Network</span></div>
                    <div class="nk-tb-col"><span>Visits</span></div>
                    <div class="nk-tb-col"><span>Cashbacks</span></div>
                    <div class="nk-tb-col"><span>Conversion Rate</span></div>
                </div><!-- .nk-tb-head -->
                @foreach($clicks as $click)
                
                <div class="nk-tb-item">
                    <div class="nk-tb-col">
                        <div class="icon-text"><a href="@if($click->store){{route(getAdminPrefix() . '.stores.show_store')}}?slug={{$click->store->slug}}@else # @endif">
                            
                            <span class="tb-lead"> <em class="text-primary icon ni ni-cart-fill mr-2"></em>{{$click->store->name ?? ''}}</span></a>
                        </div>
                    </div>
                    <div class="nk-tb-col">
                        <span class="tb-sub tb-amount"><span>{{$click->store->network->name ?? ''}}</span></span>
                    </div>
                    
                    <div class="nk-tb-col ">
                        <span class="tb-sub">{{$click->count}}</span>
                    </div>
                    <div class="nk-tb-col ">
                        <span class="tb-sub">{{ $click->store ? count($click->store->commissions) : ''}}</span>
                    </div>
                    <div class="nk-tb-col ">
                        <span class="tb-sub">{{ $click->store ? round(((count($click->store->commissions)*100)/$click->count),2) : '0'}}%</span>
                    </div>
                </div><!-- .nk-tb-item -->
                @endforeach
                <div class="nk-block-between-md g-3 card-inner adddd">
                    <div class="pagination g" route="{{$route}}">
                        {!! $clicks->links()!!}                             
                                            
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
       
        var _token = $("input[name=_token]").val();
        var store_id = $("select[name=store_id]").val();
        var start_date = $("input[name=start_date]").val();
        var end_date = $("input[name=end_date]").val();
        $.ajax({
          url:'{{route("admin.reports.search_performance")}}?page='+page,
          method:"POST",
          data:{_token:_token,store_id:store_id,start_date:start_date,end_date:end_date,page:page},
          success:function(data)
          {
           $('#report_data').html(data);
           $('html, body').animate({ scrollTop: 0 }, 'slow');
          }
        });

     }  

     if(route=='index'){
         
        var _token = $("input[name=_token]").val();
        $.ajax({
            url:"{{route(getAdminPrefix() . '.reports.fetch_performance')}}?page="+page,
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
           