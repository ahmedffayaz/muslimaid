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
use App\Models\StoreCashback;
use Illuminate\Bus\Queueable;
use App\Models\ImporterSetting;
use Illuminate\Support\Facades\DB;
use App\Models\CashbackStatusChange;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AwinImporter implements ShouldQueue
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
        $this->network = Network::where('name', 'like', 'Awin')->first();
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
     * Import stores from Awin network.
     *
     * @return void
     */
    private function importStores()
    {
        $curl = curl_init();

        curl_setopt(
            $curl,
            CURLOPT_URL,
            "https://api.awin.com/publishers/{$this->siteSettings['awin_publisher_id']}/programmes?countryCode=GB&relationship=joined"
        );

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer {$this->siteSettings['awin_authorization_token']}"
        ));

        $curlResponse = curl_exec($curl);

        if (curl_errno($curl)) {
            flash()->error('Error: ' . curl_error($curl));
            curl_close($curl);

            return redirect()->route('admin.stores.index');
        }

        curl_close($curl);

        $stores = json_decode($curlResponse, true);

        $dbStores = Store::whereNetworkId($this->network->id)
            ->whereIn('advertiser_id', array_column($stores, 'id'))
            ->pluck('advertiser_id')
            ->toArray();

        $storesInfo = DB::select("SHOW TABLE STATUS LIKE 'stores'");
        $nextPk = $storesInfo[0]->Auto_increment;

        $newStores = [];
        $newStoresImages = [];

        foreach ($stores as $key => $store) {
            try {
                if (!in_array($store['id'], $dbStores)) {
                    $newStores[] = [
                        'network_id' => $this->network->id,
                        'advertiser_id' => $store['id'],
                        'name' => $store['name'],
                        'description' => $store['description'],
                        'slug' => Str::slug($store['name']),
                        'tracking_url' => $store['clickThroughUrl'],
                        'store_url' => safeParseUrl($store['displayUrl']),
                        'status' => 'pending',
                        'status_description' => null,
                        'network_status' => $store['status'],
                    ];

                    $newStoresImages[] = [
                        'store_id' => $nextPk + $key,
                        'title' => 'logo',
                        'image' => empty($store['logoUrl']) ? (mt_rand(1, 20) . '.png') : $store['logoUrl'],
                        'image_type' => 'store_logo',
                        'is_uploaded' => 1,
                        'is_fake' => empty($store['logoUrl']) ? 1 : 0
                    ];
                }
            } catch (Exception $e) {
                flash()->error('Error while running importer');
                return redirect()->route('admin.stores.index');
            }
        }

        Store::insert($newStores);
        StoreImage::insert($newStoresImages);

        // Now, import stores' cashback
        $this->importStoresCashbacks();

        return;
    }

    /**
     * Import cashbacks of the stores imported from Awin network.
     *
     * @return void
     */
    private function importStoresCashbacks()
    {
        $curl = curl_init();

        $startDate = date('Y-m-d\TH:i:s', strtotime('-31 days'));
        $endDate = date('Y-m-d\TH:i:s');

        curl_setopt(
            $curl,
            CURLOPT_URL,
            "https://api.awin.com/publishers/{$this->siteSettings['awin_publisher_id']}/transactions/?countryCode=GB&startDate=$startDate&endDate=$endDate&timezone=UTC"
        );

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer {$this->siteSettings['awin_authorization_token']}"
        ));

        $curlResponse = curl_exec($curl);

        if (curl_errno($curl)) {
            flash()->error('Error: ' . curl_error($curl));
            curl_close($curl);

            return redirect()->route('admin.stores.index');
        }

        curl_close($curl);

        $transactions = json_decode($curlResponse, true);

        // Return in case of no transactions
        if (empty($transactions)) return;

        $dbStores = Store::whereNetworkId($this->network->id)->get()->toArray();

        foreach ($transactions as $transaction) {
            if (!array_key_exists('advertiserId', $transaction) || empty($transaction['advertiserId'])) continue;

            // Make sure the store of the transaction exists in our database
            $dbStoreKey = array_search($transaction['advertiserId'], array_column($dbStores, 'advertiser_id'));
            if (empty($dbStoreKey)) continue;

            if ($dbStores[$dbStoreKey]['override_cashback']) continue;

            $commissionType = array_key_exists('type', $transaction['commissionAmount']) && (strpos(strtolower($transaction['commissionAmount']['type']), 'percent') !== false)
                ? 'percentage'
                : 'fixed';

            StoreCashback::updateOrCreate([
                'store_id' => $dbStores[$dbStoreKey]['id'],
                'type' => $commissionType,
                'sale_commission' => $transaction['commissionAmount']['amount'],
            ], [
                'store_id' => $dbStores[$dbStoreKey]['id'],
                'type' => $commissionType,
                'cashback_name' => 'default',
                'image' => '#',
                'click_url' =>  '#',
                'sale_commission' => $transaction['commissionAmount']['amount'],
                'currency' => $transaction['commissionAmount']['currency'],
                'detail' => 'default',
                'network_detail' => 'default',
                'default' => 1
            ]);
        }

        return;
    }
}
