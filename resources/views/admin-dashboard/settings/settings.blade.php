@extends('layouts.admin-dashboard.app')
<style>
    .nk-tb-list{
        table-layout: fixed;
    }
    .nk-files-view-grid .nk-file-icon-type {
    width: 150px;
    padding: 2rem 0 .5rem 0;
}
@media (min-width: 1200px){
    .nk-files-view-grid .nk-file {
    width: calc(25% - 16px)!important;
}
}
.nk-files-view-grid .nk-file {
    background-color:#f5f6fa7a!important;
}
.stores .select2{
    width: 300px!important;
}
ul.categories { 
  list-style: none;
  margin: 5px 5x;
}
ul.categories li {
  margin: 10px 0;
} 
.custom-size .colorpicker-saturation { 
    width: 200px;
    height: 200px;
}    
.custom-size .colorpicker-hue, .custom-size .colorpicker-alpha {
    width: 30px;
    height: 200px;
}
.custom-size .colorpicker-color, .custom-size .colorpicker-color div {
    height: 30px;
}
     
</style>

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block-head nk-block-head-lg pb-2">
                        <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title fw-normal">Settings</h3>
                            <div class="nk-block-des">
                              
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                           
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        {{-- <li class="nk-block-tools-opt"><a href="{{route('admin.stores.edit',$store)}}" class="btn btn-primary btn-sm"><em class="icon ni ni-edit"></em><span>Edit Store</span></a></li> --}}
                                    
                                        {{-- <li><a href="{{route('admin.stores.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li> --}}
                                      
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div>
                    </div>
                   
                @include('flash::message')
                    
                    
                    <div class="nk-block nk-block-lg">

                        
                        <div class="card card-preview">
                            <div class="card-inner">
                               
                                <ul class="nav nav-tabs mt-n3">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tabItem4"><em class="icon ni ni-setting-fill"></em><span>General</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem5"><em class="icon ni ni-dashboard-fill"></em><span>Website</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem9"><em class="icon ni ni-coins"></em><span>Cashback</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem6"><em class="icon ni ni-emails-fill"></em><span>Mailer</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem7"><em class="icon ni ni-share-fill"></em><span>Social</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem8"><em class="icon ni ni-code"></em><span>Integration</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem10"><em class="icon ni ni-color-palette-fill"></em><span>Appearance</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem11"><em class="icon ni ni-lock-fill"></em><span>Login API's</span></a>
                                    </li>
                                    
                                </ul>
                                <div class="tab-content">
                                   
                                    <div class="tab-pane active" id="tabItem4">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                                <h5 class="title">General Settings</h5>
                                                {{-- <p>Basic info, like your name and address, that you use on Nio Platform.</p> --}}
                                            </div><!-- .nk-block-head -->
                                            <form action="{{route('admin.settings.settings_save')}}" class="gy-3 form-settings" method="POST">
                                                @csrf
                                                @method('POST')
                                                
                                               
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="email">Email</label>
                                                            {{-- <span class="form-note">Copyright information of your website.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="email" name="email" value="{{$settings['email'] ?? ''}}" placeholder="Email">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="phone_number">Phone Number</label>
                                                            {{-- <span class="form-note">Copyright information of your website.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{$settings['phone_number'] ?? ''}}" placeholder="Sender Name">
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
                                        </div>
                                      
                                    </div>
                                    <div class="tab-pane" id="tabItem5">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                                <h5 class="title">Website Settings</h5>
                                                {{-- <p>Basic info, like your name and address, that you use on Nio Platform.</p> --}}
                                            </div><!-- .nk-block-head -->
                                            <form action="{{route('admin.settings.settings_save')}}" class="gy-3 form-settings" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="website_title">Website Logo</label>
                                                            {{-- <span class="form-note">Specify the driver for mail.</span> --}}
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class=" logo">
                                                                <label for="website-logo-input">
                                                                <img id="website-logo" src="@if(isset($settings['website_logo']) && $settings['website_logo']!='default.png'){{asset('storage/dashboard/images/logo/'.$settings['website_logo'])}}@else{{asset('admin-dashboard/images/cloud-uploading.png')}}@endif" alt="store logo" class="" style="max-width:100px;max-height:120px"/>
                                                                <input id="website-logo-input" preview="#website-logo" name="website_logo" class="d-none" type='file' onchange="readURL(this);" />
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="dashboard_title">Dashboard Logo</label>
                                                            {{-- <span class="form-note">Specify the driver for mail.</span> --}}
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class=" logo">
                                                                <label for="logo-input">
                                                                <img id="logo" src="@if(isset($settings['dashboard_logo']) && $settings['dashboard_logo']!='default.png'){{asset('storage/dashboard/images/logo/'.$settings['dashboard_logo'])}}@else{{asset('admin-dashboard/images/cloud-uploading.png')}}@endif" alt="store logo" class="" style="max-width:100px;max-height:120px"/>
                                                                <input id="logo-input" preview="#logo" name="dashboard_logo" class="d-none" type='file' onchange="readURL(this);" />
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="favicon">Favicon</label>
                                                            {{-- <span class="form-note">Specify the driver for mail.</span> --}}
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class=" logo">
                                                                <label for="favicon-input">
                                                                <img id="favicon" src="@if(isset($settings['favicon']) && $settings['favicon']!='default.png'){{asset('storage/dashboard/images/logo/'.$settings['favicon'])}}@else{{asset('admin-dashboard/images/cloud-uploading.png')}}@endif" alt="store logo" class="" style="max-width:100px;max-height:120px"/>
                                                                <input id="favicon-input" preview="#favicon"  name="favicon" class="d-none" type='file' onchange="readURL(this);" />
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="website_title">Website Title</label>
                                                            {{-- <span class="form-note">Specify the driver for mail.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" name="website_title" id="website_title" value="{{$settings['website_title'] ?? ''}}" placeholder="Title">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                               
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="footer_text">Footer text</label>
                                                            {{-- <span class="form-note">Copyright information of your website.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="footer_text" name="footer_text" value="{{$settings['footer_text'] ?? ''}}" placeholder="Email">
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
                                        </div>
                                      
                                    </div>
                                    <div class="tab-pane" id="tabItem6">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                                <h5 class="title">Mailer Settings</h5>
                                                {{-- <p>Basic info, like your name and address, that you use on Nio Platform.</p> --}}
                                            </div><!-- .nk-block-head -->
                                            <form action="{{route('admin.settings.settings_save')}}" class="gy-3 form-settings" method="POST">
                                                @csrf
                                                @method('POST')
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="mail_driver">Mail Driver</label>
                                                            {{-- <span class="form-note">Specify the driver for mail.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" name="mail_driver" id="mail_driver" value="{{$settings['mail_driver']}}" placeholder="smtp">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="mail_host">Mail Host</label>
                                                            {{-- <span class="form-note">Specify the email address of your website.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="mail_host" name="mail_host" value="{{$settings['mail_host']}}" placeholder="smtp.mailtrap.io">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="mail_port">Port</label>
                                                            {{-- <span class="form-note">Copyright information of your website.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="mail_port" name="mail_port" value="{{$settings['mail_port']}}" placeholder="port">
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
                                                                <input type="text" class="form-control" name="mail_username" value="{{$settings['mail_username']}}" placeholder="username">
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
                                                                <input type="text" class="form-control" name="mail_password" value="{{$settings['mail_password']}}" placeholder="password">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="mail_email">From Email</label>
                                                            {{-- <span class="form-note">Copyright information of your website.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="mail_email" name="mail_email" value="{{$settings['mail_email'] ?? ''}}" placeholder="sender Email">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="mail_name">From Name</label>
                                                            {{-- <span class="form-note">Copyright information of your website.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="mail_name" name="mail_name" value="{{$settings['mail_name'] ?? ''}}" placeholder="Sender Name">
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
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tabItem7">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                                <h5 class="title">Social Media Settings</h5>
                                                {{-- <p>Basic info, like your name and address, that you use on Nio Platform.</p> --}}
                                            </div><!-- .nk-block-head -->
                                            <form action="{{route('admin.settings.settings_save')}}" class="gy-3 form-settings" method="POST">
                                                @csrf
                                                @method('POST')
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="facebook">Facebook</label>
                                                            {{-- <span class="form-note">Specify the driver for mail.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" name="facebook" id="facebook" value="{{$settings['facebook'] ?? ''}}" placeholder="Facebook Page link">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="twitter">Twitter</label>
                                                            {{-- <span class="form-note">Specify the email address of your website.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="twitter" name="twitter" value="{{$settings['twitter'] ?? ''}}" placeholder="Twitter handle link">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="instagram">Instagram</label>
                                                            {{-- <span class="form-note">Copyright information of your website.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="instagram" name="instagram" value="{{$settings['instagram'] ?? ''}}" placeholder="Instagram profile link">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="linkedin">LinkedIn</label>
                                                            {{-- <span class="form-note">Copyright information of your website.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="linkedin" name="linkedin" value="{{$settings['linkedin'] ?? ''}}" placeholder="LinkedIn profile link">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Pinterest</label>
                                                            {{-- <span class="form-note">Specify the URL if your main website is external.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" name="pinterest" value="{{$settings['pinterest'] ?? ''}}" placeholder="Pinterest Link">
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
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tabItem8">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                                <h5 class="title">Scripts Integration</h5>
                                                {{-- <p>Basic info, like your name and address, that you use on Nio Platform.</p> --}}
                                            </div><!-- .nk-block-head -->
                                            <form action="{{route('admin.settings.settings_save')}}" class="gy-3 form-settings" method="POST">
                                                @csrf
                                                @method('POST')
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="ga_tracking_id">Google Analytics Tracking ID</label>
                                                            {{-- <span class="form-note">Specify the driver for mail.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" name="ga_tracking_id" id="ga_tracking_id" value="{{$settings['ga_tracking_id'] ?? ''}}" placeholder="UA-1XXXXXXXX-X">
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
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tabItem9">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                                <h5 class="title">Cashback Settings</h5>
                                                {{-- <p>Basic info, like your name and address, that you use on Nio Platform.</p> --}}
                                            </div><!-- .nk-block-head -->
                                            <form action="{{route('admin.settings.settings_save')}}" class="gy-3 form-settings" method="POST">
                                                @csrf
                                                @method('POST')
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="currency">Currency</label>
                                                            {{-- <span class="form-note">Copyright information of your website.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="">
                                                                <div class="">
                                                                    <select class="form-control form-select" id="default-06" name="currency" required>
                                                                        @foreach ($currencies as $currency)
                                                                            <option @if($settings['currency'] == $currency->id) selected @endif value="{{$currency->id}}">{{$currency->short_name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="cashback_percentage">Cashback Percentage</label>
                                                            {{-- <span class="form-note">Copyright information of your website.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="cashback_percentage" name="cashback_percentage" value="{{$settings['cashback_percentage'] ?? ''}}" placeholder="Cashback Percentage">
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
                                        </div>
                                      
                                    </div>
                                    <div class="tab-pane" id="tabItem10">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                                <h5 class="title">Appearance Settings</h5>
                                                {{-- <p>Basic info, like your name and address, that you use on Nio Platform.</p> --}}
                                            </div><!-- .nk-block-head -->
                                            <form action="{{route('admin.settings.settings_save')}}" class="gy-3 form-settings" method="POST">
                                                @csrf
                                                @method('POST')
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="theme_skin">Website Theme</label>
                                                            {{-- <span class="form-note">Copyright information of your website.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="">
                                                                <div class="">
                                                                    <select class="form-control form-select" id="theme_skin" name="theme_skin" required>
                                                                        
                                                                        <option @isset($settings['theme_skin']) @if($settings['theme_skin'] == 'blue') selected  @endif @endisset value="blue">Blue</option>
                                                                        <option @isset($settings['theme_skin']) @if($settings['theme_skin'] == 'black') selected @endif @endisset value="black">Black</option>
                                                                        <option @isset($settings['theme_skin']) @if($settings['theme_skin'] == 'green') selected @endif @endisset value="green">Green</option>
                                                                        <option @isset($settings['theme_skin']) @if($settings['theme_skin'] == 'red') selected @endif @endisset value="red">Red</option>
                                                                        <option @isset($settings['theme_skin']) @if($settings['theme_skin'] == 'yellow') selected @endif @endisset value="yellow">Yellow</option>
                                                                        <option @isset($settings['theme_skin']) @if($settings['theme_skin'] == 'custom') selected @endif @endisset value="custom">Custom</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                       
                                                    </div>
                                                    
                                                </div>
                                                <div class="row g-3 custom_color" @isset($settings['theme_skin']) @if($settings['theme_skin'] != 'custom') style="display: none" @endif @endisset>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="theme_color">Theme Color</label>
                                                            {{-- <span class="form-note">Specify the driver for mail.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control colorpicker" name="theme_color" id="theme_color" value="{{$settings['theme_color'] ?? ''}}" placeholder="Theme Color">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3 custom_color">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="theme_color">Dashbaord Menu Type</label>
                                                            {{-- <span class="form-note">Specify the driver for mail.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class=" ">
                                                                <div class="">
                                                                    <select class="form-control form-select" id="dashboard_menu_type" name="dashboard_menu_type" required>
                                                                        
                                                                        <option @isset($settings['dashboard_menu_type']) @if($settings['dashboard_menu_type'] == 'top') selected  @endif @endisset value="top">Top Menu</option>
                                                                        <option @isset($settings['dashboard_menu_type']) @if($settings['dashboard_menu_type'] == 'sidebar') selected  @endif @endisset value="sidebar">Sidebar Menu</option>
                                                                        
                                                                    </select>
                                                                </div>
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
                                        </div>
                                      
                                    </div>
                                    <div class="tab-pane" id="tabItem11">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                                <h5 class="title">Facebook</h5>
                                                {{-- <p>Basic info, like your name and address, that you use on Nio Platform.</p> --}}
                                            </div><!-- .nk-block-head -->
                                            <form action="{{route('admin.settings.settings_save')}}" class="gy-3 form-settings" method="POST">
                                                @csrf
                                                @method('POST')
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="facebook_client_id">Facebook Client ID</label>
                                                            {{-- <span class="form-note">Specify the facebook client id.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" name="facebook_client_id" id="facebook_client_id" value="{{$settings['facebook_client_id'] ?? ''}}" placeholder="Facebook Client ID">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="facebook_client_secret">Facebook Client Secret</label>
                                                            {{-- <span class="form-note">Specify the facebook client secret.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" secret="facebook_client_secret" name="facebook_client_secret" value="{{$settings['facebook_client_secret'] ?? ''}}" placeholder="Facebook Client Secret">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <div class="nk-block-head">
                                                <h5 class="title">Google</h5>
                                                {{-- <p>Basic info, like your name and address, that you use on Nio Platform.</p> --}}
                                            </div><!-- .nk-block-head -->
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="google_client_id">Google Client ID</label>
                                                            {{-- <span class="form-note">Specify the google client id.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="google_client_id" name="google_client_id" value="{{$settings['google_client_id'] ?? ''}}" placeholder="Google Client ID">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="google_client_secret">Google Client Secret</label>
                                                            {{-- <span class="form-note">Specify the google client secret.</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="google_client_secret" name="google_client_secret" value="{{$settings['google_client_secret'] ?? ''}}" placeholder="Google Client Secret">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- .card-preview -->
                      
                    </div>
                  
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
@push('scripts')

<!-- Update Store-->
<script>

$(document).ready( function() {
    $(document).on('submit', '.form-settings', function(event){

        event.preventDefault();  
        var formData = new FormData(this);  
        $.ajax({
        type:'POST',
        url: $(this).attr('action'),
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success:function(data){
            
            (function(NioApp, $){
                'use strict';
                toastr.clear();
                NioApp.Toast(data.message, data.response);
            })(NioApp, jQuery); 
            location.reload(true);
            
        },
        error: function(data){

            (function(NioApp, $){
                'use strict';
                toastr.clear();
                NioApp.Toast(data.message, data.response);
            })(NioApp, jQuery); 
            
        }
    });

    });
});
</script>
<script>
    function readURL(input) {
  if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
          
          console.log(input);
          var preview = $(input).attr('preview');
          console.log(preview);
          $(preview)
              .attr('src', e.target.result)
              .css('max-width',150).css('max-height',120);
      };

      reader.readAsDataURL(input.files[0]);
  }
}

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
<script>$('.colorpicker').colorpicker({
     
customClass: 'custom-size',
 
 sliders: {
  
 saturation: {
  
 maxLeft: 200,
  
 maxTop: 200
  
 },
  
 hue: {
  
 maxTop: 200
  
 },
  
 alpha: {
  
 maxTop: 200
  
 }
  
 }
  
});</script>

<script>

function checkThemeType(){
    
    if ($('#theme_skin').val() == 'custom') {    
        $('.custom_color').show();      
        $('#theme_color').attr('required', 'required');
    }
    else{
        $('.custom_color').hide();      
        $('#theme_color').removeAttr('required').val('');
    }

}
$(document.body).on("change","#theme_skin",function(){
    checkThemeType();
});
</script>

@endpush