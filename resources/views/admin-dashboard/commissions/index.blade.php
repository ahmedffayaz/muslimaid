@extends('layouts.admin-dashboard.app')
@section('content')
   

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Cashbacks</h3>
                            <div class="nk-block-des text-soft">
                                <p>Total {{$coms->total()}} cashbacks.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt"><a href="{{route('admin.commissions.create')}}" class="btn btn-primary btn-sm"><em class="icon ni ni-plus"></em><span>Add Cashback</span></a></li>
                                        {{-- <li class="nk-block-tools-opt"><a href="#"  class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAlert"><em class="icon ni ni-download"></em><span>Import Cashbacks</span></a></li> --}}
                                        <li><a href="{{route('admin.commissions.export')}}" data-toggle="tooltip" data-placement="top" title="Export cashbacks to CSV" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                     
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="card card-preview mb-4">
                    <div class="card-inner">
                        <div id="accordion-1" class="accordion accordion-s2">
                            <div class="accordion-item">
                                <a href="#" class="accordion-head collapsed" data-toggle="collapse" data-target="#accordion-item-1-1">
                                    <h6 class="title">Search</h6>
                                    <span class="accordion-icon"></span>
                                </a>
                                <div class="accordion-body collapse" id="accordion-item-1-1" data-parent="#accordion-1">
                                    <div class="accordion-inner">
                                        <div><form action="{{route('admin.stores.search_stores')}}" class="form-validate is-alter search_form card p-4 mb-4" method="POST">
                                            @csrf
                                            <div class="row g-4">
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label" for="user_id">User</label>
                                                        <div class="form-control-wrap ">
                                                            <select class="form-select form-control" data-search="on" id="user_id" name="user_id">
                                                                <option value="0">All</option>
                                                                @foreach ($users as $user)
                                                                <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                                                @endforeach
                                                            </select> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label" for="network_id">Network</label>
                                                        <div class="form-control-wrap ">
                                                            <select class="form-select form-control" data-search="on" id="network_id" name="network_id">
                                                                <option value="0">All</option>
                                                                @foreach ($networks as $network)
                                                                <option value="{{$network->id}}">{{$network->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label" for="store_id">Store</label>
                                                        <div class="form-control-wrap ">
                                                            <select class="form-select form-control" data-search="on" id="store_id" name="store_id">
                                                                <option value="0">All</option>
                                                                @foreach ($stores as $store)
                                                                <option value="{{$store->id}}">{{$store->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label" for="click_id">Exit Click</label>
                                                        <div class="form-control-wrap ">
                                                            <select class="form-select form-control" data-search="on" id="click_id" name="click_id">
                                                                <option value="0">All</option>
                                                                @foreach ($clicks as $click)
                                                                <option value="{{$click->id}}">{{$click->id}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>      
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label" for="status">Status</label>
                                                        <div class="form-control-wrap ">
                                                            
                                                            <select class="form-control form-select" name="status_id" required>
                                                                <option value="-1">Any</option>
                                                                @foreach ($statuses as $status)
                                                                <option value="{{$status->id}}">{{$status->status}}</option>
                                                                    
                                                                @endforeach
                                                                
                                                                    
                                                            
                                                            </select>
                                                        
                                                        </div>
                                                    </div>
                                                </div>                  
                                                <div class="col-2 align-self-end">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-success btn-block">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @include('flash::message')
                <div class="nk-block">
                    <div class="card card-stretch">
                        <div class="card-inner-group">
                            
                            <div class="card-inner px-0">
                                <div class="nk-tb-list nk-tb-ulist" id="table-data">
                                    
                                    @include('admin-dashboard.commissions.index_data')                                   
                                    
                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                           
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>


<!-- Modal Alert -->
<div class="modal fade" tabindex="-1" id="modalAlert">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
            <div class="modal-body modal-body-lg text-center">
                <div class="nk-modal">
                    <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-download bg-success"></em>
                    <h4 class="nk-modal-title">Run Importer</h4>
                    <div class="nk-modal-action">
                        <a href="{{route('admin.importer.commissions')}}" class="btn btn-lg btn-mw btn-primary">Run</a>
                    </div>
                </div>
            </div><!-- .modal-body -->
            <div class="modal-footer bg-lighter">
                <div class="text-center w-100">
                    <p>Import Commissions</p>
                </div>
            </div>
        </div>
    </div>
</div>

 
@endsection
@push('scripts')
    <script>
    $(document).ready(function(){
     $(document).on('click', '.pagination a', function(event){
        event.preventDefault(); 
        var route = $('.pagination').attr('route');
        var page = $(this).attr('href').split('page=')[1];
        
         if(route=='index'){
            
             pageurl = "{{route('admin.commissions.fetch')}}?page="
             var _token = $("input[name=_token]").val();
            $.ajax({

                url:pageurl+page,
                method:"POST",
                data:{_token:_token, page:page},
                success:function(data)
                {
                    $('#table-data').html(data);
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                }
                });
         } 

         if(route=='search'){
              
              
            var _token = $("input[name=_token]").val();
            var name = $("input[name=name]").val();
            var network_id = $("select[name=network_id]").val();
            var store_id = $("select[name=store_id]").val();
            var click_id = $("select[name=click_id]").val();
            var status_id = $("select[name=status_id]").val();
            var user_id = $("select[name=user_id]").val();
            $.ajax({
              url:'{{route("admin.commissions.search_commissions")}}?page='+page,
              method:"POST",
              data:{_token:_token,name:name,network_id:network_id,store_id:store_id,click_id:click_id,status_id:status_id,user_id:user_id},
              success:function(data)
              {
               $('#table-data').html(data);
               $('html, body').animate({ scrollTop: 0 }, 'slow');
              }
            });
         }       
     });
    });
    </script> 
    <script>
        $(document).ready(function(){
        
         $(document).on('submit', '.search_form', function(event){
            event.preventDefault(); 
              
            var _token = $("input[name=_token]").val();
            var name = $("input[name=name]").val();
            var network_id = $("select[name=network_id]").val();
            var store_id = $("select[name=store_id]").val();
            var click_id = $("select[name=click_id]").val();
            var status_id = $("select[name=status_id]").val();
            var user_id = $("select[name=user_id]").val();
            $.ajax({
              url:'{{route("admin.commissions.search_commissions")}}',
              method:"POST",
              data:{_token:_token,name:name,network_id:network_id,store_id:store_id,click_id:click_id,status_id:status_id,user_id:user_id},
              success:function(data)
              {
               $('#table-data').html(data);
               $('html, body').animate({ scrollTop: 0 }, 'slow');
              }
            });
            
         });
        
        });
        
        </script>  
@endpush