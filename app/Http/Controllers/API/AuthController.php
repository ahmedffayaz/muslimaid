<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\UserResource;
use App\Models\Bonus;
use App\Models\User;
use App\Traits\ApiResponser;
use App\Traits\WelcomeEmail;
use Exception;
use Illuminate\Http\JsonResponse;
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
                $data = [
                    'status' => 406,
                    'message' => $validator->errors()->first(),
                ];
                return response()->json($data, 406);
            }

            $otp = strval(random_int(100000, 999999));
            $user = User::create([
                'first_name' => 'unnamed',
                'last_name' => 'unnamed',
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'opt_code' => $otp,
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
            $data = [
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage() . 'Something went wrong, try again.'
            ];
            return response()->json($data, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
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
                $data = [
                    'status' => 406,
                    'message' => $validator->errors()->first(),
                ];
                return response()->json($data, 406);
            }

            $userData = User::where('email', $request->email)->first();
            if ($userData->status == 'in_active') {
                $data = [
                    'status' => 401,
                    'message' => 'Your account is inactive'
                ];
                return response()->json($data, 401);
            }

            if ($userData->status == 'pending') {
                $data = [
                    'status' => 401,
                    'message' => 'Please verify your account before login'
                ];
                return response()->json($data, 401);
            }

            if (!auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
                $data = [
                    'status' => JsonResponse::HTTP_OK,
                    'message' => 'Email or password is incorrect.'
                ];
                return response()->json($data, JsonResponse::HTTP_OK);
            }

            $user = new UserResource(User::where('email', $request->email)->first());
            $response = [
                'status' => 200,
                'message' => "Successful login.",
                'data' => $user,
            ];

            return response($response, 200);
        } catch (Exception $e) {
            $data = [
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage() . 'Something went wrong, try again.'
            ];
            return response()->json($data, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
            $response = [
                'status' => 200,
                'message' => "User successfully logged out",
            ];

            return response($response, 200);
        } catch (Exception $e) {
            $data = [
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage() . 'Something went wrong, try again.'
            ];
            return response()->json($data, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
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
            $data = [
                'status' => 406,
                'message' => $validator->errors()->first()
            ];
            return response()->json($data, 406);
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    $data = [
                        'status' => 400,
                        'message' => "Check your old password."
                    ];
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    $data = [
                        'status' => 400,
                        'message' => "Please enter a password which is not similar then current password."
                    ];
                } else {
                    User::where('id', Auth::user()->id)->update(['password' => Hash::make($request->new_password)]);
                    $data = [
                        'status' => 200,
                        'message' => "Password updated successfully."
                    ];
                    return response()->json($data, 200);
                }
                return response()->json($data, 400);
            } catch (\Exception $e) {
                $data = [
                    'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $e->getMessage() . 'Something went wrong, try again.'
                ];
                return response()->json($data, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function userData()
    {
        try {
            $user = auth()->user();
            $user = new UserResource($user);
            $response = [
                'status' => 200,
                'data' => $user,
            ];
            return response($response, 200);
        } catch (\Exception $e) {
            $data = [
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage() . 'Something went wrong, try again.'
            ];
            return response()->json($data, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 406,
                'message' => $validator->errors()->first()
            ];
            return response()->json($data, 406);
        } else {
            try {
                $avatarImage = $user->avatar;
                // dd($request->hasFile('avatar'));
                if ($request->hasFile('avatar')) {
                    $avatarImage = storeUserAvatar($request->file('avatar'), $avatarImage);
                }
                $user->update([
                    'first_name' => $request->firstname,
                    'last_name' => $request->lastname,
                    'email' => $request->input('email'),
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'date_of_birth' => $request->dob,
                    'avatar' => $avatarImage,
                ]);

                $user = new UserResource($user);
                $response = [
                    'status' => 200,
                    'message' => "Successful Updated.",
                    'data' => $user,
                ];
                return response($response, 200);
            } catch (\Exception $e) {
                $data = [
                    'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $e->getMessage() . 'Something went wrong, try again.'
                ];
                return response()->json($data, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
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
