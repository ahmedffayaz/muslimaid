<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Home\UserResource;
use App\Models\Bonus;
use App\Models\User;
use App\Traits\ApiResponser;
use App\Traits\WelcomeEmail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponser, WelcomeEmail;

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
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
                'first_name' => 'unnamed',
                'last_name' => 'unnamed',
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'otp' => $otp,
                'status' => 'pending',
                'registration_type' => 'sign up',
            ]);

            $user->assignRole('user');
            $bonus = array_key_exists('welcome_bonus', SiteSetting()->toArray()) ? SiteSetting()['welcome_bonus'] : 0;
            Bonus::create([
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

            $user = new UserResource($user);
            $response = [
                'status' => 200,
                'message' => "Successful Registered.",
                'data' => $user,
            ];

            return response($response, 200);
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
                    $response = [
                        'status' => 401,
                        'message' => 'Please verify your account before login',
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

            $user = new UserResource(User::where('email', $request->email)->first());
            $response = [
                'status' => 200,
                'message' => "Successful login.",
                'data' => $user,
            ];

            return response($response, 200);
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

            return response($response, 200);
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
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
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
                $userOtp = User::where('email', $request->email)->pluck('otp')->first();
                if ((int)$userOtp === (int)$request->otp && !empty($userOtp)) {
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
                                'message' => "If" . $request->email  . " is registered with Cashblack, password reset instructions will be sent to the address.",
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
                                'message' => "If" . $request->email  . " is registered with Cashblack, password reset instructions will be sent to the address.",
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
        ]);

        $user->assignRole('user');

        $bonus = array_key_exists('welcome_bonus', SiteSetting()->toArray()) ? SiteSetting()['welcome_bonus'] : 0;

        $userBonus = Bonus::create([
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
            'profile_image' => $user->avatar ? url('storage/users/images/avatar/' . $user->avatar) : ''
        ], 'User Registered Successfully');
    }
}
