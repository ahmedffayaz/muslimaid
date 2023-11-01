<?php

namespace App\Jobs;

use App\Models\CashbackStatus;
use App\Models\ImporterSetting;
use App\Models\Network;
use App\Models\SiteSetting;
use App\Models\Store;
use App\Models\StoreImage;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\ExitClick;
use App\Models\UserCashback;
use Carbon\Carbon;
use Illuminate\Support\Str;

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
        //if ($this->importerSetting->import_stores == 1) $this->importStores();
        if($this->importerSetting->import_cashbacks == 1) $this->importUserCashbacks();
    }

    /**
     * Import stores from RevGlue network.
     *
     * @return void
     */
    private function importStores()
    {
        // $curl = curl_init();

        // curl_setopt(
        //     $curl,
        //     CURLOPT_URL,
        //     "https://www.revglue.com/partner/cashback_stores/" . $this->siteSettings['revglue_api_key'] . "/json"
        // );

        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        // $curlResponse = curl_exec($curl);

        // if (curl_errno($curl)) {
        //     flash()->error('Error: ' . curl_error($curl));
        //     curl_close($curl);

        //     return redirect()->route(getAdminPrefix() . '.stores.index');
        // }

        // curl_close($curl);

        // $stores = json_decode($curlResponse, true);

        // $dbStores = Store::whereNetworkId($this->network->id)
        //     ->whereIn('advertiser_id', array_column($stores['response']['stores'], 'rg_store_id'))
        //     ->pluck('advertiser_id')
        //     ->toArray();

        // $storesInfo = DB::select("SHOW TABLE STATUS LIKE 'stores'");
        // $nextPk = $storesInfo[0]->Auto_increment;

        // $newStores = [];
        // $newStoresLogosSmall = [];
        // $newStoresLogosLarge = [];
        // $newStoresBannersSmall = [];
        // $newStoresBannersLarge = [];
        // $newStoreCategories = [];

        // foreach ($stores['response']['stores'] as $key => $store) {
        //     try {
        //         if (!in_array($store['rg_store_id'], $dbStores)) {
        //             $newStores[] = [
        //                 'network_id' => $this->network->id,
        //                 'advertiser_id' => $store['rg_store_id'],
        //                 'name' => $store['store_title'],
        //                 'description' => $store['store_description'],
        //                 'slug' => Str::slug($store['store_title']),
        //                 'tracking_url' => rtrim($store['deeplink'], '/'),
        //                 'store_url' => $store['website_url'],
        //                 'status' => $store['status'],
        //                 'status_description' => null,
        //                 'network_status' => null,
        //             ];

        //             $newStoresLogosSmall[] = [
        //                 'store_id' => $nextPk + $key,
        //                 'title' => 'logo',
        //                 'image' => empty($store['image_url']) ? (mt_rand(1, 20) . '.png') : $store['image_url'],
        //                 'image_type' => 'store_logo_small',
        //                 'is_uploaded' => '',
        //                 'is_fake' => empty($store['image_url']) ? 1 : 0
        //             ];

        //             $newStoresLogosLarge[] = [
        //                 'store_id' => $nextPk + $key,
        //                 'title' => 'large logo',
        //                 'image' => empty($store['store_icon_large']) ? (mt_rand(1, 20) . '.png') : $store['store_icon_large'],
        //                 'image_type' => 'store_logo_large',
        //                 'is_uploaded' => '',
        //                 'is_fake' => empty($store['store_icon_large']) ? 1 : 0
        //             ];

        //             $newStoresBannersSmall[] = [
        //                 'store_id' => $nextPk + $key,
        //                 'title' => 'Cover',
        //                 'image' => empty($store['store_banner_small']) ? (mt_rand(1, 20) . '.png') : $store['store_banner_small'],
        //                 'image_type' => 'store_banner_small',
        //                 'is_uploaded' => '',
        //                 'is_fake' => empty($store['store_banner_small']) ? 1 : 0
        //             ];

        //             $newStoresBannersLarge[] = [
        //                 'store_id' => $nextPk + $key,
        //                 'title' => 'large cover',
        //                 'image' => empty($store['store_banner_large']) ? (mt_rand(1, 20) . '.png') : $store['store_banner_large'],
        //                 'image_type' => 'store_banner_large',
        //                 'is_uploaded' => '',
        //                 'is_fake' => empty($store['store_banner_large']) ? 1 : 0
        //             ];

        //             if (!empty($store['cashback_category_ids'])) {
        //                 $storeCategories = $store['cashback_category_ids'];

        //                 $newStoreCategoriesId = explode(',', $storeCategories);

        //                 if ($newStoreCategoriesId) {
        //                     foreach ($newStoreCategoriesId as $newStoreCategoryId) {
        //                         $newStoreCategories[] = [
        //                             'store_id' => $nextPk + $key,
        //                             'category_id' => $newStoreCategoryId,
        //                         ];
        //                     }
        //                 }
        //             }
        //         }
        //     } catch (Exception $e) {
        //         Log::error($e->getMessage());
        //     }
        // }

        // foreach ($newStores as $newStore) {
        //     $slug = Str::slug($newStore['slug']);
        //     $lastId = Store::orderBy('id', 'desc')->where('slug', $slug)->pluck('id')->first();
        //     $newStore['slug'] = isset($lastId) ? $newStore['slug'] . '-' . ($lastId + 1) : $newStore['slug'];
        //     Store::create($newStore);
        // }

        // StoreImage::insert($newStoresLogosSmall);
        // StoreImage::insert($newStoresLogosLarge);
        // StoreImage::insert($newStoresBannersSmall);
        // StoreImage::insert($newStoresBannersLarge);
        // DB::table('category_store')->insert($newStoreCategories);

        // $this->importStoreCashback();
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
    private function importUserCashbacks(){
            $url = "https://www.revglue.com/partner/get_revembed_commission/MTA3Mw==/UE4Wr8O9Nl7BURIBGIVY8HSJhwNi7RXYiMPc06puPVkoh9Y6xC";
            $response = Http::get($url);
            if($response->successful()){
                $cashbacks = json_decode($response, true);

                
                foreach($cashbacks['response']['commissions'] as $cashback){
                    $exitClick = ExitClick::where('id', $cashback['site_exit_click_id'])->first();
                    $store = $exitClick->store;
                    $user = $exitClick->user;
                    $network = $exitClick->network;
                    $userCashback = UserCashback::where('exit_click_id', $exitClick->id)->where('user_id', $user->id)->first();
                    
                    if(empty($userCashback)){
                        $cashbackAmount = ($cashback['commission'] / 100) * $exitClick->current_cashback_percentage;
                        if($cashback['status'] == "Pending"){
                            $status = CashbackStatus::where('status', 'pending')->first()->id;
                        } else if ($cashback['status'] == "Confirmed") {
                            $status = CashbackStatus::where('status', 'confirmed')->first()->id;
                        } else if ($cashback['status'] == "Payable") {
                            $status = CashbackStatus::where('status', 'processing')->first()->id;
                        } else if ($cashback['status'] == "Donated") {
                            $status = CashbackStatus::where('status', 'donated')->first()->id;
                        }

                        UserCashback::create([
                            'store_id' => $store->id,
                            'user_id' => $user->id,
                            'exit_click_id' => $exitClick->id,
                            'click_date' => Carbon::parse($cashback['sales_data'])->toDateTimeString(),
                            'event_date' => Carbon::parse($cashback['date_created'])->toDateTimeString(),
                            'network_commission' => $cashback['commission'],
                            'order_value' => round($cashback['order_value'], 2),
                            'amount' => round($cashbackAmount, 2),
                            'status' => $status,
                            'type' => 'cashback',
                            'is_api' => 'no'
                        ]);
                    }

                }
            }
    }
}
