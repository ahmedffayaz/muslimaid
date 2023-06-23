<?php

namespace App\Jobs;

use Exception;
use App\Models\Store;
use App\Models\Network;
use App\Models\StoreImage;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Models\ImporterSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ImpactImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $network;
    private $siteSettings;
    private $importerSetting;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->network = Network::where('name', 'like', 'Impact')->first();
        $this->importerSetting = ImporterSetting::where('network_id', $this->network->id)->first();
        $this->siteSettings = SiteSetting::latest()->get()->pluck('value', 'type');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->importerSetting->import_stores == 1) $this->importStores();
    }

    /**
     * Import stores from Impact network.
     *
     * @return void
     */
    private function importStores()
    {
        $curl = curl_init();

        // Replace with your actual API endpoint
        $apiEndpoint = 'https://api.impact.com/Mediapartners/' . $this->siteSettings['impact_account_sid'] . '/Stores';

        curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_USERPWD, $this->siteSettings['impact_account_sid'] . ':' . $this->siteSettings['impact_authorization_token']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Accept: application/json"
        ));

        $curlResponse = curl_exec($curl);

        if (curl_errno($curl)) {
            flash()->error('Error: ' . curl_error($curl));
            curl_close($curl);

            return redirect()->route(getAdminPrefix() . '.stores.index');
        }

        curl_close($curl);

        $stores = json_decode($curlResponse, true);

        $dbStores = Store::whereNetworkId($this->network->id)
            ->whereIn('advertiser_id', array_column($stores, 'Id'))
            ->pluck('advertiser_id')
            ->toArray();

        $storesInfo = DB::select("SHOW TABLE STATUS LIKE 'stores'");
        $nextPk = $storesInfo[0]->Auto_increment;

        $newStores = [];
        $newStoresImages = [];

        foreach ($stores['Stores'] as $key => $store) {
            try {
                if (!in_array($store['Id'], $dbStores)) {
                    $newStores[] = [
                        'network_id' => $this->network->id,
                        'advertiser_id' => $store['Id'],
                        'name' => $store['Name'],
                        'description' => null,
                        'slug' => Str::slug($store['Name']),
                        'tracking_url' => $store['Uri'],
                        'store_url' => safeParseUrl($store['StoreUri']),
                        'status' => 'pending',
                        'status_description' => null,
                        'network_status' => null,
                    ];

                    $newStoresImages[] = [
                        'store_id' => $nextPk + $key,
                        'title' => 'logo',
                        'image' => empty($store['StoreAvatar']) ? (mt_rand(1, 20) . '.png') : $store['StoreAvatar'],
                        'image_type' => 'store_logo',
                        'is_uploaded' => 1,
                        'is_fake' => empty($store['StoreAvatar']) ? 1 : 0
                    ];

                    if (!empty($store['StoreBanner'])) {
                        $newStoresImages[] = [
                            'store_id' => $nextPk + $key,
                            'title' => 'cover',
                            'image' => empty($store['StoreBanner']) ? (mt_rand(1, 20) . '.png') : $store['StoreBanner'],
                            'image_type' => 'store_logo',
                            'is_uploaded' => 1,
                            'is_fake' => empty($store['StoreBanner']) ? 1 : 0
                        ];
                    }
                }
            } catch (Exception $e) {
                flash()->error('Error while running importer');
                return redirect()->route(getAdminPrefix() . '.stores.index');
            }
        }

        Store::insert($newStores);
        StoreImage::insert($newStoresImages);
    }
}
