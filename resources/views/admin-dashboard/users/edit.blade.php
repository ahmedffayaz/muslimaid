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
                                <h4 class="title nk-block-title">Edit User</h4>
                                <div class="nk-block-des">
                                    {{-- <p>You can make style out your....</p> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                    <h5 class="card-title">User Info</h5>
                                </div>
                                <form action="{{route('admin.users.update',$user)}}" class="gy-3 form-validate is-alter user-form" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="full-name-1">First Name</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="full-name-1" value="{{$user->first_name}}" name="firstname" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="full-name-1">Last Name</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="full-name-1" value="{{$user->last_name}}" name="lastname" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="full-name-1">Email</label>
                                                <div class="form-control-wrap">
                                                    <input type="email" class="form-control" id="full-name-1" value="{{$user->email}}" name="email" required>
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
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="pay-amount-1">Address</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="pay-amount-1" value="{{$user->address ?? ''}}" name="address" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="default-06">Status</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-control" id="default-06" name="status" required>
                                                            
                                                            <option @if($user->status) selected @endif value="1">Active</option>
                                                            <option @if(!$user->status) selected @endif value="0">In-active</option>
                                                                
                                                           
                                                        </select>
                                                    </div>
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
      $(".user-form").submit(function(e) {
          
        // Populate hidden form on submit
        var desc = document.querySelector('input[name=intro]');
        desc.value = quill.root.innerHTML;
       
        
      });</script>
@endpush