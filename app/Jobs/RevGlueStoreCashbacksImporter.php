<?php

namespace App\Jobs;

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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($stores)
    {
        $this->stores = $stores;
        $this->siteSettings = SiteSetting::latest()->get()->pluck('value', 'type');
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
                "https://www.revglue.com/partner/stores_cashback/MTA3Mw==/json"
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

            foreach ($storeCashbacks['response']['stores'] as $key => $cashback) {
                $commissionType = array_key_exists('type', $cashback) && (strpos(strtolower($cashback['cashback_type']), 'percentage') !== false) ? 'percentage' : 'fixed';

                $saleCommission = array_key_exists('percentage', $cashback) && !empty($cashback['percentage']) ? $cashback['percentage'] : 0;

                $saleCommission = array_key_exists('cashback_value', $cashback) && !empty($cashback['cashback_value']) ? $cashback['cashback_value'] : $saleCommission;

                // Skip commission group in case of no sale commission
                if (empty($saleCommission)) continue;

                $storeCashback = StoreCashback::where([
                    'store_id' => $store->id,
                    'type' => $commissionType,
                    'sale_commission' => $saleCommission,
                ])->first();

                if (empty($storeCashback)) {
                    StoreCashback::create([
                        'store_id' => $cashback['rg_store_id'],
                        'type' => $commissionType,
                        'cashback_name' => null,
                        'image' => '#',
                        'click_url' =>  '#',
                        'sale_commission' => $saleCommission,
                        'currency' => null,
                        'detail' => $cashback['description'],
                        'network_detail' => null,
                        'default' => 1
                    ]);
                } else {
                    $storeCashback->update([
                        'detail' => $cashback['description'],
                        'network_detail' => null,
                    ]);
                }
            }
        }
    }
}
