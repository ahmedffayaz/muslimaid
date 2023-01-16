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
