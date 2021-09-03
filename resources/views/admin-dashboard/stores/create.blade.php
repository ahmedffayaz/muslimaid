@extends('layouts.admin-dashboard.app')
@section('content')
    
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
                                <h4 class="title nk-block-title">Add Store</h4>
                                <div class="nk-block-des">
                                    {{-- <p>You can make style out your....</p> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                    <h5 class="card-title">Store Info</h5>
                                </div>
                                <form action="{{route('admin.stores.store')}}" class="gy-3 form-validate is-alter" method="POST">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="full-name-1">Store Name</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="full-name-1" name="store_name" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="default-06">Network</label>
                                                <div class="form-control-wrap ">
                                                    
                                                        <select class="form-select form-control" id="default-06" name="network_id" required>
                                                            @foreach ($networks as $network)
                                                            <option value="{{$network->id}}">{{$network->name}}</option>
                                                                
                                                            @endforeach
                                                        </select>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                       
                                        
                                        
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="pay-amount-1">Store url</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="pay-amount-1" name="store_url" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="tracking_url">Tracking url</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="tracking_url" name="tracking_url" required>
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <input name="description" type="hidden">
                                                <label class="form-label" for="phone-no-1">Description</label>
                                                <!-- Create the editor container -->
                                                <div  id="editor-container">
                                                  
                                                </div>
                                               
                                            </div>
                                        </div>
                                        
                                       
                                        {{-- <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="pay-amount-1">Terms & Conditions</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="pay-amount-1" value="" name="terms_conditions">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="pay-amount-1">Extra Info</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="pay-amount-1" value="" name="extra_info">
                                                </div>
                                            </div>
                                        </div> --}}
                                        
                                       
                                        <div class="col-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-lg btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!-- .nk-block -->
                    
                  
                    
                </div><!-- .components-preview -->
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
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
      $("#store_form").submit(function(e) {
          
        // Populate hidden form on submit
        var desc = document.querySelector('input[name=description]');
        desc.value = quill.root.innerHTML;
       
        
      });
      </script>

      <script>
            $('.form-validate').validate({
                errorClass: 'invalid-feedback d-block',
                rules: {
                    store_name: {
                        required: true,
                    },
                    store_url: {
                        required: true,
                        url: true
                    },
                    tracking_url: {
                        required: true,
                        url: true
                    }
                },
                submitHandler: function(form) {
                    if ($(form).valid())
                    form.submit();
                    return false;
                }
            });
    </script>
@endpush