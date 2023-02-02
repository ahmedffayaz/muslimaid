<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Traits\ApiResponser;
use App\Traits\WelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponser, WelcomeEmail;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
            'registration_type' => 'sign up',
        ]);

        $user->assignRole('user');

        $bonus = array_key_exists('welcome_bonus', SiteSetting()->toArray()) ? SiteSetting()['welcome_bonus'] : 0;

        $user_bonus = Bonus::create([
            'user_id' => $user->id,
            'amount' => $bonus,
        ]);

        // Send welcome email to user
        $data = array(
            'name' => $user->first_name,
            'email' => $user->email
        );
        $merge_subject = ['subject' => null, 'message' => null];
        $data = array_merge($data, $merge_subject);
        $this->welcomeEmail($data);

        return $this->success([
            'token' => $user->createToken('API Token')->plainTextToken,
            'id' => $user->id,
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "email" => $user->email,
            "registration_type" => $user->registration_type,
            'phone' => $user->phone,
            'intro' => $user->intro,
            'profile_image' => $user->avatar ? url('storage/users/images/avatar/'.$user->avatar) : ''
        ], 'User registered successfully');
    }

    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6',
        ]);

        if (!Auth::attempt($attr)) {
            return $this->error('Credentials did not match', 401);
        }

        return $this->success([
            'token' => auth()->user()->createToken('API Token')->plainTextToken,
            'id' => auth()->user()->id,
            'first_name' => auth()->user()->first_name,
            'last_name' => auth()->user()->last_name,
            'email' => auth()->user()->email,
            'phone' => auth()->user()->phone,
            'intro' => auth()->user()->intro,
            'profile_image' => auth()->user()->avatar ? url('storage/users/images/avatar/'.auth()->user()->avatar) : ''
        ], 'User logged in successfully', 200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->success([
            'message' => 'User logged out',
        ]);
    }

    public function userData(Request $request)
    {
        $user = auth()->user();
        return [
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "email" => $user->email,
            "registration_type" => $user->registration_type,
        ];

    }
    public function forgotPassword(Request $request)
    {

        $input = $request->all();
        $rules = array(
            'email' => "required|email",
        );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 401);
        } else {
            try {
                $response = \Password::sendResetLink($request->only('email'));
                switch ($response) {
                    case \Password::RESET_LINK_SENT:
                        return $this->success([], trans($response));
                    case \Password::INVALID_USER:
                        return $this->error(trans($response), 401);
                }
            } catch (\Swift_TransportException $ex) {
                return $this->error($ex->getMessage(), 400);
            } catch (\Exception $ex) {
                return $this->error($ex->getMessage(), 400);

            }
        }
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();
        $input = $request->all();
        $userid = $user->id;
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => array());
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => 400, "message" => $msg, "data" => array());
            }
        }
        return \Response::json($arr);

    }
    public function socialLogin(Request $request)
    {
        $provider_id = $request->input('provider_id');
        $email = $request->input('email');
        $userExists = User::where(['provider_id' => $provider_id, 'email' => $email])->first();
        if ($userExists) {
            return $this->success([
                'token' => $userExists->createToken('API Token')->plainTextToken,
                'id' => $userExists->id,
                "first_name" => $userExists->first_name,
                "last_name" => $userExists->last_name,
                "email" => $userExists->email,
                'phone' => $userExists->phone,
                'intro' => $userExists->intro,
                "registration_type" => $userExists->registration_type,
                'profile_image' => $userExists->avatar ? url('storage/users/images/avatar/'.$userExists->avatar) : ''
            ], 'User Logged In Successfully');

        }
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'provider' => ['required'],
            'provider_id' => ['required', 'unique:users'],
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 401);
        }
        $user = User::create([
            'first_name' => $request->input('firstname'),
            'last_name' => $request->input('lastname'),
            'email' => $request->input('email'),
            'password' => Hash::make(time() . $request->input('email') . '_' . \Str::random(12)),
            'provider_id' => $request->input('provider_id'),
            'provider' => $request->input('provider'),
            'registration_type' => 'social',
        ]);

        $user->assignRole('user');

        $bonus = array_key_exists('welcome_bonus', SiteSetting()->toArray()) ? SiteSetting()['welcome_bonus'] : 0;

        $user_bonus = Bonus::create([
            'user_id' => $user->id,
            'amount' => $bonus,
        ]);

        // Send welcome email to user
        $data = array(
            'name' => $user->first_name,
            'email' => $user->email
        );
        $merge_subject = ['subject' => null, 'message' => null];
        $data = array_merge($data, $merge_subject);
        $this->welcomeEmail($data);

        return $this->success([
            'token' => $user->createToken('API Token')->plainTextToken,
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "email" => $user->email,
            "registration_type" => $user->registration_type,
            'phone' => $user->phone,
            'intro' => $user->intro,
            'profile_image' => $user->avatar ? url('storage/users/images/avatar/'.$user->avatar) : ''
        ], 'User Registered Successfully');
    }
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $input = $request->all();
        $userid = $user->id;
        $rules = array(
            'firstname' => 'required|regex:/^[A-Za-z ]+$/',
            'lastname' => 'required|regex:/^[A-Za-z ]+$/',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                $user->update([
                    'first_name' => $request->firstname,
                    'last_name' => $request->lastname,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'intro' => $request->intro,
                ]);
                if($request->has('profile_image')){

                    $imageName = $request->firstname.'_user_avatar_'.time().'.png';
                    $file = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$request->input('profile_image')));
                    \Storage::put('public/users/images/avatar/'.$imageName, $file);
                    $user->update([
                       'avatar'=>$imageName
                   ]);

                }
                $data = [
                    'id' => auth()->user()->id,
                    'first_name' => auth()->user()->first_name,
                    'last_name' => auth()->user()->last_name,
                    'email' => auth()->user()->email,
                    'phone' => auth()->user()->phone,
                    'address' => auth()->user()->adress,
                    'intro' => auth()->user()->intro,
                    'profile_image' => auth()->user()->avatar ? url('storage/users/images/avatar/'.auth()->user()->avatar) : ''
                ];
                $arr = array("status" => 200, "message" => "Profile updated successfully.", "data" => $data);

            } catch (\Exception $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => array());
            }
        }
        return \Response::json($arr);
    }

}
