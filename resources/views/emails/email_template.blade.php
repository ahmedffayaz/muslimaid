<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">

    <!-- StyleSheets  -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">

</head>
<style>
    /*! Email Template */
    .email-wraper {
        background: #f5f6fa;
        font-size: 16px;
        line-height: 22px;
        font-weight: 400;
        color: #8094ae;
        width: 100%;
    }

    .email-wraper a {
        color: #854fff;
        word-break: break-all;
    }

    .email-wraper .link-block {
        display: block;
    }

    .email-ul {
        margin: 5px 0;
        padding: 0;
    }

    .email-ul:not(:last-child) {
        margin-bottom: 10px;
    }

    .email-ul li {
        list-style: disc;
        list-style-position: inside;
    }

    .email-ul-col2 {
        display: flex;
        flex-wrap: wrap;
    }

    .email-ul-col2 li {
        width: 50%;
        padding-right: 10px;
    }

    .email-body {
        width: 96%;
        max-width: 620px;
        margin: 0 auto;
        background: #ffffff;
    }

    .email-success {
        border-bottom: #1ee0ac;
    }

    .email-warning {
        border-bottom: #f4bd0e;
    }

    .email-btn {
        background: #854fff;
        border-radius: 4px;
        color: #ffffff !important;
        display: inline-block;
        font-size: 13px;
        font-weight: 600;
        line-height: 44px;
        text-align: center;
        text-decoration: none;
        text-transform: uppercase;
        padding: 0 30px;
    }

    .email-btn-sm {
        line-height: 38px;
    }

    .email-header,
    .email-footer {
        width: 100%;
        max-width: 620px;
        margin: 0 auto;
    }

    .email-logo {
        height: 40px;
    }

    .email-title {
        font-size: 13px;
        color: #854fff;
        padding-top: 12px;
    }

    .email-heading {
        font-size: 18px;
        color: #854fff;
        font-weight: 600;
        margin: 0;
        line-height: 1.4;
    }

    .email-heading-sm {
        font-size: 24px;
        line-height: 1.4;
        margin-bottom: .75rem;
    }

    .email-heading-s1 {
        font-size: 20px;
        font-weight: 400;
        color: #526484;
    }

    .email-heading-s2 {
        font-size: 16px;
        color: #526484;
        font-weight: 600;
        margin: 0;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .email-heading-s3 {
        font-size: 18px;
        color: #854fff;
        font-weight: 400;
        margin-bottom: 8px;
    }

    .email-heading-success {
        color: #1ee0ac;
    }

    .email-heading-warning {
        color: #f4bd0e;
    }

    .email-note {
        margin: 0;
        font-size: 13px;
        line-height: 22px;
        color: #8094ae;
    }

    .email-copyright-text {
        font-size: 13px;
    }

    .email-social li {
        display: inline-block;
        padding: 4px;
    }

    .email-social li a {
        display: inline-block;
        height: 30px;
        width: 30px;
        border-radius: 50%;
        background: #ffffff;
    }

    .email-social li a img {
        width: 30px;
    }

    @media (max-width: 480px) {
        .email-preview-page .card {
            border-radius: 0;
            margin-left: -20px;
            margin-right: -20px;
        }

        .email-ul-col2 li {
            width: 100%;
        }
    }

    .p-3 {
        padding: 1rem !important;
    }

    @media (min-width: 576px) {
        .p-sm-5 {
            padding: 2.75rem !important;
        }
    }

    .text-center {
        text-align: center !important;
    }

    .pb-4,
    .py-4 {
        padding-bottom: 1.5rem !important;
    }

    .pb-5,
    .py-5 {
        padding-bottom: 2.75rem !important;
    }

    .pt-5,
    .py-5 {
        padding-top: 2.75rem !important;
    }

    ol,
    ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .pt-4,
    .py-4 {
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

    ul.email-social li .icon {
        font-size: 20px;
    }

    ul.email-social li a {
        padding: 7px
    }

    .btn {
        display: inline-block;
        font-family: "DM Sans", sans-serif;
        font-weight: 700;
        color: #526484;
        text-align: center;
        vertical-align: middle;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.4375rem 1.125rem;
        font-size: 0.8125rem;
        line-height: 1.25rem;
        border-radius: 4px;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    @media (prefers-reduced-motion: reduce) {
        .btn {
            transition: none;
        }
    }

    .btn:hover {
        color: #526484;
        text-decoration: none;
    }

    .btn:focus,
    .btn.focus {
        outline: 0;
        box-shadow: 0 0 0 3px rgba(133, 79, 255, 0.1);
    }

    .btn.disabled,
    .btn:disabled {
        opacity: 0.5;
    }

    .btn:not(:disabled):not(.disabled) {
        cursor: pointer;
    }

    .btn-success {
        color: #fff;
        background-color: #1ee0ac;
        border-color: #1ee0ac;
    }

    .btn-success:hover {
        color: #fff;
        background-color: #19be92;
        border-color: #18b389;
    }

    .btn-success:focus,
    .btn-success.focus {
        color: #fff;
        background-color: #19be92;
        border-color: #18b389;
        box-shadow: 0 0 0 0.2rem rgba(64, 229, 184, 0.5);
    }

    .btn-success.disabled,
    .btn-success:disabled {
        color: #fff;
        background-color: #1ee0ac;
        border-color: #1ee0ac;
    }

    .btn-success:not(:disabled):not(.disabled):active,
    .btn-success:not(:disabled):not(.disabled).active,
    .show>.btn-success.dropdown-toggle {
        color: #fff;
        background-color: #18b389;
        border-color: #16a881;
    }

    .btn-success:not(:disabled):not(.disabled):active:focus,
    .btn-success:not(:disabled):not(.disabled).active:focus,
    .show>.btn-success.dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(64, 229, 184, 0.5);
    }
</style>

<body>
    <table class="email-wraper">
        <tr>
            <td class="py-5">
                <table class="email-header">
                    <tbody>
                        <tr>
                            <td class="text-center pb-4">
                                <a href="{{ url('/') }}">
                                    <img class="email-logo"
                                        src="@if (isset($settings['website_logo']) && $settings['website_logo'] != 'default.png') {{ asset('storage/dashboard/images/logo/' . $settings['website_logo']) }}@else{{ asset('admin-dashboard/images/logo.png') }} @endif"
                                        alt="logo">
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="email-body">
                    <tbody>
                        <tr>
                            <td class="p-3 p-sm-5">
                                {!! $email_message !!}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="email-footer">
                    <tbody>
                        <tr>
                            <td class="text-center pt-4">
                                <p class="email-copyright-text">{{ SiteSetting()['footer_text'] }}</p>
                                <ul class="email-social">
                                    @isset(SiteSetting()['facebook'])
                                        <li><a href="{{ SiteSetting()['facebook'] }}"><img src="{{ asset('admin-dashboard/images/socials/facebook.png') }}" alt=""></a>
                                        </li>
                                    @endisset
                                    @isset(SiteSetting()['twitter'])
                                        <li><a href="{{ SiteSetting()['twitter'] }}"><img src="{{ asset('admin-dashboard/images/socials/twitter.png') }}" alt=""></a>
                                        </li>
                                    @endisset
                                    @isset(SiteSetting()['instagram'])
                                        <li><a href="{{ SiteSetting()['instagram'] }}"><img src="{{ asset('admin-dashboard/images/socials/instagram.png') }}" alt=""></a>
                                        </li>
                                    @endisset
                                    @isset(SiteSetting()['linkedin'])
                                        <li><a href="{{ SiteSetting()['linkedin'] }}"><img src="{{ asset('admin-dashboard/images/socials/linkedin.png') }}" alt=""></a>
                                        </li>
                                    @endisset
                                    @isset(SiteSetting()['pinterest'])
                                        <li><a href="{{ SiteSetting()['pinterest'] }}"><img src="{{ asset('admin-dashboard/images/socials/pinterest.png') }}" alt=""></a>
                                        </li>
                                    @endisset
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
