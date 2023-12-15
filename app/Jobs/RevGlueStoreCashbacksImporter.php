<?php

namespace App\Jobs;

use App\Models\Network;
use App\Models\SiteSetting;
use App\Models\StoreCashback;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RevGlueStoreCashbacksImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $stores;
    private $siteSettings;
    private $network;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($stores)
    {
        $this->stores = $stores;
        $this->siteSettings = SiteSetting::latest()->get()->pluck('value', 'type');
        $this->network = Network::whereName('RevGlue')->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->stores as $store) {
            // Skip store if the store's cashback has been set to overridden by admin
            if ($store->override_cashback) continue;

            $curl = curl_init();

            curl_setopt(
                $curl,
                CURLOPT_URL,
                "https://www.revglue.com/partner/stores_cashback/" . $this->siteSettings['revglue_api_key'] . "/json"
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

            $storeCashbacks = json_decode($curlResponse, true);

            // Skip in case of no commission group
            if (empty($storeCashbacks)) continue;

            // Skip in case of empty commission groups data
            if (!array_key_exists('response', $storeCashbacks) || empty($storeCashbacks['response']['stores'])) continue;

            foreach ($storeCashbacks['response']['stores'] as $cashback) {
                if ($store->advertiser_id != $cashback['rg_store_id']) continue;
                StoreCashback::updateOrCreate([
                    'advertiser_id' => $cashback['store_cashback_id'],
                    'network_id' => $this->network->id,
                    'store_id' => $store->id
                ], [
                    'type' => $cashback['cashback_type'],
                    'cashback_name' => null,
                    'image' => '#',
                    'click_url' =>  '#',
                    'sale_commission' => $cashback['cashback_value'],
                    'currency' => null,
                    'detail' => $cashback['description'],
                    'network_detail' => null
                ]);
                setStoreDefaultCashback($store->id, false);
            }
        }
    }
}
