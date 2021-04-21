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
                                <h4 class="title nk-block-title">Change user password</h4>
                                <div class="nk-block-des">
                                    <p>Change Password for {{$user->first_name}} {{$user->last_name}} {{$user->email}}</p>
                                </div>
                            </div>
                        </div>
                @include('flash::message')

                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                    <h5 class="card-title">New Password</h5>
                                </div>
                                <form action="{{route('admin.users.save_password',$user)}}" class="gy-3 form-validate is-alter" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="full-name-1">New Password</label>
                                                <div class="form-control-wrap">
                                                    <input type="password" class="form-control" id="full-name-1" value="" name="password" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="full-name-1">Confirm New Password</label>
                                                <div class="form-control-wrap">
                                                    <input type="password" class="form-control" id="full-name-1" value="" name="password_confirmation" required>
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