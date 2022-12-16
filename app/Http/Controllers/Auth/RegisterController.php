<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use App\Models\Bonus;
use App\Models\UserCashback;
use Session;

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
        ]);
    }

    public function showRegistrationForm(Request $request)
    {
        $refCode = $request->referby;
        return view('auth.register',compact('refCode'));
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
            'registration_type'=>empty($data['referral_code']) ? 'sign up' : 'refer',
            'referred_by'=>empty($data['referral_code']) ? '' : base64_decode($data['referral_code']),
            'referred_at'=>empty($data['referral_code']) ? '' : $today,
        ]);
        // $role = Role::create(['name' => 'user']);

        $user->assignRole('user');

        $email_template = EmailTemplate::where('key','user_welcome')->first(); 

        $filtered_message  = str_replace(['{{SITE_TITLE}}', '{{SITE_URL}}', '{{NAME}}', '{{EMAIL}}'],[SiteSetting()['website_title'], url('/') ,$user->first_name,$user->email],$email_template->message );
        
        $email_data = array(
            'name' =>  $data['firstname'],
            'email' => $data['email'],
            'email_message'=>$filtered_message,
            'subject'=>$email_template->subject
        );

        $bonus = array_key_exists('welcome_bonus',SiteSetting()->toArray()) ? SiteSetting()['welcome_bonus'] : 0;
      

        $user_bonus = Bonus::create([
            'user_id'=>$user->id,
            'amount'=>$bonus,
        ]);
     
        if(!empty($data['referral_code']))
        {
            $referralBonus = array_key_exists('referral_bonus',SiteSetting()->toArray()) ? SiteSetting()['referral_bonus'] : 0;
            $id =  base64_decode($data['referral_code']);
            $referral_bonus = UserCashback::create([
                'user_id'=>$id,
                'amount'=>$referralBonus,
                'status'=>'3',
            ]);
        }
        
        
        Mail::send('emails.email_template', $email_data, function ($message) use ($email_data) {
            $message->to($email_data['email'], $email_data['name'])
                ->subject($email_data['subject']);
        });

        return $user;
    }

    protected function redirectTo()
    {
        Session::flash('welcome','welcome message'); 
        if (Session::has('prvUrl')){
            return session('prvUrl');
        }else{
            return '/';
        }
    }

    
}
