<?php

namespace App\Jobs;

use Exception;
use App\Models\Store;
use App\Models\Network;
use App\Models\ExitClick;
use App\Models\StoreImage;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use App\Models\UserCashback;
use Illuminate\Bus\Queueable;
use App\Models\CashbackStatus;
use App\Models\ImporterSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CashbackStatusChange;
use App\Models\Cashout;
use App\Models\CashoutMeta;
use App\Models\Category;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RevGlueImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $network;
    protected $siteSettings;
    protected $importerSetting;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->network = Network::whereName('RevGlue')->first();
        $this->siteSettings = SiteSetting::latest()->get()->pluck('value', 'type');
        $this->importerSetting = ImporterSetting::whereNetworkId($this->network->id)->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->importerSetting->import_stores == 1) $this->importStores();
        if ($this->importerSetting->import_cashbacks == 1) $this->importUserCashbacks();
        if ($this->importerSetting->import_categories == 1) $this->importCategories();
    }

    /**
     * Import stores from RevGlue network.
     *
     * @return void
     */
    private function importStores()
    {
        try {
            $url = "https://www.revglue.com/partner/cashback_stores/" . $this->siteSettings['revglue_api_key'] . "/json";

            $response = Http::get($url);

            if ($response->successful()) {
                $stores = json_decode($response, true);

                $dbStores = Store::whereNetworkId($this->network->id)
                    ->whereIn('advertiser_id', array_column($stores['response']['stores'], 'rg_store_id'))
                    ->pluck('advertiser_id')
                    ->toArray();

                $storesInfo = DB::select("SHOW TABLE STATUS LIKE 'stores'");
                $nextPk = $storesInfo[0]->Auto_increment;

                $newStores = [];
                $newStoresLogosSmall = [];
                $newStoresLogosLarge = [];
                $newStoresBannersSmall = [];
                $newStoresBannersLarge = [];
                $newStoreCategories = [];

                foreach ($stores['response']['stores'] as $key => $store) {
                    try {
                        if (!in_array($store['rg_store_id'], $dbStores)) {
                            $newStores[] = [
                                'network_id' => $this->network->id,
                                'advertiser_id' => $store['rg_store_id'],
                                'name' => $store['store_title'],
                                'description' => $store['store_description'],
                                'slug' => Str::slug($store['store_title']),
                                'tracking_url' => rtrim($store['deeplink'], '/'),
                                'store_url' => $store['website_url'],
                                'status' => 'pending review',
                                'status_description' => null,
                                'network_status' => null,
                            ];

                            $newStoresLogosSmall[] = [
                                'store_id' => $nextPk + $key,
                                'title' => 'logo',
                                'image' => empty($store['image_url']) ? (mt_rand(1, 20) . '.png') : $store['image_url'],
                                'image_type' => 'store_logo_small',
                                'is_uploaded' => '',
                                'is_fake' => empty($store['image_url']) ? 1 : 0
                            ];

                            $newStoresLogosLarge[] = [
                                'store_id' => $nextPk + $key,
                                'title' => 'large logo',
                                'image' => empty($store['store_icon_large']) ? (mt_rand(1, 20) . '.png') : $store['store_icon_large'],
                                'image_type' => 'store_logo_large',
                                'is_uploaded' => '',
                                'is_fake' => empty($store['store_icon_large']) ? 1 : 0
                            ];

                            $newStoresBannersSmall[] = [
                                'store_id' => $nextPk + $key,
                                'title' => 'Cover',
                                'image' => empty($store['store_banner_small']) ? (mt_rand(1, 20) . '.png') : $store['store_banner_small'],
                                'image_type' => 'store_banner_small',
                                'is_uploaded' => '',
                                'is_fake' => empty($store['store_banner_small']) ? 1 : 0
                            ];

                            $newStoresBannersLarge[] = [
                                'store_id' => $nextPk + $key,
                                'title' => 'large cover',
                                'image' => empty($store['store_banner_large']) ? (mt_rand(1, 20) . '.png') : $store['store_banner_large'],
                                'image_type' => 'store_banner_large',
                                'is_uploaded' => '',
                                'is_fake' => empty($store['store_banner_large']) ? 1 : 0
                            ];

                            if (!empty($store['cashback_category_ids'])) {
                                $storeCategories = $store['cashback_category_ids'];

                                $newStoreCategoriesId = explode(',', $storeCategories);

                                if ($newStoreCategoriesId) {
                                    foreach ($newStoreCategoriesId as $newStoreCategoryId) {
                                        $newStoreCategories[] = [
                                            'store_id' => $nextPk + $key,
                                            'category_id' => $newStoreCategoryId,
                                        ];
                                    }
                                }
                            }
                        } else {
                            foreach ($dbStores as $dbStore) {
                                if ($store['status'] != 'active') {
                                    $dbStore->where('advertiser_id', $store['rg_store_id'])
                                        ->where('network_id', $this->network->id)
                                        ->update(['status' => 'closed']);
                                }
                            }
                        }
                    } catch (Exception $e) {
                        Log::error($e->getMessage());
                    }
                }

                foreach ($newStores as $newStore) {
                    $slug = Str::slug($newStore['slug']);
                    $lastId = Store::orderBy('id', 'desc')->where('slug', $slug)->pluck('id')->first();
                    $newStore['slug'] = isset($lastId) ? $newStore['slug'] . '-' . ($lastId + 1) : $newStore['slug'];
                    Store::create($newStore);
                }

                StoreImage::insert($newStoresLogosSmall);
                StoreImage::insert($newStoresLogosLarge);
                StoreImage::insert($newStoresBannersSmall);
                StoreImage::insert($newStoresBannersLarge);
                DB::table('category_store')->insert($newStoreCategories);

                $this->importStoreCashback();
            } else {
                Log::error('Get error while import stores from RevGlue');
            }
        } catch (Exception $e) {
            Log::error('Get error while import stores from RevGlue: ' . $e->getMessage());
        }
    }

    /**
     * Import store cashbacks RevGlue network.
     *
     * @return vic
     */
    private function importStoreCashback()
    {
        // We have API call limit for '20' calls per minute (for safe side make it '15'), so we divide and conquer
        $storesChunks = Store::where('network_id', $this->network->id)->orderBy('id', 'DESC')->get()->chunk(15);

        foreach ($storesChunks as $key => $storesChunk) {
            RevGlueStoreCashbacksImporter::dispatch($storesChunk);
        }
    }

    /**
     * For importing user cashbacks
     */
    private function importUserCashbacks()
    {
        try {
            $url = "https://www.revglue.com/partner/get_revembed_commission/" . $this->siteSettings['revglue_api_key'] . "/UE4Wr8O9Nl7BURIBGIVY8HSJhwNi7RXYiMPc06puPVkoh9Y6xC";
            $response = Http::get($url);
            if ($response->successful()) {
                $rgCashbacks = json_decode($response, true);

                foreach($rgCashbacks['response']['commissions'] as $rgCashback) {
                    $exitClick = ExitClick::where('id', $rgCashback['site_exit_click_id'])->first();
                    if (!empty($exitClick)) {
                        $userCashback = UserCashback::where('exit_click_id', $exitClick->id)->where('user_id', $exitClick->user_id)->first();
                        $cashbackStatus = CashbackStatus::where('status', strtolower($rgCashback['status']))->first();
                        if (!empty($userCashback) && $rgCashback['status'] != 'Pending' && !empty($cashbackStatus) && $cashbackStatus->status != 'pending') {
                            // Make user cashback status confirmed if it's pending and RG Cashback status is confirmed
                            if ($rgCashback['status'] == 'Confirmed' && $userCashback->statusMap->status == 'pending') {
                                UserCashback::where('id', $userCashback->id)->update([
                                    'status' => 3
                                ]);

                                CashbackStatusChange::create([
                                    'user_cashback_id' => $userCashback->id,
                                    'cashback_status_id' => $cashbackStatus->id
                                ]);

                                //Push Notification & Email in case of confirmed from pending status of cashback
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
                            if ($rgCashback['status'] == 'Confirmed' && $userCashback->statusMap->status == 'confirmed') {
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
                            if ($rgCashback['status'] == 'Pending' && empty($userCashback)) {
                                $customCashbackPercentage = $exitClick->store->custom_cashback_percentage;

                                if ($customCashbackPercentage)
                                    $cashback_percent = $customCashbackPercentage;
                                else
                                    $cashback_percent = SiteSetting::where('type', 'cashback_percentage')->first()->value;

                                $commission = UserCashback::create([
                                    'store_id' => $exitClick->store_id,
                                    'user_id'  => $exitClick->user_id ?? 0,
                                    'exit_click_id' => $exitClick->id,
                                    'amount' => round(($rgCashback['commission'] / 100) * $cashback_percent, 3),
                                    'network_commission' => round($rgCashback['commission'], 3),
                                    'order_value' => isset($rgCashback['order_value']) ? round($rgCashback['order_value']) : null,
                                    'status' => $cashbackStatus->id,
                                    'event_date' => $rgCashback['date_created'],
                                    'click_date' => $rgCashback['date_created'],
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
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * For importing categories
     */
    private function importCategories()
    {
        try {
            $url = "https://www.revglue.com/partner/cashback_categories/" . $this->siteSettings['revglue_api_key'] . "/json";
            $response = Http::get($url);

            if ($response->successful()) {
                $categories = $response['response']['categories'];

                if (!empty($categories)) {
                    $cashbackCategoryIds = [];

                    foreach ($categories as $category) {
                        if (isset($category['cashback_category_id'])) {
                            $cashbackCategoryIds[] = $category['cashback_category_id'];
                        }
                    }

                    if (!empty($cashbackCategoryIds)) {
                        $dbCategories = Category::where('network_id', $this->network->id)
                            ->whereIn('advertiser_id', $cashbackCategoryIds)
                            ->pluck('advertiser_id')
                            ->toArray();

                        foreach ($categories as $category) {
                            $rgCategoryId = $category['cashback_category_id'];
                            // Find the parent_id based on advertiser_id
                            $parentCategoryId = Category::where('network_id', $this->network->id)
                                ->where('advertiser_id', $category['parent_category_id'])
                                ->value('id');

                            // If parent ID is not found, set it to 0
                            $parentCategoryId = $parentCategoryId ?? 0;

                            if (!empty($dbCategories) && in_array($rgCategoryId, $dbCategories)) {
                                Category::where('network_id', $this->network->id)
                                    ->where('advertiser_id', $category['cashback_category_id'])
                                    ->update([
                                        'parent_id' => $parentCategoryId, // Use the retrieved parent_id
                                        'name' => $category['cashback_category_title'],
                                        'description' => $category['description'],
                                        'logo_type' => 'link',
                                        'logo_link' => $category['small_icon'],
                                        'banner_type' => 'link',
                                        'banner_link' => $category['banner'],
                                        'status' => $category['status'] == 'active' ? 1 : 0
                                    ]);
                            } else {
                                Category::create([
                                    'network_id' => $this->network->id,
                                    'advertiser_id' => $category['cashback_category_id'],
                                    'parent_id' => $parentCategoryId, // Use the retrieved parent_id
                                    'name' => $category['cashback_category_title'],
                                    'slug' => \Illuminate\Support\Str::slug($category['cashback_category_title']),
                                    'description' => $category['description'],
                                    'sort' => 1,
                                    'logo_type' => 'link',
                                    'logo_link' => $category['small_icon'],
                                    'banner_type' => 'link',
                                    'banner_link' => $category['banner'],
                                    'status' => $category['status'] == 'active' ? 1 : 0,
                                    'visibility' => 'visible',
                                    'is_map_enable' => 0
                                ]);
                            }
                        }
                    }
                }
            } else {
                Log::error('Get Error while import categories from RevGlue');
            }
        } catch (Exception $e) {
            Log::error('Get Error while import categories from RevGlue: ' . $e->getMessage());
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
