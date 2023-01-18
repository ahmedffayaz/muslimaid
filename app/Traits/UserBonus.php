<?php

namespace App\Traits;

use App\Models\CashbackStatusChange;
use App\Models\UserCashback;

/*
|
| This trait will be used for any bonus we sent to clients.
|
*/

trait UserBonus
{
    protected function welcomBonus($user, $status)
    {
        $welcomeBonus = array_key_exists('welcome_bonus', SiteSetting()->toArray()) ? (SiteSetting()['welcome_bonus'] != NULL ? SiteSetting()['welcome_bonus'] : 0) : 0;

        if ($welcomeBonus != 0) {
            $userCashback = UserCashback::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'amount' => $welcomeBonus,
                'status' => $status,
                'type' => 'welcome_bonus'
            ]);

            CashbackStatusChange::create([
                'user_cashback_id' => $userCashback->id,
                'cashback_status_id' => $userCashback->status
            ]);
        }
    }

    protected function referralBonus($user, $status)
    {
        $referralBonus = array_key_exists('referral_bonus', SiteSetting()->toArray()) ? (SiteSetting()['referral_bonus'] != NULL ? SiteSetting()['referral_bonus'] : 0) : 0;

        if ($referralBonus != 0) {
            $userCashback = UserCashback::create([
                'user_id' => $user,
                'amount' => $referralBonus,
                'status' => $status,
                'type' => 'referral_bonus'
            ]);

            CashbackStatusChange::create([
                'user_cashback_id' => $userCashback->id,
                'cashback_status_id' => $userCashback->status
            ]);
        }
    }
}
