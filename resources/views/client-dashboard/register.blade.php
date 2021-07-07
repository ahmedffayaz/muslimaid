@extends('layouts.frontend.app')
@section('content')
<div class="block mt-5">
    <div class="container">
        <div class="col-md-9 d-flex flex-column mx-auto">
            <div class="card flex-grow-1 mb-md-0">
                <div class="card-body">
                    <h3 class="card-title">Register</h3>
                    <div class="row">
                        <div class="col-md-7 d-flex flex-column">
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
                                <button type="submit" class="btn btn-primary mt-4">Register</button>
                            </form>
                        </div>
                        <div class="ml-3" style="border-left:1px solid rgba(0,0,0,0.1)">

                        </div>
                        <div class="col-md-4 d-flex flex-column">
                            <a class="btn btn-primary border-0 ml-4 mt-4" style="background-color: #3b5998; border-radius: 2px; font-size: 14px" href="{{ url('/login/facebook') }}" role="button">
                                <i class="fab fa-facebook-f"> Continue with Facebook</i></a>
                            <br>
                            <a class="btn btn-primary border-0 ml-4 mt-3" style="background-color: #dd4b39; border-radius: 2px; font-size: 14px" href="{{ url('/login/google') }}" role="button">
                                <i class="fab fa-google"></i> Continue with Google</a>
                            <br>
                            <a class="btn btn-primary border-0 ml-4 mt-3" style="background-color: black; border-radius: 2px; font-size: 14px" href="#" role="button">
                                <i class="fab fa-apple"></i> Continue with Apple</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection