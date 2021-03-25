@extends('layouts.auth.app')

@section('content')

<div class="nk-content ">
    <div class="nk-split nk-split-page nk-split-md">
        <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white w-lg-45">
            <div class="absolute-top-right d-lg-none p-3 p-sm-5">
                <a href="#" class="toggle btn btn-white btn-icon btn-light" data-target="athPromo"><em class="icon ni ni-info"></em></a>
            </div>
            <div class="nk-block nk-block-middle nk-auth-body">
                <div class="brand-logo pb-5">
                    <a href="html/index.html" class="logo-link">
                        <img class="logo-light logo-img logo-img-lg" src="{{ asset('admin-dashboard/images/logo.png')}}" srcset="./images/logo2x.png 2x" alt="logo">
                        <img class="logo-dark logo-img logo-img-lg" src="{{ asset('admin-dashboard/images/logo-dark.png')}}" srcset="./images/logo-dark2x.png 2x" alt="logo-dark">
                    </a>
                </div>
                <div class="nk-block-head">
                    <div class="nk-block-head-content">
                        <h5 class="nk-block-title">Register</h5>
                        <div class="nk-block-des">
                            <p>Create New Dashlite Account</p>
                        </div>
                    </div>
                </div><!-- .nk-block-head -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="firstame">First Name</label>
                        <input type="text" class="form-control form-control-lg" id="firstname" placeholder="Enter your first name" name="firstname" value="{{ old('firstname') }}" required autocomplete="firstname" autofocus>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="lastname">Last Name</label>
                        <input type="text" class="form-control form-control-lg" id="lastname" placeholder="Enter your last name" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname" autofocus>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="text" class="form-control form-control-lg" id="email" placeholder="Enter your email address" name="email" value="{{ old('email') }}" required autocomplete="email">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="form-control-wrap">
                            <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                            </a>
                            <input type="password" class="form-control form-control-lg" id="password" placeholder="Enter your password" name="password" required autocomplete="new-password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password">Confirm Password</label>
                        <div class="form-control-wrap">
                            <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                            </a>
                            <input type="password" class="form-control form-control-lg" id="password" placeholder="Re-enter your password" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-control-xs custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="checkbox">
                            <label class="custom-control-label" for="checkbox">I agree to Cashback <a tabindex="-1" href="#">Privacy Policy</a> &amp; <a tabindex="-1" href="#"> Terms.</a></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
                    </div>
                </form><!-- form -->
                <div class="form-note-s2 pt-4"> Already have an account ? <a href="html/pages/auths/auth-login.html"><strong>Sign in instead</strong></a>
                </div>
                <div class="text-center pt-4 pb-3">
                    <h6 class="overline-title overline-title-sap"><span>OR</span></h6>
                </div>
                <ul class="nav justify-center gx-8">
                    <li class="nav-item"><a class="nav-link" href="#">Facebook</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Google</a></li>
                </ul>
            </div><!-- .nk-block -->
            <div class="nk-block nk-auth-footer">
                <div class="nk-block-between">
                    <ul class="nav nav-sm">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Terms & Condition</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Privacy Policy</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Help</a>
                        </li>
                      
                    </ul><!-- nav -->
                </div>
                <div class="mt-3">
                    <p>&copy; 2021 Cashback. All Rights Reserved.</p>
                </div>
            </div><!-- nk-block -->
        </div><!-- nk-split-content -->
        <div class="nk-split-content nk-split-stretch bg-lighter d-flex toggle-break-lg toggle-slide toggle-slide-right" data-content="athPromo" data-toggle-screen="lg" data-toggle-overlay="true">
            <div class="slider-wrap w-100 w-max-550px p-3 p-sm-5 m-auto">
                <div class="slider-init" data-slick='{"dots":true, "arrows":false}'>
                    <div class="slider-item">
                        <div class="nk-feature nk-feature-center">
                            <div class="nk-feature-img">
                                <img class="round" src="{{ asset('admin-dashboard/images/slides/promo-a.png')}}" srcset="{{ asset('admin-dashboard/images/slides/promo-a2x.png')}} 2x" alt="">
                            </div>
                            <div class="nk-feature-content py-4 p-sm-5">
                                <h4>Cashback Reborn</h4>
                                <p>You can start to create your products easily with its user-friendly design & most completed responsive layout.</p>
                            </div>
                        </div>
                    </div><!-- .slider-item -->
                    <div class="slider-item">
                        <div class="nk-feature nk-feature-center">
                            <div class="nk-feature-img">
                                <img class="round" src="{{ asset('admin-dashboard/images/slides/promo-b.png')}}" srcset="{{ asset('admin-dashboard/images/slides/promo-b2x.png')}} 2x" alt="">
                            </div>
                            <div class="nk-feature-content py-4 p-sm-5">
                                <h4>Cashback Reborn</h4>
                                <p>You can start to create your products easily with its user-friendly design & most completed responsive layout.</p>
                            </div>
                        </div>
                    </div><!-- .slider-item -->
                    <div class="slider-item">
                        <div class="nk-feature nk-feature-center">
                            <div class="nk-feature-img">
                                <img class="round" src="{{ asset('admin-dashboard/images/slides/promo-c.png')}}" srcset="{{ asset('admin-dashboard/images/slides/promo-c2x.png')}} 2x" alt="">
                            </div>
                            <div class="nk-feature-content py-4 p-sm-5">
                                <h4>Cashback Reborn</h4>
                                <p>You can start to create your products easily with its user-friendly design & most completed responsive layout.</p>
                            </div>
                        </div>
                    </div><!-- .slider-item -->
                </div><!-- .slider-init -->
                <div class="slider-dots"></div>
                <div class="slider-arrows"></div>
            </div><!-- .slider-wrap -->
        </div><!-- nk-split-content -->
    </div><!-- nk-split -->
</div>
         {{--      
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
--}}
@endsection
