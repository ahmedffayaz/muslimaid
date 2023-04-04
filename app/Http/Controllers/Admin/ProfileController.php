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
            'sort_code' => isset($request->sort_code) ? $request->sort_code : $profile->sort_code,
            'bank_acc_no' => isset($request->bank_acc_no) ? $request->bank_acc_no : $profile->bank_acc_no,
            'paypal_email' => isset($request->paypal_email) ? $request->paypal_email : $profile->paypal_email,
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
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

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
            return redirect()->route('admin.profile.index');
        } else {
            return array(
                'message' => 'Password updated successfully',
                'updated' => 'success'
            );
        }
    }
}
