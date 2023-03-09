@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block-head nk-block-head-lg pb-2">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title fw-normal"></h3>
                                <div class="nk-block-des">

                                </div>
                            </div>
                            <div class="nk-block-head-content">

                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                        data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">

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
                                <div class="nk-msg-body bg-white profile-shown">
                                    <div class="nk-msg-head">
                                        <h4 class="title d-none d-lg-block mb-2">{{ $ticket->title }}</h4>
                                        <div class="nk-msg-head-meta align-items-start">

                                            <div class="d-none d-lg-block">
                                                @if ($ticket->category_id)
                                                <ul class="nk-msg-tags">
                                                    <li><span class="label-tag"><em class="icon ni ni-flag-fill"></em>
                                                            <span>{{ $ticket->category->name ?? '' }}</span></span></li>
                                                </ul>
                                                @endif
                                            </div>

                                            <ul class="nk-msg-actions">
                                                <li class="text-right">
                                                    @if ($ticket->status != 'closed')
                                                    <a onclick="$('#close-{{ $ticket->id }}').submit();"
                                                        style="cursor: pointer"
                                                        class="btn btn-dim btn-sm btn-outline-light"> <em
                                                            class="icon ni ni-check"></em><span>Mark as
                                                            Closed</span></a>

                                                    <form action="{{ route('admin.tickets.close', $ticket) }}"
                                                        id="close-{{ $ticket->id }}" method="POST" class="m-0">
                                                        @method('PUT')
                                                        @csrf

                                                    </form>
                                                    @else
                                                    <button href="#" class="btn btn-sm btn-success" disabled> <em
                                                            class="icon ni ni-check"></em><span>Closed</span></button><br>
                                                    <div class="mt-1">Closed by:
                                                        {{ $ticket->closedByUser->first_name  ?? 'super' }}
                                                        {{ $ticket->closedByUser->second_name  ?? 'admin' }} at
                                                        {{ $ticket->closing_time ?? '' }}</div>
                                                    @endif

                                                </li>

                                            </ul>
                                        </div>
                                    </div><!-- .nk-msg-head -->
                                    <div class="nk-msg-reply nk-reply" data-simplebar>
                                        <div class="nk-msg-head py-4 d-lg-none">
                                            <h4 class="title">{{ $ticket->title }}</h4>
                                            <ul class="nk-msg-tags">
                                                @if ($ticket->category_id)
                                                <li><span class="label-tag"><em class="icon ni ni-flag-fill"></em>
                                                        <span>{{ $ticket->category->name ?? '' }}</span></span></li>
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="nk-reply-item">
                                            <div class="nk-reply-header">
                                                <div class="user-card">
                                                    <div class="user-avatar sm bg-blue text-uppercase">
                                                        <span>{{ $ticket->user->first_name[0] }}{{
                                                            $ticket->user->last_name[0] }}</span>
                                                    </div>
                                                    <div class="user-name text-capitalize">{{ $ticket->user->first_name
                                                        }}
                                                        {{ $ticket->user->last_name }}</div>
                                                </div>
                                                <div class="date-time">
                                                    {{ Carbon\Carbon::parse($ticket->created_at)->isoFormat('Do MMMM
                                                    YYYY') }}
                                                </div>
                                            </div>
                                            <div class="nk-reply-body">
                                                <div class="nk-reply-entry entry">
                                                    @if ($ticket->ticket_type == 'claim')
                                                    Store: <a
                                                        href="{{ route('admin.stores.show_store') }}?slug={{ $ticket->store->slug }}">{{
                                                        $ticket->store->id }}
                                                        - {{ $ticket->store->name }}</a><br>
                                                    Purchase Amount: {{ currency($ticket->claim_amount) }}<br>
                                                    Claim date:
                                                    {{ Carbon\Carbon::parse($ticket->created_at)->isoFormat('Do MMMM
                                                    YYYY') }}<br>
                                                    Click ID: {{ $ticket->click_id }}<br>
                                                    Click date:
                                                    {{ Carbon\Carbon::parse($ticket->click->created_at)->isoFormat('Do
                                                    MMMM YYYY') }}<br>
                                                    Description:  {{ $ticket->message }}
                                                    @endif


                                                </div>

                                            </div>
                                        </div><!-- .nk-reply-item -->
                                        @if (count($ticket->replies))
                                        @foreach ($ticket->replies as $reply)
                                        <div class="nk-reply-item">
                                            <div class="nk-reply-header">
                                                <div class="user-card">
                                                    <div
                                                        class="user-avatar sm @if ($reply->reply_by == 'admin') bg-pink @else bg-blue @endif text-uppercase">
                                                        <span>{{ $reply->user->first_name[0] }}{{
                                                            $reply->user->last_name[0] }}</span>
                                                    </div>
                                                    <div class="user-name text-capitalize">{{ $reply->user->first_name
                                                        }}
                                                        {{ $reply->user->last_name }}</span></div>
                                                </div>
                                                <div class="date-time">
                                                    {{ Carbon\Carbon::parse($reply->created_at)->isoFormat('Do MMMM
                                                    YYYY') }}
                                                </div>
                                            </div>
                                            <div class="nk-reply-body">
                                                <div class="nk-reply-entry entry">
                                                    <p>{{ $reply->reply }}</p>

                                                </div>
                                            </div>
                                        </div><!-- .nk-reply-item -->
                                        @endforeach
                                        @endif

                                        @if ($ticket->status != 'closed')
                                        <div class="nk-reply-form">
                                            <div class="nk-reply-form-header">
                                                <ul class="nav nav-tabs-s2 nav-tabs nav-tabs-sm">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-toggle="tab"
                                                            href="#reply-form">Reply</a>
                                                    </li>

                                                </ul>

                                            </div>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="reply-form">
                                                    <div class="nk-reply-form-editor">
                                                        <form method="POST" action="{{ route('admin.replies.store') }}"
                                                            class="form-validate">
                                                            @csrf
                                                            <input type="hidden" name="ticket_id"
                                                                value="{{ $ticket->id }}">
                                                            <div class="nk-reply-form-field">
                                                                <textarea
                                                                    class="form-control form-control-simple no-resize"
                                                                    name="reply" placeholder="Hello"
                                                                    required>{{ old('reply') }}</textarea>
                                                            </div>
                                                            @error('reply')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                            <div class="nk-reply-form-tools">
                                                                <ul class="nk-reply-form-actions g-1">
                                                                    <li class="mr-2"><button class="btn btn-primary"
                                                                            type="submit">Reply</button></li>

                                                                </ul>

                                                            </div><!-- .nk-reply-form-tools -->
                                                        </form>
                                                    </div><!-- .nk-reply-form-editor -->
                                                </div>

                                            </div>
                                        </div><!-- .nk-reply-form -->
                                        @endif
                                    </div><!-- .nk-reply -->

                                </div><!-- .nk-msg-body -->

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
<script>
    $('.form-validate').validate({
            errorClass: 'invalid-feedback d-block',
            rules: {
                reply: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                if ($(form).valid())
                    form.submit();
                return false;
            }
        });
</script>
@endpush
