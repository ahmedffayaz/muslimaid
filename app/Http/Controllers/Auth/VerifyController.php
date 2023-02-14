<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserVerify;
use App\Traits\UserBonus;
use Illuminate\Support\Str;
use DB;

class VerifyController extends Controller
{
    use UserBonus;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();

        if (!is_null($verifyUser)) {
            $user = $verifyUser->user;

            if (!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();

                $bonusStatus = 3;

                $this->welcomBonus($user, $bonusStatus);

                if (!empty($verifyUser->user->referred_by)) {
                    $this->referralBonus($verifyUser->user->referred_by, $bonusStatus);
                }

                session()->flash('success', 'Your e-mail is verified. You can now login.');
            } else {
                session()->flash('success', 'Your e-mail is already verified. You can now login.');
            }
        } else {
            session()->flash('error', 'Sorry your email cannot be identified.');
        }

        return redirect()->route('login');
    }
}
