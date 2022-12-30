<?php

namespace App\Jobs;

use Exception;
use App\Models\Store;
use App\Models\Network;
use App\Models\Voucher;
use App\Models\ExitClick;
use App\Models\StoreImage;
use App\Models\SiteSetting;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use App\Models\StoreCashback;
use Illuminate\Bus\Queueable;
use App\Models\ImporterSetting;
use App\Models\ImportedCategory;
use Illuminate\Support\Facades\DB;
use App\Models\CashbackStatusChange;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class AwinImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $network = Network::where('name', 'like', 'Awin')->first();
        $importerSetting = ImporterSetting::where('network_id', $network->id)->first();
        $siteSettings = SiteSetting::latest()->get()->pluck('value', 'type');

        if ($importerSetting->import_stores == 1) {
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, "https://api.awin.com/publishers/{$siteSettings['awin_publisher_id']}/programmes?countryCode=GB&relationship=joined");

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

            $stores = json_decode($curlResponse, true);

            $dbStores = Store::whereNetworkId($network->id)
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
                            'network_id' => $network->id,
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
        }
    }
}
