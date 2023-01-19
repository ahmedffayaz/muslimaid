@if(Session::has('login-welcome'))
    <div class="toast bg-success m-2" role="alert" aria-live="assertive" aria-atomic="true"style="position:absolute; top:0; right:0; z-index: 200">
        <div class="toast-header p-3">
            <strong class="mr-auto">Welcome back {{Auth::user()->first_name}} {{Auth::user()->last_name}}.</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif

@if(Session::has('social-login'))
    <div class="toast bg-danger m-2" role="alert" aria-live="assertive" aria-atomic="true"style="position:absolute; top:0; right:0; z-index: 200">
        <div class="toast-header p-3">
            <strong class="mr-auto">Please login with social media instead of.</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif

@if(Session::has('login-expired'))
    <div class="toast bg-danger m-2" role="alert" aria-live="assertive" aria-atomic="true"style="position:absolute; top:0; right:0; z-index: 200">
        <div class="toast-header p-3">
            <strong class="mr-auto">Your Session has expired! Please login again.</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif

@if(Session::has('email-not-verified'))
    <div class="toast bg-danger m-2" role="alert" aria-live="assertive" aria-atomic="true"style="position:absolute; top:0; right:0; z-index: 200">
        <div class="toast-header p-3">
            <strong class="mr-auto">You need to confirm your account.please check your email.</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif

@if(Session::has('success'))
    <div class="toast bg-success m-2" role="alert" aria-live="assertive" aria-atomic="true"style="position:absolute; top:0; right:0; z-index: 200">
        <div class="toast-header p-3">
            <strong class="mr-auto">{{ Session::get('success') }}</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif

@if(Session::has('error'))
    <div class="toast bg-danger m-2" role="alert" aria-live="assertive" aria-atomic="true"style="position:absolute; top:0; right:0; z-index: 200">
        <div class="toast-header p-3">
            <strong class="mr-auto">{{ Session::get('error') }}</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif
