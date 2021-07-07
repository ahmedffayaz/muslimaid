<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block-head nk-block-head-lg pb-2">
                        <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title fw-normal">{{$user->id}} - @if($user->first_name != 'unnamed' || $user->last_name != 'unnamed'){{$user->first_name}} {{$user->last_name}} @else {{$user->email}} @endif</h3>
                            <div class="user_id_checker d-none">{{$user->id}}</div>
                           
                        </div>
                        <div class="nk-block-head-content">
                            <div class="form-group stores">
                                <label class="form-label" for="user_select"></label>
                                <div class="form-control-wrap ">
                                    <div class="">
                                        <select class="form-control form-select select-2" data-search="on" name="open_store" id="user_select" required>
                                            @foreach ($users as $item)
                                            <option @if($user->id == $item->id) selected @endif value="{{$item->id}}">{{$item->id}} - @if($item->first_name != 'unnamed' || $item->last_name != 'unnamed') {{$item->first_name}} {{$item->last_name}} @else {{$item->email}} @endif</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        {{-- <li class="nk-block-tools-opt"><a href="{{route('admin.stores.edit',$store)}}" class="btn btn-primary btn-sm"><em class="icon ni ni-edit"></em><span>Edit Store</span></a></li> --}}
                                    
                                        {{-- <li><a href="{{route('admin.stores.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li> --}}
                                        
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div>
                    </div>
                    
                @include('flash::message')
                    
                    <div class="nk-block nk-block-lg">
                        <div class="card card-preview">
                            <div class="card-inner">
                                
                                <ul class="nav nav-tabs mt-n3">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tabItem5"><em class="icon ni ni-file-text"></em><span>Details</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem6"><em class="icon ni ni-link"></em><span>Clicks </span></a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem7"><em class="icon ni ni-sign-gbp"></em><span>Cashbacks</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem8"><em class="icon ni ni-money"></em><span>Payment Info</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem9"><em class="icon ni ni-lock-alt-fill"></em><span>Change Password</span></a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabItem5">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                                <h5 class="title">User Information</h5>
                                                {{-- <p>Basic info, like your name and address, that you use on Nio Platform.</p> --}}
                                            </div><!-- .nk-block-head -->
                                            <form action="{{route('admin.users.update',$user)}}" class="gy-3 form-validate is-alter user-form" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="row g-4">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="firstname">First Name</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="firstname" value="{{$user->first_name}}" name="firstname" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="lastname">Last Name</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="lastname" value="{{$user->last_name}}" name="lastname" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="email">Email</label>
                                                            <div class="form-control-wrap">
                                                                <input type="email" class="form-control" id="email" value="{{$user->email}}" name="email" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="phone-no-1">Phone</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="phone-no-1" value="{{$user->phone}}" name="phone">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label class="form-label" for="address">Address</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="address" value="{{$user->address ?? ''}}" name="address" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-lg-12">
                                                        <div class="card">
                                                            <input name="intro" type="hidden">
                                                            <label class="form-label" for="phone-no-1">Introduction</label>
                                                            <!-- Create the editor container -->
                                                            <div  id="editor-container">
                                                            {!!$user->intro ?? ''!!}
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="status">Status</label>
                                                            <div class="form-control-wrap ">
                                                                <div class="form-control-select">
                                                                    <select class="form-control" id="status" name="status" required>
                                                                        <option @if($user->status) selected @endif value="1">Active</option>
                                                                        <option @if(!$user->status) selected @endif value="0">In-active</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="roles">User Role</label>
                                                            <div class="form-control-wrap ">
                                                                <div class="form-control-select">
                                                                    <select class="form-control form-select select-2" id="roles" name="roles[]" required multiple>

                                                                        @foreach ($roles as $role)
                                                                        <option @if($user->hasRole($role->name)) selected @endif value="{{$role->id}}">{{$role->name}}</option>
                                                                            
                                                                        @endforeach
                                                                    </select>
                                                                </div>
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
                                    <div class="tab-pane" id="tabItem6">
                                        <h5 class="title mb-4 d-inline">Clicks</h5>
                                        <span id="clicks-data"></span>                                       
                                    </div>
                                    <div class="tab-pane" id="tabItem7">
                                        <h5 class="title mb-4 d-inline">Cashbacks</h5>
                                        <span id='cashbacks-data'></span>
                                        
                                    </div>
                                    <div class="tab-pane" id="tabItem8">
                                        <h5 class="title mb-4">Payment Info</h5>
                                        <form action="{{route('admin.users.payment_save')}}" class="gy-3 form-validate is-alter" id='payment_form' method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{$user->id}}">
                                            <div class="row g-4">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="payment_method">Payment Method</label>
                                                        <div class="form-control-wrap ">
                                                            <div class="">
                                                                <select class="form-control form-select select-2" id="payment_method" name="payment_method" required>
                                                                    @php
                                                                        $method =  $user->paymentInfo->payment_method ?? '';
                                                                    @endphp
                                                                    <option value="paypal" @if($method ==  'paypal') selected @endif>Paypal</option>
                                                                    <option value="bank" @if($method ==  'bank') selected @endif>Bank</option>
                                                                    <option value="cheque" @if($method == 'cheque') selected @endif> Cheque</option>
                                                                        
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 paypal-email">
                                                    <div class="form-group">
                                                        <label class="form-label" for="paypal_email">Paypal Email</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="paypal_email" name="paypal_email" required value="{{$user->paymentInfo->paypal_email ?? ''}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g4 cheque">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="first_name">First name</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="first_name" name="first_name" required value="{{$user->paymentInfo->first_name ?? ''}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="last_name">Last name</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="last_name" name="last_name" required value="{{$user->paymentInfo->last_name ?? ''}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="address">Address</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="address" name="address" required value="{{$user->paymentInfo->address ?? ''}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="city">City</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="city" name="city" required value="{{$user->paymentInfo->city ?? ''}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="postcode">Postcode</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="postcode" name="postcode" required value="{{$user->paymentInfo->postcode ?? ''}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g4 bank">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="account_name">Account Name</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="account_name" name="account_name" required value="{{$user->paymentInfo->account_name ?? ''}}"> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="bank_title">Bank Title</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="bank_title" name="bank_title" required value="{{$user->paymentInfo->bank_title ?? ''}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="account_number">Accont Number</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="account_number" name="account_number" required value="{{$user->paymentInfo->account_number ?? ''}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="bank_sort_code">
                                                            Bank Sort Code</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="bank_sort_code" name="bank_sort_code" required value="{{$user->paymentInfo->bank_sort_code ?? ''}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="bic">BIC</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="bic" name="bic" required value="{{$user->paymentInfo->bic ?? ''}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="row g-4">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-lg btn-primary">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        
                                    </div>
                                    
                                    <div class="tab-pane" id="tabItem9">
                                        <h5 class="title mb-4">Change Password</h5>
                                        <form action="{{route('admin.users.save_password',$user)}}" class="gy-3 form-validate is-alter" id='password_form' method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row g-4">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="password">New Password</label>
                                                        <div class="form-control-wrap">
                                                            <input type="password" class="form-control" id="password" value="" name="password" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="password_confirmation">Confirm New Password</label>
                                                        <div class="form-control-wrap">
                                                            <input type="password" class="form-control" id="password_confirmation" value="" name="password_confirmation" required>
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
                        </div><!-- .card-preview -->
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        window.livewire.on('userChange', () => {
           
            fetchCashbacks(1);
            fetchClicks(1);
            initializeSelect2();
            var quill = new Quill('#editor-container', {
                modules: {
                    toolbar: [
                    ['bold', 'italic'],
                    ['link', 'blockquote', 'code-block', 'image'],
                    [{ list: 'ordered' }, { list: 'bullet' }]
                    ]
                },
                placeholder: 'Compose an epic...',
                theme: 'snow'
            });

        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#user_select').on('change', function (e) {
            livewire.emit('changeEvent',e.target.value)
        });     
    });
</script>
<link rel="stylesheet" href="{{ asset('admin-dashboard/css/editors/quill.css?ver=2.2.0')}}">
<script src="{{ asset('admin-dashboard/js/libs/editors/quill.js?ver=2.2.0')}}"></script>
<script src="{{ asset('admin-dashboard/js/editors.js?ver=2.2.0')}}"></script>
<script>
    
    var quill = new Quill('#editor-container', {
        modules: {
            toolbar: [
            ['bold', 'italic'],
            ['link', 'blockquote', 'code-block', 'image'],
            [{ list: 'ordered' }, { list: 'bullet' }]
            ]
        },
        placeholder: 'Compose an epic...',
        theme: 'snow'
        });
        
    //   var form = document.querySelector('form');
        $(".user-form").submit(function(e) {
            
        // Populate hidden form on submit
        var desc = document.querySelector('input[name=intro]');
        desc.value = quill.root.innerHTML;
        
        
        });
</script>

<script>
    function fetchCashbacks(page){
        pageurl = "{{route('admin.users.cashbacks')}}?page="+page
        var _token = $("input[name=_token]").val();
        var user =  $('.user_id_checker').text(); 
        $.ajax({

        url:pageurl,
        method:"POST",
        data:{_token:_token,user:user},
        success:function(data)
        {
            $('#cashbacks-data').html(data);
            
        }
        });
    }
    function fetchClicks(page){
        pageurl = "{{route('admin.users.clicks')}}?page="+page
        var _token = $("input[name=_token]").val();
        var user =  $('.user_id_checker').text(); 
        $.ajax({

        url:pageurl,
        method:"POST",
        data:{_token:_token,user:user},
        success:function(data)
        {
            $('#clicks-data').html(data);
            
        }
        });
    }
    fetchCashbacks(1);
    fetchClicks(1);
    $(document).ready(function(){
        $(document).on('click', '#click-paginate .pagination a', function(event){
        event.preventDefault(); 
        
        var page = $(this).attr('href').split('page=')[1];
        fetchClicks(page);  
            

            });
            $(document).on('click', '#cashback-paginate .pagination a', function(event){
        event.preventDefault(); 
        
        var page = $(this).attr('href').split('page=')[1];
        fetchCashbacks(page);  
            

            });
    });
</script>
    
<!-- Update User-->
<script>
    $(document).ready( function() {
        $(document).on('submit', '.user-form', function(event){
    
            event.preventDefault();
            
            
            $.ajax({
            type:'PUT',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success:function(data){
            
                (function(NioApp, $){
                'use strict';
                toastr.clear();
                NioApp.Toast('User updated Successfully.', 'success');
            })(NioApp, jQuery);                
            },
            error: function(data){
                console.log("error");
                console.log(data);
            }
        });
    
        });
    });
</script>   
<script>
    $(document).ready( function() {
        $(document).on('submit', '#password_form', function(event){
            event.preventDefault();
            $.ajax({
            type:'PUT',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success:function(data){
            
                (function(NioApp, $){
                'use strict';
                toastr.clear();
                NioApp.Toast(data.message, data.updated);
            })(NioApp, jQuery);                
            },
            error: function(data){
                console.log("error");
                console.log(data);
            }
        });
    
        });
    });
</script>   

<script>
    $(document).ready( function() {
        $(document).on('submit', '#payment_form', function(event){
            event.preventDefault();
            $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success:function(data){
            
                (function(NioApp, $){
                'use strict';
                toastr.clear();
                NioApp.Toast(data.message, data.updated);
            })(NioApp, jQuery);                
            },
            error: function(data){
                console.log("error");
                console.log(data);
            }
        });
    
        });
    });
</script> 

<script>
        $('.cheque').hide();
        $('.bank').hide();

    function show_paypal(){
        $('.paypal-email').show();
        $('#paypal_email').attr('required', 'required');
    }

    function hide_paypal(){
        $('.paypal-email').hide();
        $('#paypal_email').removeAttr('required').val('');
    }

    function show_bank(){
        $('.bank').show();      
        $('#account_name').attr('required', 'required');
        $('#bank_title').attr('required', 'required');
        $('#account_number').attr('required', 'required');
        $('#bank_sort_code').attr('required', 'required');
        $('#bic').attr('required', 'required');
    }

    function hide_bank(){
        $('.bank').hide();
        $('#account_name').removeAttr('required').val('');
        $('#bank_title').removeAttr('required').val('');
        $('#account_number').removeAttr('required').val('');
        $('#bank_sort_code').removeAttr('required').val('');
        $('#bic').removeAttr('required').val('');
    }

    function hide_cheque(){
        $('.cheque').hide();     
        $('#first_name').removeAttr('required').val('');
        $('#last_name').removeAttr('required').val('');
        $('#address').removeAttr('required').val('');
        $('#city').removeAttr('required').val('');
        $('#postcode').removeAttr('required').val('');
    }

    function show_cheque(){
        $('.cheque').show();
        $('#first_name').attr('required', 'required');
        $('#last_name').attr('required', 'required');
        $('#address').attr('required', 'required');
        $('#city').attr('required', 'required');
        $('#postcode').attr('required', 'required');
    }


    $(document).ready(function() {
        if ($('#payment_method').val() == 'bank') {
            hide_cheque();
            hide_paypal();
            show_bank();
        }
        else if ($('#payment_method').val() == 'paypal') {
            show_paypal();
            hide_cheque();
            hide_bank();
        }
        else if ($('#payment_method').val() == 'cheque') {
            show_cheque();
            hide_bank();
            hide_paypal();
        }

    });
    
    $(document.body).on("change","#payment_method",function(){
        if (this.value == 'bank') {
            hide_cheque();
            hide_paypal();
            show_bank();

        }
        else if (this.value == 'paypal') {
            show_paypal();
            hide_cheque();
            hide_bank();
        }
        else if (this.value == 'cheque') {
            show_cheque();
            hide_bank();
            hide_paypal();
        }
    });

    // $(function(){
    //     // bind change event to select
    //     $('#user_select').on('change', function () {
    //         var url = $(this).val(); // get selected value
    //         if (url) { // require a URL
    //             window.location = url; // redirect
    //         }
    //         return false;
    //     });
    // });
    function initializeSelect2() {
        $('.select-2').select2({
            // maximumSelectionLength: 5,
            placeholder: function(){
                $(this).data('placeholder');
                
            }
        });
    }

</script> 
@endpush