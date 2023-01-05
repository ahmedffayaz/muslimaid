<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Store;
use App\Models\Network;
use App\Models\ExitClick;
use App\Models\StoreImage;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use App\Models\UserCashback;
use App\Models\StoreCashback;
use Illuminate\Support\Facades\DB;
use App\Models\CashbackStatusChange;

class AwinController extends Controller
{
    public function awinTest()
    {
        $network = Network::where('name', 'like', 'Awin')->first();
        $siteSettings = SiteSetting::latest()->get()->pluck('value', 'type');

        $curl = curl_init();

        $startDate = date('Y-m-d\TH:i:s', strtotime('-31 days'));
        $endDate = date('Y-m-d\TH:i:s');

        curl_setopt(
            $curl,
            CURLOPT_URL,
            "https://api.awin.com/publishers/{$siteSettings['awin_publisher_id']}/transactions/?countryCode=GB&startDate=$startDate&endDate=$endDate&timezone=UTC"
        );

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer {$siteSettings['awin_authorization_token']}"
        ));

        $curlResponse = curl_exec($curl);

        if (curl_errno($curl)) {
            flash()->error('Error: ' . curl_error($curl));
            return redirect()->route('admin.stores.index');
        }

        curl_close($curl);

        $transactions = json_decode($curlResponse, true);

        // return in case of no transactions
        if (empty($transactions)) return;

        $dbStores = Store::whereNetworkId($network->id)->get()->toArray();

        foreach ($transactions as $transaction) {
            if (!array_key_exists('advertiserId', $transaction) || empty($transaction['advertiserId'])) continue;

            // make sure the store of the transaction exists in our database
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
    }
}
