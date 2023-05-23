@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body wide-md mx-auto">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Mailer Settings</h3>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->

                @include('flash::message')
                <div class="nk-block">
                    <div class="card">
                        <div class="card-inner">
                            <h5 class="card-title">Mailer Setting</h5>
                            <p>Here are your mailer settings of your website.</p>
                            <form action="{{route(getAdminPrefix() . '.settings.mailer_settings_save')}}" class="gy-3 form-settings" method="POST">
                                @csrf
                                @method('POST')
                                <div class="row g-3 align-center">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label" for="site-name">Mail Driver</label>
                                            {{-- <span class="form-note">Specify the driver for mail.</span> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" name="driver" id="site-name" value="{{$settings['mail_driver']}}" placeholder="smtp">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 align-center">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label" for="host">Mail Host</label>
                                            {{-- <span class="form-note">Specify the email address of your website.</span> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="host" name="host" value="{{$settings['mail_host']}}" placeholder="smtp.mailtrap.io">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 align-center">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label" for="site-copyright">Port</label>
                                            {{-- <span class="form-note">Copyright information of your website.</span> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="port" name="port" value="{{$settings['mail_port']}}" placeholder="port">
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                
                                <div class="row g-3 align-center">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label">Username</label>
                                            {{-- <span class="form-note">Specify the URL if your main website is external.</span> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" name="username" value="{{$settings['mail_username']}}" placeholder="username">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 align-center">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label">Password</label>
                                            {{-- <span class="form-note">Specify the URL if your main website is external.</span> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" name="password" value="{{$settings['mail_password']}}" placeholder="password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 align-center">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label" for="site-copyright">From Email</label>
                                            {{-- <span class="form-note">Copyright information of your website.</span> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="email" name="email" value="{{$settings['mail_email'] ?? ''}}" placeholder="sender Email">
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row g-3 align-center">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label" for="site-copyright">From Name</label>
                                            {{-- <span class="form-note">Copyright information of your website.</span> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="name" name="name" value="{{$settings['mail_name'] ?? ''}}" placeholder="Sender Name">
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-lg-9 offset-lg-3">
                                        <div class="form-group mt-2">
                                            <button type="submit" class="btn btn-lg btn-primary">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div><!-- .card-inner -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
@endsection