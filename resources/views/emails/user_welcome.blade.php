<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
  
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('admin-dashboard/css/dashlite.css?ver=2.2.0')}}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('admin-dashboard/css/theme.css?ver=2.2.0')}}">
    <link rel="stylesheet" href="{{asset('vendor/laraberg/css/laraberg.css')}}">
    <script src="https://unpkg.com/react@16.8.6/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@16.8.6/umd/react-dom.production.min.js"></script>
    <script src="{{ asset('vendor/laraberg/js/laraberg.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    
</head>
<style>
    /*! Email Template */
.email-wraper { background: #f5f6fa; font-size: 16px; line-height: 22px; font-weight: 400; color: #8094ae; width: 100%; }

.email-wraper a { color: #854fff; word-break: break-all; }

.email-wraper .link-block { display: block; }

.email-ul { margin: 5px 0; padding: 0; }

.email-ul:not(:last-child) { margin-bottom: 10px; }

.email-ul li { list-style: disc; list-style-position: inside; }

.email-ul-col2 { display: flex; flex-wrap: wrap; }

.email-ul-col2 li { width: 50%; padding-right: 10px; }

.email-body { width: 96%; max-width: 620px; margin: 0 auto; background: #ffffff; }

.email-success { border-bottom: #1ee0ac; }

.email-warning { border-bottom: #f4bd0e; }

.email-btn { background: #854fff; border-radius: 4px; color: #ffffff !important; display: inline-block; font-size: 13px; font-weight: 600; line-height: 44px; text-align: center; text-decoration: none; text-transform: uppercase; padding: 0 30px; }

.email-btn-sm { line-height: 38px; }

.email-header, .email-footer { width: 100%; max-width: 620px; margin: 0 auto; }

.email-logo { height: 40px; }

.email-title { font-size: 13px; color: #854fff; padding-top: 12px; }

.email-heading { font-size: 18px; color: #854fff; font-weight: 600; margin: 0; line-height: 1.4; }

.email-heading-sm { font-size: 24px; line-height: 1.4; margin-bottom: .75rem; }

.email-heading-s1 { font-size: 20px; font-weight: 400; color: #526484; }

.email-heading-s2 { font-size: 16px; color: #526484; font-weight: 600; margin: 0; text-transform: uppercase; margin-bottom: 10px; }

.email-heading-s3 { font-size: 18px; color: #854fff; font-weight: 400; margin-bottom: 8px; }

.email-heading-success { color: #1ee0ac; }

.email-heading-warning { color: #f4bd0e; }

.email-note { margin: 0; font-size: 13px; line-height: 22px; color: #8094ae; }

.email-copyright-text { font-size: 13px; }

.email-social li { display: inline-block; padding: 4px; }

.email-social li a { display: inline-block; height: 30px; width: 30px; border-radius: 50%; background: #ffffff; }

.email-social li a img { width: 30px; }

@media (max-width: 480px) { .email-preview-page .card { border-radius: 0; margin-left: -20px; margin-right: -20px; }
  .email-ul-col2 li { width: 100%; } }
  
  
.p-3 {
    padding: 1rem !important;
}
@media (min-width: 576px){
    .p-sm-5 {
    padding: 2.75rem !important;
}}
.text-center {
    text-align: center !important;
}
.pb-4, .py-4 {
    padding-bottom: 1.5rem !important;
}
.pb-5, .py-5 {
    padding-bottom: 2.75rem !important;
}
.pt-5, .py-5 {
    padding-top: 2.75rem !important;
}
ol, ul {
    list-style: none;
    margin: 0;
    padding: 0;
}
.pt-4, .py-4 {
    padding-top: 1.5rem !important;
}
.fs-12px {
    font-size: 12px;
}
body {
    margin: 0;
    font-family: "DM Sans", sans-serif, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1.65;
    color: #526484;
    text-align: left;
    background-color: #f5f6fa;
}
ul.email-social li .icon {font-size: 20px;}
ul.email-social li a {padding:inherit}
</style>
<body>
<table class="email-wraper">
    <tr>
        <td class="py-5">
            <table class="email-header">
                <tbody>
                    <tr>
                        <td class="text-center pb-4">
                            <a href="{{url('/')}}"><img class="email-logo" src="@if(isset($settings['website_logo']) && $settings['website_logo']!='default.png'){{asset('storage/dashboard/images/logo/'.$settings['website_logo'])}}@else{{asset('admin-dashboard/images/logo.png')}}@endif" alt="logo"></a>
                           
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="email-body">
                <tbody>
                    <tr>
                        <td class="p-3 p-sm-5">
                            <p><strong>Hello</strong>,</p>
                            <p>Let's face it, sometimes you have a simple message that doesn’t need much design—but still needs flexibility and reliability. Select a basic email template. Write your message. Then send with confidence.</p>
                            <p>Its clean, minimal and pre-designed email template that is suitable for multiple purposes email template.</p>
                            <p>Hope you'll enjoy the experience, we're here if you have any questions, drop us a line at info@yourwebsite.com anytime. </p>
                            <p class="mt-4">---- <br> Regards<br>Abu Bin Ishtiyak</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="email-footer">
                <tbody>
                    <tr>
                        <td class="text-center pt-4">
                            <p class="email-copyright-text">{{SiteSetting()['footer_text']}}</p>
                            <ul class="email-social">
                                @isset(SiteSetting()['facebook'])
                                <li><a href="{{SiteSetting()['facebook']}}"><em class="icon ni ni-facebook-f"></em></a></li>
                                @endisset
                                @isset(SiteSetting()['twitter'])
                                <li><a href="{{SiteSetting()['twitter']}}"><em class="icon ni ni-twitter"></em></a></li>
                                @endisset
                                @isset(SiteSetting()['instagram'])
                                <li><a href="{{SiteSetting()['instagram']}}"><em class="icon ni ni-instagram"></em></a></li>
                                @endisset
                                @isset(SiteSetting()['linkedin'])
                                <li><a href="{{SiteSetting()['linkedin']}}"><em class="icon ni ni-linkedin"></em></a></li>
                                @endisset
                                @isset(SiteSetting()['pinterest'])
                                <li><a href="{{SiteSetting()['pinterest']}}"><em class="icon ni ni-pinterest"></em></a></li>
                                @endisset
                            </ul>
                            <p class="fs-12px">This email was sent to you as a registered member of <a href="{{'/'}}">{{SiteSetting()['website_title']}}</a>.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

