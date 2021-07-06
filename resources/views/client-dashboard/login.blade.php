@extends('layouts.frontend.app')
@section('content')
<div class="block mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 d-flex flex-column">
                <div class="card flex-grow-1 mb-md-0">
                    <div class="card-body">
                        <h3 class="card-title">Login</h3>
                        <form  method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label>Email address</label>
                                <input  type="email"  name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter email"required autocomplete="email" autofocus>
                                @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password"  name="password" required class="form-control" placeholder="Password">
                                @error('password')
                                             <span class="invalid-feedback d-block" role="alert">
                                                 <strong>{{ $message }}</strong>
                                             </span>
                                         @enderror
                                <small class="form-text text-muted">
                                    <a href="">Forgotten Password</a>
                                </small>
                                
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <span class="form-check-input input-check">
                                        <span class="input-check__body">
                                            <input class="input-check__input" type="checkbox" id="login-remember">
                                            <span class="input-check__box"></span>
                                            <svg class="input-check__icon" width="9px" height="7px">
                                                <use xlink:href="{{asset('frontend/images/sprite.svg')}}#check-9x7"></use>
                                            </svg>
                                        </span>
                                    </span>
                                    <label class="form-check-label" for="login-remember">Remember Me</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4">Login</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-flex flex-column mt-4 mt-md-0">
                <div class="card flex-grow-1 mb-0">
                    <div class="card-body">
                        <h3 class="card-title">Register</h3>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <input type="hidden" class="form-control form-control-lg" id="lastname" placeholder="Enter your last name" name="lastname" value="unnamed" >
                            <input type="hidden" class="form-control form-control-lg" id="firstname" placeholder="Enter your first name" name="firstname" value="unnamed">

                            <div class="form-group">
                                <label>Email address</label>
                                <input type="email"name="email"  class="form-control" placeholder="Enter email" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password" required placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label>Repeat Password</label>
                                <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required >
                            </div>
                            <button type="submit" class="btn btn-primary mt-4">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection