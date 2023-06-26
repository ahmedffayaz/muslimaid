<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Store;
use App\Models\Network;
use App\Models\ExitClick;
use App\Models\SiteSetting;
use App\Models\UserCashback;
use Illuminate\Bus\Queueable;
use App\Models\CashbackStatus;
use App\Models\ImporterSetting;
use Illuminate\Support\Facades\Log;
use App\Models\CashbackStatusChange;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class AfrofiliateImporter implements ShouldQueue
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
        $this->network = Network::where('name', 'like', 'Afrofiliate')->first();
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
        if ($this->importerSetting->import_cashbacks == 1) $this->importUsersCashbacks();
    }

    private function importUsersCashbacks()
    {
        $curl = curl_init(); // Replace with the actual parameters and values required by the API
        // Replace with your actual API endpoint
        $apiEndpoint = 'https://api.eflow.team/v1/affiliates/reporting/conversions';


        $startDate = date('Y-m-d', strtotime('-31 days'));
        $endDate = date('Y-m-d');
        $timezone_id = 67;
        $data = array(
            'from' => $startDate,
            "to" => $endDate,
            "timezone_id" => $timezone_id,
            "show_conversions" => true,
            "show_events" => true,
            "query" => [
                "filters" => []
            ]
        );

        curl_setopt(
            $curl,
            CURLOPT_URL,
            $apiEndpoint
        );
        $EverflowApiKey = $this->siteSettings['afrofiliate_api_key'];
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST'); // Specify the correct HTTP method
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'X-Eflow-API-Key: ' .  $EverflowApiKey,
            'Accept: application/json',
            'Content-Type: application/json', // Set the Content-Type header if sending JSON data
        ));
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); // Convert the data to JSON and send it
        $curlResponse = curl_exec($curl);

        if (curl_errno($curl)) {
            flash()->error('Error: ' . curl_error($curl));
            curl_close($curl);

            return redirect()->route(getAdminPrefix() . '.stores.index');
        }

        curl_close($curl);

        $data = json_decode($curlResponse, true);
        $cashbackStatuses = CashbackStatus::get();
        $dbStores = Store::whereNetworkId($this->network->id)->pluck('name')->toArray();
        foreach ($data['conversions'] as $store) {
            $realStoreId = "";
            $userId = "";
            if (isset($store['sub1'])) {
                $exitClick = ExitClick::where('id', $store['sub1'])->first();
            }
            if (isset($exitClick)) {
                $realStoreId=$exitClick->store_id;
                $userId=$exitClick->user_id;

                $userCashbackAmount = ($store['revenue'] / 100) * (isset($exitClick->current_cashback_percentage) ? $exitClick->current_cashback_percentage : $this->siteSettings['cashback_percentage']);

                $userCashback = UserCashback::updateOrCreate([
                    'exit_click_id' =>  $exitClick->id,
                    'network_commission_id' =>  $store['order_id']
                ], [
                    'store_id' => $realStoreId,
                    'user_id' => $userId,
                    'exit_click_id' => $exitClick->id,
                    'amount' => round($userCashbackAmount, 2),
                    'network_commission' => $store['revenue'],
                    'network_order_id' =>  $store['order_id'],
                    'order_value' => round($store['sale_amount'], 2),
                    'status' => "3",
                    'event_date' => Carbon::createFromTimestamp($store['conversion_unix_timestamp'])->toDateTimeString(),
                    'click_date' => Carbon::createFromTimestamp($store['click_unix_timestamp'])->toDateTimeString(),

                ]);
                
                $transactionStatus = $userCashback->status;
                CashbackStatusChange::updateOrCreate([
                    'user_cashback_id' => $userCashback->id,
                    'cashback_status_id' => $cashbackStatuses->where('status', $transactionStatus)->first()->id
                ], [
                    'user_cashback_id' => $userCashback->id,
                    'cashback_status_id' => $cashbackStatuses->where('status', $transactionStatus)->first()->id
                ]);
            } else {
                continue;
            }
        }
        return;
    }
}
