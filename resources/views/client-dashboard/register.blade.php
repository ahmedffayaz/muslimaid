@extends('layouts.frontend.app')
@section('content')
<div class="block mt-5">
    <div class="container">
        <div class="col-md-10 d-flex flex-column mx-auto">
            <div class="card flex-grow-1 mb-md-0">
                <div class="card-body">
                    <h3 class="card-title">Register</h3>
                    <div class="row">
                        <div class="col-md-6 d-flex flex-column">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <input type="hidden" class="form-control form-control-lg" id="lastname" placeholder="Enter your last name" name="lastname" value="Doe">
                                <input type="hidden" class="form-control form-control-lg" id="firstname" placeholder="Enter your first name" name="firstname" value="john">

                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="password" required placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <label>Repeat Password</label>
                                    <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-primary mt-1">Register</button>
                            </form>
                            @if(isFacebookEnabled() || isGoogleEnabled())
                                <div>
                                    <hr>
                                    @if(isFacebookEnabled())
                                        <a class="btn btn-primary border-0" style="background-color: #3b5998; border-radius: 2px" href="{{ url('/login/facebook') }}" role="button">
                                            <i class="fab fa-facebook-f"></i> Join with Facebook</a>
                                    @endif
                                    @if(isGoogleEnabled())
                                        <a class="btn btn-primary border-0 ml-2" style="background-color: #dd4b39; border-radius: 2px" href="{{ url('/login/google') }}" role="button">
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
                                <p><span class="mr-3" style="font-family: wingdings; font-size: 120%; color:green;">&#10004;</span>Join the thousands of people who are saving money when they buy from 4,500+ popular brands</p>
                        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection