<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\URL;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    //redirect
    protected function authenticated(Request $request, $user)
    {
        $previousUrl = URL::previous();
        // Check if the previous URL is the login URL, and if so, redirect to the home page
        if ($previousUrl == route('login')) {
            return redirect('/');
        }

        return redirect()->intended($request->prvUrl);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {

        $emailCheck = User::where('email', $request->email)->first();


        if (is_null($emailCheck)) {
            return redirect()->back()->with(['message' => 'Email address not found']);
        }

        if ($emailCheck->provider != 'email') {
            Session::flash('social-login');
            return redirect()->route('login');
        }

        if ($emailCheck->status == 'in_active') {
            return redirect()->back()->with(['message' => 'Your account is inactive']);
        }

        if ($emailCheck->status == 'pending') {
            return redirect()->back()->with(['message' => 'Please verify your account before login']);
        }

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $rules = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];
        // Check if reCAPTCHA key is set
        if (!empty(getSpecificSetting('google_recaptcha_site_key')) && !empty(getSpecificSetting('google_recaptcha_secret_key'))) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $request->validate($rules);
    }
    protected function redirectTo()
    {

        if (Session::has('prvUrl')) {
            return session('prvUrl');
        } else {
            if (!Auth::user()->is_email_verified || Auth::user()->status == 'pending') {
                auth()->logout();
                Session::flash('message', 'You need to confirm your account. Please check your email.');
                return route('login');
            }
            Session::flash('login-welcome');
            return '/';
        }
    }
}
