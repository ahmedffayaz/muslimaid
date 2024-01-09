<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profile = Auth::user();
        return view('admin-dashboard.profile.edit', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $profile)
    {
        $request->validate([
            'first_name' => 'required|regex:/^[A-Za-z ]+$/',
            'last_name' => 'required|regex:/^[A-Za-z ]+$/',
        ], [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.'
        ]);

        $profile->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $avatarImage = $profile->avatar;
        if ($request->hasFile('avatar')) {
            $avatarImage = storeUserAvatar($request->file('avatar'), $avatarImage);
            $profile->update([
                'avatar' => $avatarImage
            ]);
        }

        flash()->success('Profile Updated');
        return redirect()->back();
    }

    public function savePassword(Request $request, User $user)
    {
        $rules = [
            'password' => ['required', 'confirmed']
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
            if (!$request->ajax()) {
                flash()->error($validator->errors()->first());
                return redirect()->back();
            } else {
                return array(
                    'message' => $validator->errors()->first(),
                    'updated' => 'error'
                );
            }
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        if (!$request->ajax()) {
            flash()->success('Password changed successfully');
            return redirect()->route(getAdminPrefix() . '.profile.index');
        } else {
            return array(
                'message' => 'Password updated successfully',
                'updated' => 'success'
            );
        }
    }
}
