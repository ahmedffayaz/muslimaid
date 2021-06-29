@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Vouchers</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have total {{$vouchers->total()}} vouchers.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt"><a href="#add-voucher-modal" data-toggle="modal" class="btn btn-primary btn-sm" ><em class="icon ni ni-plus"></em><span>Add Voucher</span></a></li>
                                        {{-- <li class="nk-block-tools-opt"><a href="{{route('admin.importer.vouchers')}}" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAlert"><em class="icon ni ni-download"></em><span>Import Vouchers</span></a></li> --}}
                                    
                                        <li><a href="{{route('admin.vouchers.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                      
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
                                            <div class="row g-4">
                                                <div class="col-lg-4">
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
                                            
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="stores_id">Store</label>
                                                        <div class="form-control-wrap ">
                                                            <select class="form-select form-control" data-search="on" id="stores_id" name="stores_id">
                                                                <option value="0">All</option>
                                                                @foreach ($stores as $store)
                                                                <option value="{{$store->id}}">{{$store->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>                        
                                                <div class="col-4 align-self-end">
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
                                    
                                    @include('admin-dashboard.vouchers.index_data')                                   
                                    
                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                           
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
  <!-- Add Voucher Modal -->
<div class="modal fade" tabindex="-1" id="add-voucher-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
           
            <div class="modal-header align-center">
                <div class="nk-file-title">
              
                   
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Add Voucher</span></div>
                        {{-- <div class="nk-file-name-sub">Project</div> --}}
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="add-voucher-form" class=" p-4">
                <form action="{{route('admin.vouchers.store')}}" class="gy-3 form-validate is-alter voucher_form" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="full-name-1">Title</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="full-name-1" name="link_name" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="default-06">Store</label>
                                <div class="form-control-wrap ">
                                    
                                        <select class="form-select form-control" data-search="on" id="default-06" name="store_id" required>
                                            @foreach ($stores as $store)
                                            <option value="{{$store->id}}">{{$store->name}}</option>
                                            @endforeach
                                        </select>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="pay-amount-1">Click url</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="pay-amount-1" name="click_url" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="phone-no-1">Destination url</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="phone-no-1" name="destination" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <input name="description" type="hidden">
                                <label class="form-label" for="phone-no-1">Description</label>
                                <textarea name="description" class="form-control " ></textarea>

                                <!-- Create the editor container -->
                                {{-- <div  id="editor-container">
                                  
                                </div> --}}
                               
                            </div>
                        </div>
                       
                       
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="form-label" for="promotion_type">Promotion Type</label>

                                    <div class="form-control-wrap ">
                                        
                                            <select class="form-select form-control select-2" data-search="on"   id="promotion_type" name="promotion_type" required>
                                              
                                                <option value="Coupon">Coupon</option>
                                                <option value="Sale/Discount">Sale/Discount</option>
                                             
                                            </select>
                                       
                                    </div>
                                </div>
                            
                            </div>
                        </div>
                       
                        
                        <div class="col-lg-6 coupon-div">
                            <div class="form-group">
                                <label class="form-label" for="coupon_code">Coupon Code</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="coupon_code" value="" name="coupon_code" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="sale_commission">Sale commission</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="sale_commission" value="" name="sale_commission" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="promotion_start_date">Promotion Start Date</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control date-picker" id="promotion_start_date" value="" name="promotion_start_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="promotion_end_date">Promotion End Date</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control date-picker" id="promotion_end_date" value="" name="promotion_end_date" required>
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
        </div>
    </div>
</div>
<!-- Modal Alert -->
<div class="modal fade" tabindex="-1" id="voucher-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
           
            <div class="modal-header align-center">
                <div class="nk-file-title">
              
                   
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Edit Voucher</span></div>
                        {{-- <div class="nk-file-name-sub">Project</div> --}}
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="voucher-form" class=" p-4">

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
            
             pageurl = "{{route('admin.vouchers.fetch')}}?page="
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
            var store_id = $("select[name=store_id]").val();
            $.ajax({
              url:'{{route("admin.vouchers.search_vouchers")}}?page='+page,
              method:"POST",
              data:{_token:_token,network_id:network_id,store_id:store_id},
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
        var network_id = $("select[name=network_id]").val();
        var store_id = $("select[name=stores_id]").val();

        $.ajax({
            url:'{{route("admin.vouchers.search_vouchers")}}',
            method:"POST",
            data:{_token:_token,network_id:network_id,store_id:store_id},
            success:function(data)
            {
                $('#table-data').html(data);
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
            
    });
    $(document).on('click', '.edit-voucher', function(event){
        event.preventDefault(); 
        var pageurl = $(this).attr('href');
        var _token = $("input[name=_token]").val();
        $.ajax({

            url:pageurl,
            method:"GET",
            data:{_token:_token},
            success:function(data)
            {
                $('#voucher-modal').modal('show');
                $('#voucher-form').html(data);
                $(".select-2").each(function() {
                    initializeSelect2($(this));
                    
                });
                $( "#promotion_end_date" ).datepicker();
                $( "#promotion_start_date" ).datepicker();
                checkVoucherType();
                
            }
            });
    });
    
        
});
function initializeSelect2(selectElementObj) {
    selectElementObj.select2({
        placeholder: function(){
            $(this).data('placeholder');
        }
    });
}
function checkVoucherType(){
    
    if ($('#promotion_type').val() == 'Coupon') {    
        $('.coupon-div').show();      
        $('#coupon_code').attr('required', 'required');
    }
    else{
        $('.coupon-div').hide();      
        $('#coupon_code').removeAttr('required').val('');
    }

}
$(document.body).on("change","#promotion_type",function(){
    checkVoucherType()
});
</script>  
<script>
    $(document).ready(function(){
       
       $(document).on('click', '.delete', function(event){
            var form_id = $(this).attr('form_id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then(function (result) {
                if (result.value) {
                $('#'+form_id).submit();
                }
           });
           event.preventDefault(); 
        });
   });
</script>
@endpush