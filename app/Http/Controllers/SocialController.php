<?php

namespace App\Http\Controllers;

use Socialite;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\UserBonus;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class SocialController extends Controller
{
    use UserBonus;

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function Callback($provider)
    {
        $today = Carbon::today()->toDateString();
        $userSocial =   Socialite::driver($provider)->stateless()->user();
        $users       =   User::where(['email' => $userSocial->getEmail()])->first();
        if ($users) {
            Auth::login($users);
            if (Session::has('prvUrl')) {
                return redirect(session('prvUrl'));
            } else {
                return redirect('/');
            }
        } else {
            $name = $userSocial->getName();
            $fisrt_name = explode(" ", $name);
            if (count($fisrt_name) > 1) {
                foreach ($fisrt_name as $index => $elem) {
                    if ($index != 0) {
                        $last_name[] = $elem;
                    }
                }
            } else {
                $last_name[] = "";
            }
            $user = User::create([
                'first_name'        => $fisrt_name[0],
                'last_name'         => join(' ', $last_name),
                'email'             => $userSocial->getEmail(),
                'password'          => Hash::make('123456789'),
                'registration_type' => "social",
                'image'             => $userSocial->getAvatar(),
                'provider_id'       => $userSocial->getId(),
                'provider'          => $provider,
                'referred_by'       => Session::has('refCode') ? base64_decode(Session::get('refCode')) : null,
                'referred_at'       => Session::has('refCode') ? $today : '',
                'is_email_verified' => 1
            ]);

            $user->assignRole('user');

            $bonusStatus = 3;
            $this->welcomBonus($user, $bonusStatus);
            if (!empty($user->provider) && !empty($user->referred_by)) {
                $this->referralBonus($user->referred_by, $bonusStatus);
            }

            $email_template = EmailTemplate::where('key', 'user_welcome')->first();

            $filtered_message  = str_replace(['%SITE_TITLE%', '%SITE_URL%', '%NAME%', '%EMAIL%'], [SiteSetting()['website_title'], url('/'), $fisrt_name[0], $userSocial->getEmail()], $email_template->message);

            $email_data = array(
                'name' =>  $fisrt_name[0],
                'email' => $userSocial->getEmail(),
                'email_message' => $filtered_message,
                'subject' => $email_template->subject
            );

            Mail::send('emails.email_template', $email_data, function ($message) use ($email_data) {
                $message->to($email_data['email'], $email_data['name'])
                    ->subject($email_data['subject']);
            });
            Auth::login($user);
            Session::forget('refCode');
            Session::flash('welcome', 'welcome message');
            if (Session::has('prvUrl')) {
                return redirect(session('prvUrl'));
            } else {
                return redirect('/');
            }
        }
    }
}
