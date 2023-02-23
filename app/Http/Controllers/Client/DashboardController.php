<?php

namespace App\Http\Controllers\Client;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ExitClick;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $items = $user->cashbacks()->whereHas('store')->latest()->limit(5)->get();
        return view('frontend.client-dashboard.dashboard', compact('user', 'items'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        return view('frontend.client-dashboard.edit-profile', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'firstname' => 'required|regex:/^[A-Za-z ]+$/',
            'lastname' => 'required|regex:/^[A-Za-z ]+$/',
        ], $messages = [
            'firstname.required' => 'First name is required.',
            'lastname.required' => 'Last name is required.'
        ]);
        $user = auth()->user();
        $avatarImage = $user->avatar;
        if ($request->hasFile('avatar')) {
            $avatarImage = store_user_avatar($request->file('avatar'), $avatarImage);
        }

        $user->update([
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'phone' => $request->phone,
            'address' => $request->address,
            'intro' => $request->intro,
            'avatar' => $avatarImage
        ]);
        flash()->success('User updated successfully');
        return redirect()->back();
    }

    public function cashback()
    {
        $user = Auth::user();
        $cashbacks = UserCashback::where('user_id', $user->id)->latest()->get();
        return view('frontend.client-dashboard.cashback', compact('user', 'cashbacks'));
    }

    public function clicks()
    {
        $user = Auth::user();
        $clicks = ExitClick::where('user_id', $user->id)->whereHas('store')->latest()->get();
        return view('frontend.client-dashboard.clicks', compact('user', 'clicks'));
    }

    public function changePassword()
    {
        return view('frontend.client-dashboard.change-password');
    }

    public function savePassword(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            flash()->error($validator->errors()->first());
            return redirect()->back();
        }
        if (!Hash::check($request->old_password, $user->password)) {
            flash()->error('Old password does not match with our records');
            return redirect()->back();
        } else if ($request->password != $request->password_confirmation) {
            flash()->error('Password confirmation do not match');
            return redirect()->back();
        } else {
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            flash()->success('Password changed successfully');
            return redirect()->back();
        }
    }
}
