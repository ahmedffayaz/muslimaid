<?php

namespace App\Http\Controllers\Auth;

use Throwable;
use App\Traits\UserBonus;
use App\Models\UserVerify;
use App\Traits\WelcomeEmail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class VerifyController extends Controller
{
    use UserBonus, WelcomeEmail;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function verifyAccount($token)
    {
        try {
            DB::beginTransaction();
            $verifyUser = UserVerify::where('token', $token)->first();

            if (!is_null($verifyUser)) {
                $user = $verifyUser->user;

                if (!$user->is_email_verified) {
                    $verifyUser->user->is_email_verified = 1;
                    $verifyUser->user->status = 'active';
                    $verifyUser->user->save();

                    $bonusStatus = 3;

                    $this->welcomBonus($user, $bonusStatus);

                    // Send welcome email to user
                    $data['name'] = $verifyUser->user->first_name;
                    $data['email'] = $verifyUser->user->email;
                    $merge_subject = ['subject' => null, 'message' => null];
                    $data = array_merge($data, $merge_subject);
                    $this->welcomeEmail($data);

                    if (!empty($verifyUser->user->referred_by)) {
                        $this->referralBonus($verifyUser->user->referred_by, $bonusStatus);
                    }

                    session()->flash('success', 'Your e-mail is verified. You can now login.');
                } else {
                    session()->flash('success', 'Your e-mail is already verified. You can now login.');
                }
            } else {
                session()->flash('message', 'Sorry your email cannot be identified.');
            }

            DB::commit();

            return redirect()->route('login');
        } catch (Throwable $th) {
            DB::rollBack();
            session()->flash('message', 'something went wrong, try again.');
        }
    }
}
