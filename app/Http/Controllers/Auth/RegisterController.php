<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Traits\UserBonus;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use UserBonus;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => ['required','captcha'],
        ]);
    }

    public function showRegistrationForm(Request $request)
    {
        $refCode = $request->referby;
        Session::put('refCode', $refCode);
        return view('frontend.auth.register', compact('refCode'));
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $today = Carbon::today()->toDateString();
        $user =  User::create([
            'first_name' => $data['firstname'],
            'last_name' => $data['lastname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'registration_type' => 'sign up',
            'referred_by' => empty($data['referral_code']) ? '' : base64_decode($data['referral_code']),
            'referred_at' => empty($data['referral_code']) ? '' : $today,
        ]);

        $user->assignRole('user');

        $bonusStatus = 1;

        $this->welcomBonus($user, $bonusStatus);

        $email_template = EmailTemplate::where('key', 'user_welcome')->first();

        $filtered_message  = str_replace(['{{SITE_TITLE}}', '{{SITE_URL}}', '{{NAME}}', '{{EMAIL}}'], [SiteSetting()['website_title'], url('/'), $user->first_name, $user->email], $email_template->message);

        $email_data = array(
            'name' =>  $data['firstname'],
            'email' => $data['email'],
            'email_message' => $filtered_message,
            'subject' => $email_template->subject
        );

        Mail::send('emails.email_template', $email_data, function ($message) use ($email_data) {
            $message->to($email_data['email'], $email_data['name'])
                ->subject($email_data['subject']);
        });

        //send email to user to verify email address
        dispatch(new \App\Jobs\SendEmailJob($user));

        return redirect()->route('login');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }


    protected function redirectTo()
    {
        Session::flash('welcome', 'welcome message');
        if (Session::has('prvUrl')) {
            return session('prvUrl');
        } else {
            return '/';
        }
    }
}
