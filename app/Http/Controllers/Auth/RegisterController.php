<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\UserBonus;
use App\Jobs\SendEmailJob;
use App\Traits\WelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\SubscribeNewsletter;
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

    use RegistersUsers, UserBonus, WelcomeEmail, SubscribeNewsletter;

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
        $recaptchaEnabled = !empty(getSpecificSetting('google_recaptcha_site_key')) && !empty(getSpecificSetting('google_recaptcha_secret_key'));

        $commonRules = [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ];

        $passwordRules = env('PASSWORD_VALIDATION', '');

        if (!empty($passwordRules)) {
            $additionalRules = explode('|', $passwordRules);
            $commonRules['password'] = array_merge($commonRules['password'], $additionalRules);
        } else {
            $commonRules['password'][] = 'string';
        }

        $rules = $recaptchaEnabled
            ? array_merge(['g-recaptcha-response' => 'required|captcha'], $commonRules)
            : $commonRules;

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
            DB::beginTransaction();
            $today = Carbon::today()->toDateString();
            $user =  User::create([
                'first_name' => $data['firstname'],
                'last_name' => $data['lastname'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'registration_type' => 'sign up',
                'referred_by' => empty($data['referral_code']) ? '' : User::where('short_ref_id', $data['referral_code'])->first()->id,
                'referred_at' => empty($data['referral_code']) ? '' : $today,
                'avatar' => 'default.png',
                'short_ref_id' => uniqueRefLinkGenerator()
            ]);

            if (!empty($data['ref_code'])) {
                $user->metaData()->create([
                    'user_id' => $user->id,
                    'type' => 'referral_code',
                    'value' => !empty($data['ref_code']) ? $data['ref_code'] : null
                ]);
            }

            $user->assignRole('user');

            $bonusStatus = 3;

            $this->welcomBonus($user, $bonusStatus);
            //send email to user to verify email address
            dispatch(new SendEmailJob($user));

            // Add email in SendGrid's register contact list
            $this->registerNewsletter(['type' => 'register', 'user' => $user]);

            DB::commit();
            return redirect()->route('login')->with(['success' => 'User Successfully registered, verify your account']);
        } catch (Exception $ex) {
            DB::rollBack();
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
        // Get deleted account
        $deletedUser = User::where('email', $request->input('email'))->withTrashed()->first();
        if (!empty($deletedUser))
            return redirect()->route('login')->with(['error' => 'The account has been deleted permanently.']);

        $validator = $this->validator($request->all());
        if($validator->fails()){
            if($request->ajax()){
                return response()->json([
                    'status' => 406,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ]);
            } else {
                Session::flash('error', $validator->errors()->first());
                return redirect()->back();
            }
        }
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
