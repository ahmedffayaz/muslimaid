<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Cashout;
use App\Models\ExitClick;
use App\Models\CashoutMeta;
use App\Models\SiteSetting;
use App\Models\UserCashback;
use App\Jobs\SendEmailToUser;
use Illuminate\Bus\Queueable;
use App\Jobs\SendEmailToAdmin;
use App\Jobs\SendNotification;
use App\Models\CashbackStatus;
use App\Models\CashbackStatusChange;
use Exception;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Log;

class RevGlueUserCashbacksImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 900;
    protected $chunk;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chunk)
    {
        $this->chunk = $chunk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            foreach ($$his->chunk as $rgCashback) {
                $exitClick = ExitClick::where('id', $rgCashback->site_exit_click_id)->first();
                if (!empty($exitClick)) {
                    $userCashback = UserCashback::where('exit_click_id', $exitClick->id)->where('user_id', $exitClick->user_id)->first();
                    $cashbackStatus = CashbackStatus::where('status', strtolower($rgCashback->status))->first();
                    if (!empty($userCashback) && $rgCashback->status != 'Pending' && !empty($cashbackStatus) && $cashbackStatus->status != 'pending') {
                        // Make user cashback status confirmed if it's pending and RG Cashback status is confirmed
                        if ($rgCashback->status == 'Confirmed' && $userCashback->statusMap->status == 'pending') {
                            UserCashback::where('id', $userCashback->id)->update(['status' => 3]);

                            CashbackStatusChange::create([
                                'user_cashback_id' => $userCashback->id,
                                'cashback_status_id' => $cashbackStatus->id
                            ]);

                            // Push Notification & Email in case of confirmed from pending status of cashback
                            $userEmailTemplateKey = 'user_new_cashback_tracked';
                            $filterMessageVariables = ['{{STORE}}', '{{AMOUNT}}'];
                            $requestFilteredMessage = [$userCashback->store->name, number_format($userCashback->amount, 2)];

                            $data = [
                                'name' => $userCashback->user->first_name . ' ' . $userCashback->user->last_name,
                                'email' => $userCashback->user->email,
                                'subject' => null,
                                'message' => null
                            ];
                            SendEmailToUser::dispatch($userEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);

                            // Notification data
                            $cashbackNotificationTitle = 'Cashback Received';
                            $userNotificationMessage = "You've received cashback from " . $userCashback->store->name;
                            $userCashbackUrl = url('account/cashback');

                            // Send notification to user
                            $deviceToken = $userCashback->user->devices()->whereType('web')->latest()->first();
                            !is_null($deviceToken) ? dispatch(new SendNotification($cashbackNotificationTitle, $userNotificationMessage, $deviceToken->fcm_token, $userCashbackUrl, $deviceToken->user())) : '';

                            // Get default appeal and save it cashout meta table
                            $preferredAppeal = $userCashback->user->appeal;

                            if (isset($preferredAppeal) && !empty($preferredAppeal)) {
                                $previousCashouts = $userCashback->user->cashouts()->where('status', 'paid')->count();

                                if (isset(SiteSetting()['min_cashout_amount']) && $previousCashouts == 0)
                                    $min = SiteSetting()['min_cashout_amount'];
                                else if (isset(SiteSetting()['next_cashout_amount']) && $previousCashouts > 0)
                                    $min = SiteSetting()['next_cashout_amount'];
                                else if ($previousCashouts == 0) $min = 1;
                                else $min = 2;

                                $userCashout = $userCashback->user->cashouts()->where('status', '!=', 'pending')->orWhere('status', '!=', 'processing')->orWhere('status', '!=', 'processing donation')->first();

                                $oldBalance = $userCashback->user->availableBalance(3);

                                if ($oldBalance > $min && isset($userCashout) && ($userCashout->status != 'pending' || $userCashout->status != 'processing' || $userCashout->status != 'processing donation')) {
                                    $userConfirmedCashback = UserCashback::where('id', $userCashback->id)->first();
                                    $cashout = Cashout::create([
                                        'user_id' => $userCashback->user->id,
                                        'amount' => $userConfirmedCashback->amount,
                                        'new_cashout' => '1',
                                        'payment_method' => 'appeal',
                                        'status' => 'donated'
                                    ]);

                                    CashoutMeta::create([
                                        'cashout_id' => $cashout->id,
                                        'type' => 'cashback_id',
                                        'value' => $userConfirmedCashback->id
                                    ]);

                                    CashoutMeta::create([
                                        'cashout_id' => $cashout->id,
                                        'type' => 'appeal_id',
                                        'value' => $preferredAppeal->id
                                    ]);

                                    $cashback = $cashout->user->cashbacks()->where('id', $userCashback->id)->first();
                                    $cashback->update(['status' => 7, 'cashout_id' => $cashout->id]);
                                    $cashback->statusHistory()->create([
                                        'cashback_status_id' => $cashback->status,
                                        'user_cashback_id' => $cashback->id
                                    ]);

                                    if ($userCashback->user->bonus && $userCashback->user->bonus->status == 'unpaid') {
                                        $userCashback->user->bonus->update([
                                            'status' => 'paid',
                                            'cashout_id' => $cashout->id
                                        ]);
                                    }

                                    //Push Notification & Email in case of donated from confirmed status of cashback
                                    $userDonationEmailTemplateKey = 'user_cashout_donation';
                                    $adminDonationEmailTemplateKey = 'admin_cashout_donation';
                                    $this->sendEmail($cashout, $userDonationEmailTemplateKey, $adminDonationEmailTemplateKey);

                                    // Notification data
                                    $notificationTitle = 'Cashback Donated';
                                    $userMessage = 'You have donated your cashback';

                                    // Send notification to user
                                    $cashbackWithdrawUrl = url('account/withdraw');
                                    $userDeviceToken = $userCashback->user->devices()->whereType('web')->latest()->first();
                                    !is_null($userDeviceToken) ? dispatch(new SendNotification($notificationTitle, $userMessage, $userDeviceToken->fcm_token, $cashbackWithdrawUrl, $userDeviceToken->user())) : '';

                                    // Send notification to admin
                                    $adminMessage = $userCashback->user()->first_name . ' ' . $userCashback->user()->last_name . ' cashback donated';
                                    $adminCashoutUrl = url(getAdminPrefix() . '/cashouts') . '/' . $cashout->id;
                                    $admin = User::first();
                                    $adminDeviceToken = $admin->devices()->where('type', 'web')->latest()->first();
                                    !is_null($adminDeviceToken) ? dispatch(new SendNotification($notificationTitle, $adminMessage, $adminDeviceToken->fcm_token, $adminCashoutUrl, $admin)) : '';
                                }
                            }
                        }

                        // Change status donate from confirmed if revglue cashback status is confirmed and donate cashback to default appeal
                        if ($rgCashback->status == 'Confirmed' && $userCashback->statusMap->status == 'confirmed') {
                            // Get default appeal and save it cashout meta table
                            $preferredAppeal = $userCashback->user->appeal;

                            if (isset($preferredAppeal) && !empty($preferredAppeal)) {
                                $previousCashouts = $userCashback->user->cashouts()->where('status', 'paid')->count();

                                if (isset(SiteSetting()['min_cashout_amount']) && $previousCashouts == 0)
                                    $min = SiteSetting()['min_cashout_amount'];
                                else if (isset(SiteSetting()['next_cashout_amount']) && $previousCashouts > 0)
                                    $min = SiteSetting()['next_cashout_amount'];
                                else if ($previousCashouts == 0) $min = 1;
                                else $min = 2;

                                $userCashout = $userCashback->user->cashouts()->where('status', '!=', 'pending')->orWhere('status', '!=', 'processing')->orWhere('status', '!=', 'processing donation')->first();

                                $oldBalance = $userCashback->user->availableBalance(3);

                                if ($oldBalance > $min && isset($userCashout) && ($userCashout->status != 'pending' || $userCashout->status != 'processing' || $userCashout->status != 'processing donation')) {
                                    $userConfirmedCashback = UserCashback::where('id', $userCashback->id)->first();
                                    $cashout = Cashout::create([
                                        'user_id' => $userCashback->user->id,
                                        'amount' => $userCashback->amount,
                                        'new_cashout' => '1',
                                        'payment_method' => 'appeal',
                                        'status' => 'donated'
                                    ]);

                                    CashoutMeta::create([
                                        'cashout_id' => $cashout->id,
                                        'type' => 'cashback_id',
                                        'value' => $userCashback->id
                                    ]);

                                    CashoutMeta::create([
                                        'cashout_id' => $cashout->id,
                                        'type' => 'appeal_id',
                                        'value' => $preferredAppeal->id
                                    ]);

                                    $cashback = $cashout->user->cashbacks()->where('id', $userCashback->id)->first();
                                    $cashback->update(['status' => 7, 'cashout_id' => $cashout->id]);
                                    $cashback->statusHistory()->create([
                                        'cashback_status_id' => $cashback->status,
                                        'user_cashback_id' => $cashback->id
                                    ]);

                                    if ($userCashback->user->bonus && $userCashback->user->bonus->status == 'unpaid') {
                                        $userCashback->user->bonus->update([
                                            'status' => 'paid',
                                            'cashout_id' => $cashout->id
                                        ]);
                                    }

                                    //Push Notification & Email in case of donated from confirmed status of cashback
                                    $userDonationEmailTemplateKey = 'user_cashout_donation';
                                    $adminDonationEmailTemplateKey = 'admin_cashout_donation';
                                    $this->sendEmail($cashout, $userDonationEmailTemplateKey, $adminDonationEmailTemplateKey);

                                    // Notification data
                                    $notificationTitle = 'Cashback Donated';
                                    $userMessage = 'You have donated your cashback';

                                    // Send notification to user
                                    $cashbackWithdrawUrl = url('account/withdraw');
                                    $userDeviceToken = $userCashback->user->devices()->whereType('web')->latest()->first();
                                    !is_null($userDeviceToken) ? dispatch(new SendNotification($notificationTitle, $userMessage, $userDeviceToken->fcm_token, $cashbackWithdrawUrl, $userDeviceToken->user)) : '';

                                    // Send notification to admin
                                    $adminMessage = $userCashback->user->first_name . ' ' . $userCashback->user->last_name . ' cashback donated';
                                    $adminCashoutUrl = url(getAdminPrefix() . '/cashoutes') . '/' . $cashout->id;
                                    $admin = User::first();
                                    $adminDeviceToken = $admin->devices()->where('type', 'web')->latest()->first();
                                    !is_null($adminDeviceToken) ? dispatch(new SendNotification($notificationTitle, $adminMessage, $adminDeviceToken->fcm_token, $adminCashoutUrl, $admin)) : '';
                                }
                            }
                        }
                    } else {
                        // Save new cashback if cashback not exist
                        if ($rgCashback->status == 'Pending' && empty($userCashback)) {
                            $customCashbackPercentage = $exitClick->store->custom_cashback_percentage;

                            if ($customCashbackPercentage)
                                $cashback_percent = $customCashbackPercentage;
                            else
                                $cashback_percent = SiteSetting::where('type', 'cashback_percentage')->first()->value;

                            $commission = UserCashback::create([
                                'store_id' => $exitClick->store_id,
                                'user_id'  => $exitClick->user_id ?? 0,
                                'exit_click_id' => $exitClick->id,
                                'amount' => round(($rgCashback->commission / 100) * $cashback_percent, 3),
                                'network_commission' => round($rgCashback->commission, 3),
                                'order_value' => isset($rgCashback->order_value) ? round($rgCashback->order_value) : null,
                                'status' => $cashbackStatus->id,
                                'event_date' => $rgCashback->date_created,
                                'click_date' => $rgCashback->date_created,
                                'is_api' => 'yes',
                            ]);

                            CashbackStatusChange::create([
                                'user_cashback_id' => $commission->id,
                                'cashback_status_id' => $commission->status
                            ]);

                            if ($exitClick->user_id != 0) {
                                $deviceToken = $exitClick->user->devices()->whereType('web')->latest()->first();
                                $title = 'Cashback Received';
                                $message = "You've received cashback from " . $exitClick->store->name;
                                $url = url('account/cashback');
                                $user = $exitClick->user;

                                !is_null($deviceToken) ? dispatch(new SendNotification($title, $message, $deviceToken->fcm_token, $url, $user)) : '';
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Log::error('Get error while import chunk user cashbacks from RevGlue: ' . $e->getMessage());
        }
    }

    function sendEmail($cashout, $userEmailTemplateKey, $adminEmailTemplateKey)
    {
        $filterMessageVariables = ['{{AMOUNT}}', '{{METHOD}}'];
        $method = '';

        if ($cashout->payment_method == 'appeal') {
            $method = 'Appeal';
        }

        $requestFilteredMessage = [number_format($cashout->amount, 2), $method];
        $data = [
            'name' => $cashout->user->first_name . ' ' . $cashout->user->last_name,
            'email' => $cashout->user->email,
            'subject' => null,
            'message' => null
        ];

        SendEmailToUser::dispatch($userEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);
        SendEmailToAdmin::dispatch($adminEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);
    }
}
