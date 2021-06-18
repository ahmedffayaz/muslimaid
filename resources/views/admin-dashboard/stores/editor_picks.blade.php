@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Editor Picks</h3>
                            <div class="nk-block-des text-soft">
                             
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt"><a href="{{route('admin.stores.index')}}" class="btn btn-primary btn-sm"><em class="icon ni ni-cart-fill"></em><span>All Stores</span></a></li>
                                        <li class="nk-block-tools-opt"><a href="{{route('admin.stores.create')}}"  data-toggle="modal" data-target="#category-modal" class="btn btn-primary btn-sm"><em class="icon ni ni-plus"></em><span>Add Editor Pick</span></a></li>
                                        <li><a href="{{route('admin.stores.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                      
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
                                        <div><form action="{{route('admin.stores.search_stores')}}" class="form-validate is-alter search_form" method="POST">
                                            @csrf
                                            <div class="row g-4 justify-content-md-center">
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label" for="store_id">Store ID</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="store_id" value="" name="store_id">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label" for="pay-amount-1">Store name</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="pay-amount-1" value="" name="store_name">
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
                                                        <label class="form-label" for="status">Status</label>
                                                        <div class="form-control-wrap ">
                                                            <select class="form-select form-control" data-search="on" id="status" name="status">
                                                                <option value="-1">Any</option>
                                                                <option value="pending review">Pending Review</option>
                                                                <option value="active">Active</option>
                                                                <option value="disabled">Disabled</option>
                                                                <option value="closed">Closed at Network</option>
                                                                <option value="error">Error</option>
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
                                    
                                    @include('admin-dashboard.stores.picks_data')                                   
                                    
                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                           
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
<!-- @@ Category Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="category-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
              
                   
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Add Editor Pick</span></div>

                        {{-- <div class="nk-file-name-sub">Project</div> --}}
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="" class=" p-4">
                <form action="{{route('admin.stores.create_editor_picks')}}" class="gy-3 form-validate is-alter category_form" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label" for="default-06">Category</label>
                                <div class="form-control-wrap ">
                                    <div class="">
                                        <select class="form-control form-select" id="default-06" data-search="on" name="category_id" required>
                                            
                                           @foreach ($categories as $category)
                                                <option value="{{$category->id}}" style="font-weight:bold">{{$category->name}}</option>
                                                @if(count($category->childs))
                                                    @include('admin-dashboard.categories.child_input',['childs' => $category->childs,'isEdit'=> 1 ,'category'=>$category,'dashes'=>'~'])
                                                @endif
                                            @endforeach                                           
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                       <div class="col-md-12">
                            <label class="form-label" for="default-06">Editor Picks (select 5 stores)</label>
                                <div class="form-control-wrap ">
                                    <div class="">
                                        <select class="form-control form-select select-2" name="picks[]" required multiple>
                
                                            @foreach ($stores as $store)
                                              <option  @if(in_array($store->id, $category->picks->pluck('store_id')->toArray())) selected  @endif value="{{$store->id}}">{{$store->id}} - {{$store->name}}</option>
                                            @endforeach
                                          
                                        </select>
                                    </div>
                                </div> 
                        </div>
                       
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
                

            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->
@endsection
@push('scripts')
    <script>
    $(document).ready(function(){
     $(document).on('click', '.pagination a', function(event){
        event.preventDefault(); 
        var route = $('.pagination').attr('route');
        var page = $(this).attr('href').split('page=')[1];
        
         if(route=='index'){
            
             pageurl = "{{route('admin.stores.fetch_editor_picks')}}?page="
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
            var network_id = $("select[name=network_id]").val();
            var store_id = $("input[name=store_id]").val();
            var status = $("select[name=status").val();
            var store_name = $("input[name=store_name]").val();
            $.ajax({
              url:'{{route("admin.stores.search_editor_picks")}}?page='+page,
              method:"POST",
              data:{_token:_token,network_id:network_id,store_id:store_id,store_name:store_name,status:status,page:page},
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
            var store_id = $("input[name=store_id]").val();
            var network_id = $("select[name=network_id]").val();
            var status = $("select[name=status").val();
            var store_name = $("input[name=store_name]").val();
            $.ajax({
              url:'{{route("admin.stores.search_editor_picks")}}',
              method:"POST",
              data:{_token:_token,network_id:network_id,store_id:store_id,store_name:store_name,status:status},
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