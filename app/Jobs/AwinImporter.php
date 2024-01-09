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
use Illuminate\Support\Carbon;
use App\Models\ImporterSetting;
use Illuminate\Support\Facades\DB;
use App\Models\CashbackStatusChange;
use Illuminate\Queue\SerializesModels;
use App\Jobs\AwinStoreCashbacksImporter;
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
        if ($this->importerSetting->import_cashbacks == 1) $this->importUsersCashbacks();
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

            return redirect()->route(getAdminPrefix() . '.stores.index');
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
                return redirect()->route(getAdminPrefix() . '.stores.index');
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
        // We have API call limit for '20' calls per minute (for safe side make it '15'), so we divide and conquer
        $storesChunks = Store::where('network_id', $this->network->id)->orderBy('id', 'DESC')->get()->chunk(15);

        foreach ($storesChunks as $key => $storesChunk) {
            AwinStoreCashbacksImporter::dispatch($storesChunk)->delay(now()->addMinutes($key + 1));
        }
    }

    /**
     * Import users' cashbacks
     *
     * @return void
     */
    private function importUsersCashbacks()
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
            return redirect()->route(getAdminPrefix() . '.stores.index');
        }

        curl_close($curl);

        $transactions = json_decode($curlResponse, true);

        // Return in case of no transaction
        if (empty($transactions)) return;

        $dbStores = Store::whereNetworkId($this->network->id)->get()->toArray();
        $cashbackStatuses = CashbackStatus::get();

        foreach ($transactions as $transaction) {
            // Skip transaction without advertiser ID
            if (!array_key_exists('advertiserId', $transaction) || empty($transaction['advertiserId'])) continue;

            // Skip transaction without click reference
            if (empty($transaction['clickRefs'])) continue;

            // Make sure the store of the transaction exists in our database
            $dbStoreKey = array_search($transaction['advertiserId'], array_column($dbStores, 'advertiser_id'));
            if (empty($dbStoreKey)) continue;

            // If the db store's cashback is set to be overridden by admin, then we will skip this
            if ($dbStores[$dbStoreKey]['override_cashback']) continue;

            $exitClick = ExitClick::where('id', $transaction['clickRefs']['clickRef'])
                ->orWhere('network_click_ref', $transaction['clickRefs']['clickRef'])->first();

            if (empty($exitClick)) {
                $exitClick = ExitClick::create([
                    'store_id' => $dbStores[$dbStoreKey]['id'],
                    'user_id' => 1,
                    'network_click_ref' => $transaction['clickRefs']['clickRef'],
                    'exit_url' => '#',
                    'current_cashback_percentage' => $this->siteSettings['cashback_percentage'],
                ]);
            }

            $transactionStatus = 'pending';

            if ($transaction['commissionStatus'] == 'pending') $transactionStatus = 'pending';
            if ($transaction['commissionStatus'] == 'approved') $transactionStatus = 'paid';
            if ($transaction['commissionStatus'] == 'declined') $transactionStatus = 'failed';
            if ($transaction['commissionStatus'] == 'deleted') $transactionStatus = 'failed';

            $userCashbackAmount = ($transaction['commissionAmount']['amount'] / 100) * $exitClick->current_cashback_percentage;

            $userCashback = UserCashback::updateOrCreate([
                'exit_click_id' =>  $exitClick->id,
                'network_commission_id' =>  $transaction['id']
            ], [
                'store_id' => $dbStores[$dbStoreKey]['id'],
                'user_id' => 1,
                'exit_click_id' => $exitClick->id,
                'amount' => round($userCashbackAmount, 2),
                'network_commission' => $transaction['commissionAmount']['amount'],
                'network_commission_id' => $transaction['id'],
                'network_order_id' => $transaction['id'],
                'order_value' => round($transaction['saleAmount']['amount'], 2),
                'status' => $transaction['commissionStatus'],
                'event_date' => Carbon::parse($transaction['transactionDate'])->toDateTimeString(),
                'click_date' => Carbon::parse($transaction['clickDate'])->toDateTimeString(),
            ]);

            CashbackStatusChange::updateOrCreate([
                'user_cashback_id' => $userCashback->id,
                'cashback_status_id' => $cashbackStatuses->where('status', $transactionStatus)->first()->id
            ], [
                'user_cashback_id' => $userCashback->id,
                'cashback_status_id' => $cashbackStatuses->where('status', $transactionStatus)->first()->id
            ]);
        }

        return;
    }
}
