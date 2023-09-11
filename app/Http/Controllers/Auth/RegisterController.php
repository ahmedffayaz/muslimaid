<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Exception;
use App\Traits\UserBonus;
use App\Jobs\SendEmailJob;
use App\Traits\WelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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

    use RegistersUsers, UserBonus, WelcomeEmail;

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
        $rules = [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ];

        $passwordRules = env('PASSWORD_VALIDATION', '');
        if(!empty($passwordRules)){
            $additionalRules = explode('|', $passwordRules);
            $rules['password'] = array_merge($rules['password'], $additionalRules);
        }else {
            $rules['password'][] = 'string';
        }
        // Check if reCAPTCHA key is set
        if (!empty(getSpecificSetting('google_recaptcha_site_key')) && !empty(getSpecificSetting('google_recaptcha_secret_key'))) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        return Validator::make($data, $rules);
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
        try {
            $today = Carbon::today()->toDateString();
            $user =  User::create([
                'first_name' => $data['firstname'],
                'last_name' => $data['lastname'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'registration_type' => 'sign up',
                'referred_by' => empty($data['referral_code']) ? '' : User::where('short_ref_id', $data['referral_code'])->first()->id,
                'referred_at' => empty($data['referral_code']) ? '' : $today,
                'short_ref_id' => uniqueRefLinkGenerator()
            ]);

            $user->assignRole('user');

            $bonusStatus = 3;

            $this->welcomBonus($user, $bonusStatus);
            //send email to user to verify email address
            dispatch(new SendEmailJob($user));

            if (!empty($settings['sendgrid_registered_list_id']) && !empty($settings['sendgrid_api_key'])) {
                $settings = SiteSetting();
                $requestBody = [
                    'list_ids' => [
                        isset($settings['sendgrid_registered_list_id']) ? $settings['sendgrid_registered_list_id'] : "",
                    ],
                    'contacts' => [
                        [
                            'email' => $data['email'],
                            'first_name' => $data['firstname'],
                            'last_name' => $data['lastname'],
                        ]
                    ]
                ];
                $apiKey = isset($settings['sendgrid_api_key']) ? $settings['sendgrid_api_key'] : "";
                $sg = new \SendGrid($apiKey);

                $response = $sg->client->marketing()->contacts()->put($requestBody);
                if ($response->statusCode() != 201 && $response->statusCode() != 202) {
                    return redirect()->route('login')->with(['error' => 'Something went wrong!']);
                }
            }
            return redirect()->route('login')->with(['success' => 'User Successfully registered, verify your account'],);
        } catch (Exception $ex) {
            return redirect()->route('login')->with(['error' => 'Something went wrong!']);
        }
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
