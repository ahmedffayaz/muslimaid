@extends('layouts.frontend.app')
@section('content')
    <div class="block mt-3">
        <div class="page-header">
            <div class="page-header__container container">
                <div class="page-header__breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/') }}">Home</a>
                                <svg class="breadcrumb-arrow" width="6px" height="9px">
                                    <use xlink:href="{{ asset('frontend/images/sprite.svg') }}#arrow-rounded-right-6x9">
                                    </use>
                                </svg>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Refer a friend</li>
                        </ol>
                    </nav>
                </div>
                <div class="page-header__title">
                    <h3>Invite your friends and earn £{{ $referralBonus }} referral bonus</h3>
                </div>
            </div>
        </div>
        <div class="container">
            @include('flash::message')
            @include('layouts.frontend.includes.alert')
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="block">
                        <div class="posts-view">
                            <div class="posts-view__list posts-list posts-list--layout--list">
                                <div class="posts-list__body">
                                    <div class="posts-list__item">
                                        <form action="{{ route('account.send-referral-link') }}" method="post">
                                            @csrf
                                            <div class="row g-3 align-center">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="cashback_percentage">Invite Your
                                                            Friends<em class="icon ni ni-question form-label"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title=" Enter cashback percentage which will be given to user."></em></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9">
                                                    <div class="form-group">
                                                        <div class="form-control-wrap">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control"
                                                                    name="referral_email" id="referral_email" value=""
                                                                    placeholder="Enter email">
                                                                <button
                                                                    class="input-group-btn btn btn-primary go inline">Send</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </form>
                                    </div>
                                    <div class="row g-3 align-center">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="form-label" for="cashback_percentage">Share Your Link<em
                                                        class="icon ni ni-question form-label" data-toggle="tooltip"
                                                        data-placement="top"
                                                        title=" Enter cashback percentage which will be given to user."></em></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="ref_link"
                                                        value="{{ url('/register-form?referby=' . base64_encode($user = \Auth::user()->id)) }}"
                                                        readonly>
                                                    <button class="input-group-btn btn btn-primary go inline"
                                                        onclick="copyText()">Copy</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        function copyText() {

            // Get the text field
            var copyText = document.getElementById("ref_link");
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            navigator.clipboard.writeText(copyText.value);

            if (navigator.clipboard.writeText.length == 1) {
                $('#alert-message').removeClass('d-none');
                $('#alert-message').text('Referral link copied');
                $('#alert-message').addClass('alert-success');
                $('div.alert').not('.alert-important').delay(2000).fadeOut(350);
            }
        }
    </script>
@endpush
