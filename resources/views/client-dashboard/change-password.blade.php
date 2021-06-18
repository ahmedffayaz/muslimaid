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
                <div class="card">
                    @include('flash::message')
                    <div class="card-header">
                        <h5>Change Password</h5>
                    </div>
                    <div class="card-divider"></div>
                    <div class="card-body">
                        <div class="row no-gutters">
                            <div class="col-12 col-lg-7 col-xl-6">
                                <form action="{{route('account.save_password')}}" method="post">
                                    @csrf
                                <div class="form-group">
                                    <label for="password-new">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="New Password">
                                </div>
                                <div class="form-group">
                                    <label for="password-confirm">Reenter New Password</label>
                                    <input type="password" class="form-control" id="password-confirm" name="password_confirmation" placeholder="Reenter New Password">
                                </div>
                                <div class="form-group mt-5 mb-0">
                                    <button class="btn btn-primary" type="submit">Change</button>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection