<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserVerify;
use App\Models\UserCashback;

class VerifyController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();

        session()->flash('message', 'Sorry your email cannot be identified.');
        session()->flash('alert-class', 'alert-danger');

        if (!is_null($verifyUser)) {
            $user = $verifyUser->user;

            if (!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();

                if (!empty($verifyUser->user->referred_by)) {
                    $referralBonus = array_key_exists('referral_bonus', SiteSetting()->toArray()) ? (SiteSetting()['referral_bonus'] != NULL ? SiteSetting()['referral_bonus'] : 0) : 0;
                    if ($referralBonus != 0) {
                        $referral_bonus = UserCashback::create([
                            'user_id' => $verifyUser->user->referred_by,
                            'amount' => $referralBonus,
                            'status' => '3',
                        ]);
                    }
                }

                session()->flash('message', 'Your e-mail is verified. You can now login.');
                session()->flash('alert-class', 'alert-success');
            } else {
                session()->flash('message', 'Your e-mail is already verified. You can now login.');
                session()->flash('alert-class', 'alert-success');
            }
        }

        return redirect()->route('login');
    }
}
