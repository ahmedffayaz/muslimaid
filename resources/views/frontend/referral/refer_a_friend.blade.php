@extends('frontend.layouts.app')

@section('content')

    @include('frontend.layouts.includes.toast')

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
                                        <form action="{{ route('account.send-referral-link') }}" id="send-email" method="post">
                                            @csrf
                                            <div class="row g-3 align-center">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="cashback_percentage">
                                                            Invite Your Friends
                                                            <em class="icon ni ni-question form-label" data-toggle="tooltip" data-placement="top"
                                                                title="Enter cashback percentage which will be given to user."></em>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9">
                                                    <div class="form-group">
                                                        <div class="form-control-wrap">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="referral_email" id="referral_email" placeholder="Enter email">
                                                                <button class="input-group-btn btn btn-primary go inline">Send</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="row g-3 align-center">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="form-label" for="cashback_percentage">
                                                    Share Your Link
                                                    <em class="icon ni ni-question form-label" data-toggle="tooltip" data-placement="top"
                                                        title=" Enter cashback percentage which will be given to user."></em>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="ref_link"
                                                        value="{{ url('/register-form?referby=' . base64_encode($user = \Auth::user()->id)) }}" readonly>
                                                    <button class="input-group-btn btn btn-primary go inline" onclick="copyText()">Copy</button>
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
        $('#send-email').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                processData: false,
                contentType: false,
                data: new FormData(this),
                success: function(response) {
                    console.log(response)
                    $('div.toast').removeClass('d-none bg-danger');
                    $('div.toast').addClass('bg-success');
                    $('#toast-message').text(response.message);
                    $('div.toast').toast({
                        delay: 3000
                    });
                    $('div.toast').toast('show');
                },
                error: function(response) {
                    console.log(response)

                    let errors = response.responseJSON.message;
                    let error;

                    for (const key in errors) {
                        error = `${errors[key]}`
                    }

                    $('div.toast').removeClass('d-none');
                    $('div.toast').addClass('bg-danger');
                    $('#toast-message').text(error);
                    $('div.toast').toast({
                        delay: 3000
                    });

                    $('div.toast').toast('show');
                }
            })
        })

        function copyText() {
            var copyText = document.getElementById("ref_link");
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            navigator.clipboard.writeText(copyText.value);

            if (navigator.clipboard.writeText.length == 1) {
                $('div.toast').removeClass('d-none bg-danger');
                $('div.toast').addClass('bg-success');
                $('#toast-message').text('Referral link copied');
                $('div.toast').toast({
                    delay: 3000
                });
                $('div.toast').toast('show');
            }
        }
    </script>
@endpush
