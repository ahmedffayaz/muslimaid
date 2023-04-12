<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Home\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
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
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
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
                'message' => $validator->errors()->first(),
                'data' => []
            ];
            return response()->json($data, 406);
        } else {
            try {
                $avatarImage = $user->avatar;
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
                    'message' => "Successfully Updated.",
                    'data' => $user,
                ];
                return response($response, 200);
            } catch (\Exception $e) {
                $data = [
                    'status' => 500,
                    'message' => 'Something went wrong, try again.',
                    'data' => []
                ];
                return response()->json($data, 500);
            }
        }
    }


    public function updateAvatar(Request $request)
    {
        try {
            $user = auth()->user();
            $validator = Validator::make($request->all(), [
                'file' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 406,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ];
                return response()->json($data, 406);
            } else {
                $avatarImage = $user->avatar;
                if ($request->hasFile('file')) {
                    $avatarImage = storeUserAvatar($request->file('file'), $avatarImage);
                }
                $user->update([
                    'avatar' => $avatarImage,
                ]);

                $user = new UserResource($user);
                $response = [
                    'status' => 200,
                    'message' => "Successfully Updated.",
                    'data' => [
                        'image_url' => url('/') . '/' . ($user->avatar == 'default.png' || !Storage::exists('public/users/images/avatar/' . $user->avatar) ? 'admin-dashboard/images/avatar.png' : 'storage/users/images/avatar/' . $user->avatar),
                    ],
                ];
                return response($response, 200);
            }
        } catch (\Exception $e) {
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }
}
