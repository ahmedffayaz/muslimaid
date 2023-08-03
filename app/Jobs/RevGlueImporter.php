<?php

namespace App\Jobs;

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
        if ($this->importerSetting->import_stores == 1) $this->ImportStores();
    }

    /**
     * Import stores from RevGlue network.
     *
     * @return void
     */
    private function importStores()
    {
        $curl = curl_init();

        curl_setopt(
            $curl,
            CURLOPT_URL,
            "https://www.revglue.com/partner/cashback_stores/" . $this->siteSettings['revglue_api_key'] . "/json"
        );

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $curlResponse = curl_exec($curl);

        if (curl_errno($curl)) {
            flash()->error('Error: ' . curl_error($curl));
            curl_close($curl);

            return redirect()->route(getAdminPrefix() . '.stores.index');
        }

        curl_close($curl);

        $stores = json_decode($curlResponse, true);

        $dbStores = Store::whereNetworkId($this->network->id)
            ->whereIn('advertiser_id', array_column($stores['response']['stores'], 'rg_store_id'))
            ->pluck('advertiser_id')
            ->toArray();

        $storesInfo = DB::select("SHOW TABLE STATUS LIKE 'stores'");
        $nextPk = $storesInfo[0]->Auto_increment;

        $newStores = [];
        $newStoresLogos = [];
        $newStoresBanners = [];
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
                        'status' => $store['status'],
                        'status_description' => null,
                        'network_status' => null,
                    ];

                    $newStoresLogos[] = [
                        'store_id' => $nextPk + $key,
                        'title' => 'logo',
                        'image' => empty($store['store_icon_large']) ? (mt_rand(1, 20) . '.png') : $store['store_icon_large'],
                        'image_type' => 'store_logo',
                        'is_uploaded' => '',
                        'is_fake' => empty($store['image_url']) ? 1 : 0
                    ];

                    $newStoresBanners[] = [
                        'store_id' => $nextPk + $key,
                        'title' => 'Cover',
                        'image' => empty($store['store_banner_large']) ? (mt_rand(1, 20) . '.png') : $store['store_banner_large'],
                        'image_type' => 'store_logo',
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
                }
            } catch (Exception $e) {
                flash()->error('Error while running importer');
                return redirect()->route(getAdminPrefix() . '.stores.index');
            }
        }

        foreach ($newStores as $newStore) {
            $slug = Str::slug($newStore['slug']);
            $lastId = Store::orderBy('id', 'desc')->where('slug', $slug)->pluck('id')->first();
            $newStore['slug'] = isset($lastId) ? $newStore['slug'] . '-' . ($lastId + 1) : $newStore['slug'];
            Store::create($newStore);
        }

        StoreImage::insert($newStoresLogos);
        StoreImage::insert($newStoresBanners);
        DB::table('category_store')->insert($newStoreCategories);

        $this->importStoreCashback();
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
}
