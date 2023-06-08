<?php

namespace App\Jobs;

use App\Models\Store;
use App\Models\Network;
use App\Models\ExitClick;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use App\Models\CashbackStatus;
use App\Models\ImporterSetting;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class PartnerizeImporter implements ShouldQueue
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
        $this->network = Network::whereName('Partnerize')->first();
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
        if ($this->importerSetting->import_cashbacks == 1) $this->importUserCashbacks();
    }

    /**
     * Import user's cashbacks
     *
     * @return void
     */
    private function importUserCashbacks()
    {
        $curl = curl_init();

        curl_setopt(
            $curl,
            CURLOPT_URL,
            'https://api.partnerize.com/user/publisher/' . $this->siteSettings['partnerize_publisher_id'] . '/tq'
        );
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                'Authorization: Basic ' . base64_encode($this->siteSettings['partnerize_application_key'] . ':' . $this->siteSettings['partnerize_user_api_key'])
                )
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

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

        $userCashbacks = $transactions['transaction_queries'];

        foreach ($userCashbacks as $transaction) {
            $advertiserId = $transaction->transaction_query->campaign->advertiser_id;

            // Skip transaction without advertiser ID
            if (!array_key_exists('advertiser_id', $advertiserId) || empty($advertiserId)) continue;

            // Make sure the store of the transaction exists in our database
            $dbStoreKey = array_search($advertiserId, array_column($dbStores, 'advertiser_id'));
            if (empty($dbStoreKey)) continue;

            // If the db store's cashback is set to be overridden by admin, then we will skip this
            if ($dbStores[$dbStoreKey]['override_cashback']) continue;

        }

        return;
    }
}
