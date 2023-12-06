<?php

namespace App\Jobs;

use App\Models\SiteSetting;
use App\Models\StoreCashback;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AwinStoreCashbacksImporter implements ShouldQueue
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
                "https://api.awin.com/publishers/{$this->siteSettings['awin_publisher_id']}/commissiongroups?advertiserId={$store->advertiser_id}"
            );

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer {$this->siteSettings['awin_authorization_token']}"
            ));

            $curlResponse = curl_exec($curl);

            if (curl_errno($curl)) {
                info('Error: ' . curl_error($curl));
                curl_close($curl);

                continue;
            }

            curl_close($curl);

            $commissionGroups = json_decode($curlResponse, true);

            // Skip in case of no commission group
            if (empty($commissionGroups)) continue;

            // Skip in case of empty commission groups data
            if (!array_key_exists('commissionGroups', $commissionGroups) || empty($commissionGroups['commissionGroups'])) continue;

            foreach ($commissionGroups['commissionGroups'] as $commissionGroup) {
                $commissionType = array_key_exists('type', $commissionGroup) && (strpos(strtolower($commissionGroup['type']), 'percent') !== false)
                    ? 'percentage'
                    : 'fixed';

                $saleCommission = array_key_exists('percentage', $commissionGroup) && !empty($commissionGroup['percentage'])
                    ? $commissionGroup['percentage']
                    : 0;

                $saleCommission = array_key_exists('amount', $commissionGroup) && !empty($commissionGroup['amount'])
                    ? $commissionGroup['amount']
                    : $saleCommission;

                // Skip commission group in case of no sale commission
                if (empty($saleCommission)) continue;

                $storeCashback = StoreCashback::where([
                    'store_id' => $store->id,
                    'type' => $commissionType,
                    'sale_commission' => $saleCommission,
                ])->first();

                if (empty($storeCashback)) {
                    StoreCashback::create([
                        'store_id' => $store->id,
                        'type' => $commissionType,
                        'cashback_name' => $commissionGroup['groupName'],
                        'image' => '#',
                        'click_url' =>  '#',
                        'sale_commission' => $saleCommission,
                        'currency' => array_key_exists('currency', $commissionGroup) ? $commissionGroup['currency'] : null,
                        'detail' => $commissionGroup['groupName'] . ' default',
                        'network_detail' => $commissionGroup['groupName'] . ' default',
                        'default' => 1
                    ]);
                } else {
                    $storeCashback->update([
                        'detail' => $commissionGroup['groupName'] . ' default',
                        'network_detail' => $commissionGroup['groupName'] . ' default',
                    ]);
                    setStoreDefaultCashback($storeCashback->store_id, false);
                }
            }
        }

        return;
    }
}
