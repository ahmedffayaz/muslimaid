@extends('layouts.admin-dashboard.app')

@section('content')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="content-page wide-md m-auto">
                        <div class="nk-block-head nk-block-head-lg wide-sm">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title fw-normal">Email Templates</h3>

                            </div>
                        </div><!-- .nk-block-head -->

                        @foreach ($templates as $template)

                            <div class="nk-block border-bottom">
                                <h4 class="nk-block-title fw-normal mb-3">{{ $template->title }}</h4>
                                <p class="lead">{{ $template->detail }}</p>
                                <div class="card">


                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">Subject: {{ $template->subject }}</h6>
                                            </div>
                                            <div class="card-tools">
                                                <a href="{{ route('admin.email_templates.edit', $template) }}"
                                                    class="template-edit btn btn-primary"><em class="icon ni ni-edit mr-1"></em> Edit</a>
                                            </div>
                                        </div>
                                        {{-- <h4 class="title text-soft mb-4 overline-title"></h4> --}}
                                        <table class="email-wraper mt-4">
                                            <tr>
                                                <td class="py-5">
                                                    <table class="email-header">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center pb-4">
                                                                    <a href="#"><img class="email-logo" src="@if (isset($settings['website_logo'])
                                                                            && $settings['website_logo'] !='default.png'
                                                                            ) {{ asset('storage/dashboard/images/logo/' . $settings['website_logo']) }}@else{{ asset('admin-dashboard/images/logo.png') }} @endif" alt="logo"></a>

                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="email-body">
                                                        <tbody>
                                                            <tr>
                                                                <td class="p-3 p-sm-5">
                                                                    {!! $template->message !!}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="email-footer">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center pt-4">
                                                                    <p class="email-copyright-text">
                                                                        {{ SiteSetting()['footer_text'] }}</p>
                                                                    <ul class="email-social">
                                                                        @isset(SiteSetting()['facebook'])
                                                                            <li><a href="{{ SiteSetting()['facebook'] }}"><img
                                                                                        src="{{ asset('admin-dashboard/images/socials/facebook.png') }}"
                                                                                        alt=""></a></li>
                                                                        @endisset
                                                                        @isset(SiteSetting()['twitter'])
                                                                            <li><a href="{{ SiteSetting()['twitter'] }}"><img
                                                                                        src="{{ asset('admin-dashboard/images/socials/twitter.png') }}"
                                                                                        alt=""></a></li>
                                                                        @endisset
                                                                        @isset(SiteSetting()['instagram'])
                                                                            <li><a href="{{ SiteSetting()['instagram'] }}"><img
                                                                                        src="{{ asset('admin-dashboard/images/socials/instagram.png') }}"
                                                                                        alt=""></a></li>
                                                                        @endisset
                                                                        @isset(SiteSetting()['linkedin'])
                                                                            <li><a href="{{ SiteSetting()['linkedin'] }}"><img
                                                                                        src="{{ asset('admin-dashboard/images/socials/linkedin.png') }}"
                                                                                        alt=""></a></li>
                                                                        @endisset
                                                                        @isset(SiteSetting()['pinterest'])
                                                                            <li><a href="{{ SiteSetting()['pinterest'] }}"><img
                                                                                        src="{{ asset('admin-dashboard/images/socials/pinterest.png') }}"
                                                                                        alt=""></a></li>
                                                                        @endisset
                                                                    </ul>
                                                                    {{-- <p class="fs-12px pt-4">This email was sent to you as a
                                                                        registered member of <a
                                                                            href="{{ '/' }}">{{ SiteSetting()['website_title'] }}</a>.
                                                                    </p> --}}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div><!-- .nk-block -->
                            <hr class="border border-primary my-5 w-50">
                        @endforeach


                    </div><!-- .content-page -->
                </div>
            </div>
        </div>
    </div>
    <!-- @@ Edit Template Modal @e -->
    <div class="modal fade" tabindex="-1" role="dialog" id="edit-template-modal">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header align-center">
                    <div class="nk-file-title">


                        <div class="nk-file-name">
                            <div class="nk-file-name-text"><span class="title">Edit Template</span></div>
                            {{-- <div class="nk-file-name-sub">Project</div> --}}
                        </div>
                    </div>
                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                </div>
                <div id="edit-template" class=" p-4">


                </div>
            </div><!-- .modal-content -->
        </div><!-- .modla-dialog -->
    </div><!-- .modal -->

    <link rel="stylesheet" href="{{ asset('admin-dashboard//css/style-email.css') }}">
    <style>
        ul.email-social li .icon {
            font-size: 20px;
        }
    
        ul.email-social li a {
            padding: 7px
        }
    </style>
@endsection

@push('scripts')
    <link rel="stylesheet" href="{{ asset('admin-dashboard/css/editors/quill.css?ver=2.2.0') }}">
    <script src="{{ asset('admin-dashboard/js/libs/editors/quill.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('admin-dashboard/js/editors.js?ver=2.2.0') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.template-edit', function(event) {
                event.preventDefault();
                var pageurl = $(this).attr('href');
                //  pageurl = 'email_templates/'+id+'/edit';
                var _token = $("input[name=_token]").val();
                $.ajax({
                    url: pageurl,
                    method: "GET",
                    data: {
                        _token: _token
                    },
                    success: function(data) {
                        $('#edit-template-modal').modal('show');
                        $('#edit-template').html(data);
                        var rquill = new Quill('#teditor-container', {
                            modules: {
                                toolbar: [
                                    ['bold', 'italic'],
                                    ['link', 'blockquote', 'code-block', 'image'],
                                    [{
                                        list: 'ordered'
                                    }, {
                                        list: 'bullet'
                                    }]
                                ]
                            },
                            placeholder: 'Compose an epic...',
                            theme: 'snow'
                        });
                    }
                });

            });
        });

        // Cashback Update
        $(document).ready(function() {
            $(document).on('submit', '.template_form', function(event) {

                event.preventDefault();
                var editor = document.querySelector('#teditor-container')
                var desc = document.querySelector('input[name=message]');
                desc.value = editor.children[0].innerHTML
                console.log(desc.value);

                $.ajax({
                    url: $(this).attr('action'),
                    type: "PUT",
                    data: $(this).serialize(),
                    success: function(data) {
                        $('#edit-template-modal').modal('hide');
                        (function(NioApp, $) {
                            'use strict';
                            toastr.clear();
                            NioApp.Toast(data['message'], data['status']);
                            location.reload(true);



                        })(NioApp, jQuery);

                    }
                });
            });
        });

    </script>
@endpush
