@extends('layouts.frontend.app')
@section('content')
{{-- <div class="page-header">
    <div class="page-header__container container">
        <div class="page-header__breadcrumb">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.html">Home</a>
                        <svg class="breadcrumb-arrow" width="6px" height="9px">
                            <use xlink:href="images/sprite.svg#arrow-rounded-right-6x9"></use>
                        </svg>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="">Breadcrumb</a>
                        <svg class="breadcrumb-arrow" width="6px" height="9px">
                            <use xlink:href="images/sprite.svg#arrow-rounded-right-6x9"></use>
                        </svg>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">My Account</li>
                </ol>
            </nav>
        </div>
        <div class="page-header__title">
            <h1>My Account</h1>
        </div>
    </div>
</div> --}}
<div class="block mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-3 d-flex">
                @include('client-dashboard.side-nav')
                
            </div>
            <div class="col-12 col-lg-9 mt-4 mt-lg-0">
                @include('flash::message')
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h5>Edit Profile</h5>
                    </div>
                    <div class="card-divider"></div>
                    <div class="card-body">
                    <form action="{{route('account.profile.update',$user)}}" class="gy-3 form-validate is-alter user-form" method="POST">
                            @csrf
                            @method('PUT')
                        <div class="row no-gutters">
                            <div class="col-12 col-lg-7 col-xl-6">
                                <div class="form-group px-1">
                                    <label for="profile-first-name">First Name</label>
                                    <input type="text" class="form-control" id="profile-first-name" name="firstname" placeholder="First Name" value="{{$user->first_name}}" required>
                                </div>
                            </div>
                            <div class="col-12 col-lg-7 col-xl-6">
                                <div class="form-group px-1">
                                    <label for="profile-last-name">Last Name</label>
                                    <input type="text" class="form-control" id="profile-last-name" name="lastname" placeholder="Last Name" value="{{$user->last_name}}" required>
                                </div>
                            </div>
                            <div class="col-12 col-lg-7 col-xl-6">
                                <div class="form-group px-1">
                                    <label for="profile-email">Email Address</label>
                                    <input type="email" class="form-control" id="profile-email" name="email" disabled placeholder="Email Address" value="{{$user->email}}" required>
                                </div>
                            </div>
                            <div class="col-12 col-lg-7 col-xl-6">
                                <div class="form-group px-1">
                                    <label for="profile-phone">Phone Number</label>
                                    <input type="text" class="form-control" id="profile-phone" placeholder="Phone Number" name="phone" value="{{$user->phone}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-12 col-xl-12">
                                <div class="form-group px-1">
                                    <label for="profile-phone">Intro</label>
                                    <textarea name="intro" id="intro" class="form-control" rows="5" >{{$user->intro}}</textarea>
                                   
                                </div>
                            </div>
                            <div class="col-12 col-lg-7 col-xl-6">

                                <div class="form-group mt-2 mb-0">
                                    <button class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    
<script>
    jQuery.validator.addMethod("regex", function(value, element) {
          return this.optional(element) || /^[A-Za-z ]+$/i.test(value);
        }, "Only alphabetic name is allow");
        
        $('.form-validate').validate({
            errorClass: 'invalid-feedback d-block',
            rules: {
                firstname: {
                    required: true,
                    regex: true
                },
                lastname: {
                required: true,
                regex: true
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    number: true,
                    maxlength: 15
                },
            },
            submitHandler: function(form) {
                if ($(form).valid())
                form.submit();
                return false;
            }
        });
</script>

@endpush