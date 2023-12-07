<?php

namespace App\Jobs;

use Exception;
use Carbon\Carbon;
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
use App\Models\Category;
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
        $storesChunks = Store::where('network_id', $this->network->id)->orderBy('id', 'DESC')->get()->chunk(2000);

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
                $cashbacks = json_decode($response, true);

                foreach ($cashbacks['response']['commissions'] as $cashback) {
                    $exitClick = ExitClick::where('id', $cashback['site_exit_click_id'])->first();
                    if (empty($exitClick)) {
                        continue;
                    }
                    $store = $exitClick->store;
                    $user = $exitClick->user;
                    $network = $exitClick->network;
                    $userCashback = UserCashback::where('exit_click_id', $exitClick->id)->where('user_id', $user->id)->first();

                    if ($cashback['status'] == "Pending") {
                        $status = CashbackStatus::where('status', 'pending')->first()->id;
                    } else if ($cashback['status'] == "Confirmed") {
                        $status = CashbackStatus::where('status', 'confirmed')->first()->id;
                    } else if ($cashback['status'] == "Failed") {
                        $status = CashbackStatus::where('status', 'failed')->first()->id;
                    } else if ($cashback['status'] == "Payable") {
                        $status = CashbackStatus::where('status', 'failed')->first()->id;
                    }

                    if (empty($userCashback)) {
                        $cashbackAmount = ($cashback['commission'] / 100) * $exitClick->current_cashback_percentage;
                        $newCashback = UserCashback::create([
                            'store_id' => $store->id,
                            'user_id' => $user->id,
                            'exit_click_id' => $exitClick->id,
                            'click_date' => Carbon::parse($cashback['sales_date'])->toDateTimeString(),
                            'event_date' => Carbon::parse($cashback['date_created'])->toDateTimeString(),
                            'network_commission' => $cashback['commission'],
                            'order_value' => round($cashback['order_value'], 2),
                            'amount' => round($cashbackAmount, 2),
                            'status' => $status,
                            'type' => 'cashback',
                            'is_api' => 'no'
                        ]);
                        CashbackStatusChange::create([
                            'user_cashback_id' => $newCashback->id,
                            'cashback_status_id' => $status
                        ]);

                        if ($cashback['status'] == "Pending" || $cashback['status'] == "Confirmed") {
                            //Push Notification & Email in case of confirmed or pending status of cashback
                            $userEmailTemplateKey = 'user_new_cashback_tracked';
                            $filterMessageVariables = ['{{STORE}}', '{{AMOUNT}}'];
                            $requestFilteredMessage = [$newCashback->store->name, number_format($newCashback->amount, 2)];

                            $data = [
                                'name' => $newCashback->user->first_name . ' ' . $newCashback->user->last_name,
                                'email' => $newCashback->user->email,
                                'subject' => null,
                                'message' => null
                            ];
                            SendEmailToUser::dispatch($userEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);
                            $deviceToken = optional($newCashback->user->devices()->whereType('web')->first())->fcm_token;
                            if ($deviceToken != null) {
                                $title = 'Cashback Tracked';
                                $message = 'We have tracked your cashback from ' . $newCashback->store->name;
                                $url = url('account/cashback');
                                $user = $newCashback->user()->get();

                                dispatch(new SendNotification($title, $message, $deviceToken, $url, $user));
                            }
                        } else {
                            //Push Notification and email in case of failed status
                            $userEmailTemplateKey = 'user_new_cashback_tracked';
                            $filterMessageVariables = ['{{STORE}}', '{{AMOUNT}}'];
                            $requestFilteredMessage = [$newCashback->store->name, number_format($newCashback->amount, 2)];

                            $data = [
                                'name' => $newCashback->user->first_name . ' ' . $newCashback->user->last_name,
                                'email' => $newCashback->user->email,
                                'subject' => null,
                                'message' => null
                            ];
                            SendEmailToUser::dispatch($userEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);
                            $deviceToken = optional($newCashback->user->devices()->whereType('web')->first())->fcm_token;
                            if ($deviceToken != null) {
                                $title = 'Cashback Tracked';
                                $message = 'We have tracked your cashback from ' . $newCashback->store->name;
                                $url = url('account/cashback');
                                $user = $newCashback->user()->get();

                                dispatch(new SendNotification($title, $message, $deviceToken, $url, $user));
                            }
                        }
                    } else {
                        $cashbackStatus = UserCashback::where('exit_click_id', $exitClick->id)->first()->status;
                        if ($cashbackStatus != $status && $cashbackStatus != CashbackStatus::where('status', 'failed')->first()->id) {
                            UserCashback::where('exit_click_id', $exitClick->id)->where('user_id', $user->id)->update(['status' => $status]);
                            CashbackStatusChange::create([
                                'user_cashback_id' => UserCashback::where('exit_click_id', $exitClick->id)->where('user_id', $user->id)->first()->id,
                                'cashback_status_id' => $status
                            ]);
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
                            $deviceToken = optional($userCashback->user->devices()->whereType('web')->first())->fcm_token;
                            if ($deviceToken != null) {
                                $title = 'Cashback Tracked';
                                $message = 'We have tracked your cashback from ' . $userCashback->store->name;
                                $url = url('account/cashback');
                                $user = $userCashback->user()->get();

                                dispatch(new SendNotification($title, $message, $deviceToken, $url, $user));
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
                                        'banner_upload' => $category['large_icon'],
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
                                    'banner_upload' => $category['large_icon'],
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
}
