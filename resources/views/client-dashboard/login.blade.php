@extends('layouts.frontend.app')
@section('content')
<div class="block mt-5">
    <div class="container">
        <div class="col-md-9 d-flex flex-column mx-auto">
            <div class="card flex-grow-1 mb-md-0">
                <div class="card-body">
                    <h3 class="card-title">Login</h3>
                    <div class="row">
                        <div class="col-md-7 d-flex flex-column mx-auto">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter email" required autocomplete="email" autofocus>
                                    @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" required class="form-control" placeholder="Password">
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
                                <button type="submit" class="btn btn-primary mt-1">Login</button>
                            </form>
                        </div>

                        @if(isFacebookEnabled() || isGoogleEnabled())
                            
                            <div class="ml-3" style="border-left:1px solid rgba(0,0,0,0.1)"></div>

                            <div class="col-md-4 d-flex flex-column">
                            @if(isFacebookEnabled())
                                <a class="btn btn-primary border-0 ml-4 mt-4" style="background-color: #3b5998; border-radius: 2px" href="{{ url('/login/facebook') }}" role="button">
                                    <i class="fab fa-facebook-f"> Login with Facebook</i></a>
                                <br>
                            @endif
                            @if(isGoogleEnabled())
                                <a class="btn btn-primary border-0 ml-4 mt-3" style="background-color: #dd4b39; border-radius: 2px" href="{{ url('/login/google') }}" role="button">
                                    <i class="fab fa-google"></i> Login with Google</a>
                                <br>
                            @endif
                            {{-- @if(isAppleEnabled())
                                <a class="btn btn-primary border-0 ml-4 mt-3" style="background-color: black; border-radius: 2px" href="#" role="button">
                                    <i class="fab fa-apple"></i> Login with Apple</a>
                            @endif--}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection