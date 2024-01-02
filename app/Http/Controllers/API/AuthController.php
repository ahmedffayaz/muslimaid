<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use App\Traits\UserBonus;
use App\Jobs\SendOTPEmail;
use App\Models\UserDevice;
use App\Traits\ApiResponser;
use App\Traits\WelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Home\UserResource;
use App\Jobs\SendEmailJob;
use App\Traits\SubscribeNewsletter;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponser, WelcomeEmail, UserBonus, SubscribeNewsletter;

    public function register(Request $request)
    {
        try {
            $rules = [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
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
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $response = [
                    'status' => 406,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ];
                return response()->json($response, 406);
            }

            $otp = strval(random_int(100000, 999999));
            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'otp' => $otp,
                'status' => 'pending',
                'avatar' => 'default.png',
                'registration_type' => 'sign up',
                'short_ref_id' => uniqueRefLinkGenerator()
            ]);

            if (!empty($request->ref_code)) {
                $user->metaData()->create([
                    'user_id' => $user->id,
                    'type' => 'referral_code',
                    'value' => !empty($request->ref_code) ? $request->input('ref_code') : null
                ]);
            }

            $user->assignRole('user');
            $bonusStatus = 3;

            // verify email
            $this->welcomBonus($user, $bonusStatus);
            dispatch(new SendOTPEmail($user));

            // Add email in SendGrid's register contact list
            $this->registerNewsletter(['type' => 'register', 'user' => $user]);

            $user = new UserResource($user);
            $response = [
                'status' => 200,
                'message' => "User registered successfully",
                'data' => $user,
            ];

            return response()->json($response, 200);
        } catch (Exception $e) {
            $response = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($response, 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|string|email|',
                'password' => 'required|string|min:6',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $response = [
                    'status' => 406,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ];
                return response()->json($response, 406);
            }

            $userData = User::where('email', $request->email)->first();
            if (isset($userData)) {
                if ($userData->status == 'in_active') {
                    $response = [
                        'status' => 401,
                        'message' => 'Your account is inactive',
                        'data' => []
                    ];
                    return response()->json($response, 401);
                }

                if ($userData->status == 'pending') {
                    dispatch(new SendEmailJob($userData));
                    $response = [
                        'status' => 401,
                        'message' => 'Your account is not verified. We have sent you the verification email. Please verify your account before login',
                        'data' => []
                    ];
                    return response()->json($response, 401);
                }
            }

            if (!auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
                $response = [
                    'status' => 200,
                    'message' => 'Email or password is incorrect.',
                    'data' => []
                ];
                return response()->json($response, 200);
            }

            if($request->has('fcmtoken')){
                DB::beginTransaction();
                auth()->user()->devices()->updateOrCreate([
                    'fcm_token' => $request->fcmtoken,
                    'type' => UserDevice::TYPE_API
                ]);
                DB::commit();
            }

            $user = new UserResource(User::where('email', $request->email)->first());
            if(auth()->user()->short_ref_id == null){
                uniqueRefLinkGenerator();
            }
            $response = [
                'status' => 200,
                'message' => "Successful login.",
                'data' => $user,
            ];

            return response()->json($response, 200);
        } catch (Exception $e) {
            $response = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($response, 500);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
            $response = [
                'status' => 200,
                'message' => "User successfully logged out",
                'data' => []
            ];

            return response()->json($response, 200);
        } catch (Exception $e) {
            $response = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($response, 500);
        }
    }

    public function changePassword(Request $request)
    {
        $rules = [
            'old_password' => ['required'],
            'new_password' => ['required'],
            'confirm_password' => ['required', 'same:new_password'],
        ];

        $passwordRules = env('PASSWORD_VALIDATION', '');
        if(!empty($passwordRules)){
            $additionalRules = explode('|', $passwordRules);
            $rules['new_password'] = array_merge($rules['new_password'], $additionalRules);
            $rules['confirm_password'] = array_merge($rules['new_password'], $additionalRules);
        }else {
            $rules['new_password'][] = 'string';
            $rules['confirm_password'][] = 'string';
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response = [
                'status' => 406,
                'message' => $validator->errors()->first(),
                'data' => []
            ];
            return response()->json($response, 406);
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    $response = [
                        'status' => 400,
                        'message' => "Check your old password.",
                        'data' => []
                    ];
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    $response = [
                        'status' => 400,
                        'message' => "Please enter a password which is not similar then current password.",
                        'data' => []
                    ];
                } else {
                    User::where('id', Auth::user()->id)->update(['password' => Hash::make($request->new_password)]);
                    $response = [
                        'status' => 200,
                        'message' => "Password updated successfully.",
                        'data' => []
                    ];
                    return response()->json($response, 200);
                }
                return response()->json($response, 400);
            } catch (\Exception $e) {
                $response = [
                    'status' => 500,
                    'message' => 'Something went wrong, try again.',
                    'data' => []
                ];
                return response()->json($response, 500);
            }
        }
    }

    public function resendOtpCode(Request $request)
    {
        $rules = array(
            'email' => 'required|email',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 406,
                'message' => $validator->errors()->first(),
                'data' => []
            ];
            return response()->json($response, 406);
        } else {
            $user = User::where('email', $request->email)->first();
            if (!isset($user)) {
                $response = [
                    'status' => 404,
                    'message' => 'User not found',
                    'data' => []
                ];
                return response()->json($response, 404);
            } else {
                dispatch(new SendOTPEmail($user));
                $response = [
                    'status' => 200,
                    'message' => "Verification email sent successfully",
                    'data' => $user,
                ];

                return response()->json($response, 200);
            }
        }
    }

    public function verifyOtpCode(Request $request)
    {
        $rules = array(
            'email' => 'required|email',
            'otp' => 'required|max:6',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 406,
                'message' => $validator->errors()->first(),
                'data' => []
            ];
            return response()->json($response, 406);
        } else {
            try {
                $user = User::where('email', $request->email)->first();
                if (!isset($user)) {
                    $response = [
                        'status' => 404,
                        'message' => 'Registered user not found',
                        'data' => []
                    ];
                    return response()->json($response, 404);
                } else if ((int)$user->otp === (int)$request->otp && !empty($user->otp)) {
                    $user->status = "active";
                    $user->save();
                    $response = [
                        'status' => 200,
                        'message' => "Otp successfully verified",
                        'data' => []
                    ];
                    return response()->json($response, 200);
                } else {
                    $response = [
                        'status' => 400,
                        'message' => "Error! Entered Otp doesn't match",
                        'data' => []
                    ];
                    return response()->json($response, 400);
                }
            } catch (\Exception $ex) {
                $response = [
                    'status' => 400,
                    'message' => 'Something went wrong, try again.',
                    'data' => []
                ];
                return response()->json($response, 400);
            }
        }
    }

    public function forgotPassword(Request $request)
    {
        $rules = array(
            'email' => 'required|email',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 406,
                'message' => $validator->errors()->first(),
                'data' => []
            ];
            return response()->json($response, 406);
        } else {
            try {

                $response = \Password::sendResetLink($request->only('email'));
                switch ($response) {
                    case \Password::RESET_LINK_SENT: {

                            $user = User::where('email', $request->email)->first();
                            $userData =  [
                                'id' => $user->id,
                                'name' => $user->first_name . ' ' . $user->last_name,
                                'email' => $user->email,
                            ];
                            $passingData =  [
                                'user' => $userData,
                                'otp' => empty($user->otp) ? '' : $user->otp,
                            ];
                            $response = [
                                'status' => 200,
                                'message' => "If " . $request->email  . " is registered with Cashblack, password reset instructions will be sent to the address.",
                                'data' => $passingData,
                            ];
                            return response()->json($response, 200);
                        }
                    case \Password::INVALID_USER: {
                            $response = [
                                'status' => 401,
                                'message' => "Invalid Email",
                                'data' => []
                            ];
                            return response()->json($response, 401);
                        }
                    default: {
                            $user = User::where('email', $request->email)->first();
                            $userData =  [
                                'id' => $user->id,
                                'name' => $user->first_name . ' ' . $user->last_name,
                                'email' => $user->email,
                            ];
                            $passingData =  [
                                'user' => $userData,
                                'otp' => empty($user->otp) ? '' : $user->otp,
                            ];
                            $response = [
                                'status' => 200,
                                'message' => "If " . $request->email  . " is registered with Cashblack, password reset instructions will be sent to the address.",
                                'data' => $passingData,
                            ];
                            return response()->json($response, 200);
                        }
                }
            } catch (\Swift_TransportException $ex) {
                $response = [
                    'status' => 400,
                    'message' => 'Something went wrong, try again.',
                    'data' => []
                ];
                return response()->json($response, 400);
            } catch (\Exception $ex) {
                $response = [
                    'status' => 400,
                    'message' => 'Something went wrong, try again.',
                    'data' => []
                ];
                return response()->json($response, 400);
            }
        }
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
                'profile_image' => $userExists->avatar ? url('storage/users/images/avatar/' . $userExists->avatar) : ''
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
            'is_email_verified' => 1,
            'status' => 'active',
            'short_ref_id' => uniqueRefLinkGenerator(),
            'avatar' => 'default.png'
        ]);

        $user->assignRole('user');

        // Send welcome email to user and adding the bonus for user
        $data = array(
            'name' => $user->first_name,
            'email' => $user->email
        );
        $merge_subject = ['subject' => null, 'message' => null];
        $data = array_merge($data, $merge_subject);
        $bonusStatus = 3;
        $this->welcomBonus($user, $bonusStatus);
        $this->welcomeEmail($data);

        return $this->success([
            'token' => $user->createToken('API Token')->plainTextToken,
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "email" => $user->email,
            "registration_type" => $user->registration_type,
            'phone' => $user->phone,
            'intro' => $user->intro,
            'profile_image' => $user->avatar ? url('storage/users/images/avatar/' . $user->avatar) : ''
        ], 'User Registered Successfully');
    }
}
