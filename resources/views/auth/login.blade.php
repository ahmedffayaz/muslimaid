@extends('layouts.frontend.app')
@section('content')
<div class="block mt-5">
    <div class="container">
        <div class="col-md-10 d-flex flex-column mx-auto">
            <div class="card flex-grow-1 mb-md-0">
   
            @if(Session::has('login-expired'))
                    <div class = "container alert alert-danger alert-dismissible fade show alert-important" role = "alert">
                    Your Session has expired! Please login again.
                    <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Close">
                        <span aria-hidden = "true">&times;</span>
                    </button>
                </div>
            @endif

            @if(Session::has('email-not-verified'))
                    <div class = "container alert alert-danger alert-dismissible fade show alert-important" role = "alert">
                        You need to confirm your account.please check your email.
                    <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Close">
                        <span aria-hidden = "true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session()->has('message'))
                    <div class = "container alert {{ session('alert-class') }} alert-dismissible fade show alert-important" role = "alert">
                        {{ session('message') }}.
                    <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Close">
                        <span aria-hidden = "true">&times;</span>
                    </button>
                </div>
            @endif

                <div class="card-body">
                    <h3 class="card-title">Login</h3>
                    <div class="row">
                        <div class="col-md-6 d-flex flex-column">
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
                                        <a href="{{route('password.request')}}">Forgotten Password</a>
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
                            @if(isFacebookEnabled() || isGoogleEnabled())
                                <div>
                                    <hr>
                                    @if(isFacebookEnabled())
                                        <a class="btn btn-primary border-0 fb-button" style="background-color: #3b5998; border-radius: 2px" href="{{ url('/login/facebook') }}" role="button">
                                            <i class="fab fa-facebook-f"></i> Join with Facebook</a>
                                    @endif
                                    @if(isGoogleEnabled())
                                        <a class="btn btn-primary border-0 ml-2 g-button" style="background-color: #dd4b39; border-radius: 2px" href="{{ url('/login/google') }}" role="button">
                                            <i class="fab fa-google"></i> Join with Google</a>
                                    @endif
                                </div>
                            @endif
                            </div>
                            
                            <div class="mt-4 mx-auto d-none d-md-block" style="border-left:1px solid rgba(0,0,0,0.1); height: 220px;"></div>

                            <div class="col-md-5 d-flex flex-column">
                            
                                <h4 class="mb-3">
                                    Save money on your favourite brands
                                </h4>
                                <p><span class="mr-3" style="font-family: wingdings; font-size: 120%; color:green;">&#10004;</span>A few clicks to get cashback</p>
                                <p><span class="mr-3" style="font-family: wingdings; font-size: 120%; color:green;">&#10004;</span>Completely free</p>
                                <p><span class="mr-3" style="font-family: wingdings; font-size: 120%; color:green;">&#10004;</span>Get cashback and/or discount codes</p>
                                <p><span class="mr-3" style="font-family: wingdings; font-size: 120%; color:green;">&#10004;</span>Join the thousands of people who are saving money when they buy from 500+ popular brands</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection