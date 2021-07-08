<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailTemplate;
use App\Models\Bonus;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{
    use ApiResponser;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 401);
        }

        
        $user = User::create([
            'first_name' => $request->input('firstname'),
            'last_name' => $request->input('lastname'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'registration_type'=>'sign up',
        ]);

        $user->assignRole('user');
        $email_template = EmailTemplate::where('key','user_welcome')->first(); 

        $filtered_message  = str_replace(['%SITE_TITLE%', '%SITE_URL%', '%NAME%', '%EMAIL%'],[SiteSetting()['website_title'], url('/') ,$user->first_name,$user->email],$email_template->message );
        
        $email_data = array(
            'name' =>  $user->firstname,
            'email' => $user->email,
            'email_message'=>$filtered_message,
            'subject'=>$email_template->subject
        );

        $bonus = array_key_exists('welcome_bonus',SiteSetting()->toArray()) ? SiteSetting()['welcome_bonus'] : 0;

        $user_bonus = Bonus::create([
            'user_id'=>$user->id,
            'amount'=>$bonus,
        ]);
        
        
        Mail::send('emails.email_template', $email_data, function ($message) use ($email_data) {
            $message->to($email_data['email'], $email_data['name'])
                ->subject($email_data['subject']);
        });
        return $this->success([
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        if (!Auth::attempt($attr)) {
            return $this->error('Credentials not match', 401);
        }

        return $this->success([
            'token' => auth()->user()->createToken('API Token')->plainTextToken
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->success([
            'message' => 'User logged out'
        ]);
    }
}