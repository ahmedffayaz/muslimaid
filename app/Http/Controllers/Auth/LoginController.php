<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->provider != 'email'){
            auth()->logout();
            Session::flash('message', 'Please login with social media instead of.');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('login');
        }
    }

    protected function redirectTo()
    {
        if (Session::has('prvUrl')){
            return session('prvUrl');
          }else{

            if (!Auth::user()->is_email_verified) {
              auth()->logout();
              Session::flash('email-not-verified');
              return route('login');
            }
            Session::flash('login-welcome');
            return '/';
          }
    }
}
