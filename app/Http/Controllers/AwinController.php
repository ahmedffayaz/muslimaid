<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

class AwinController extends Controller
{
    public function awinTest()
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

        // TODO: replace 12 with 1 in final state
        if ($importerSetting->import_cashbacks == 12) {
            $curl = curl_init();

            $startDate = date('Y-m-d\TH:i:s', strtotime(' -31 days'));
            $endDate = date('Y-m-d\TH:i:s');

            curl_setopt(
                $curl,
                CURLOPT_URL,
                "https://api.awin.com/publishers/{$siteSettings['awin_publisher_id']}/transactions/?startDate=$startDate&endDate=$endDate&timezone=UTC"
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

            echo $curlResponse; die();
            
            $stores = json_decode($curlResponse, true);

            $cashback_percent_setting = SiteSetting::where('type', 'cashback_percentage')->first()->value;
            
            $publisherId = $siteSettings['awin_publisher_id'];
            $authorizationToken = $siteSettings['awin_authorization_token'];

            $soap = new \SoapClient(
                NULL,
                array(
                    "location"   => "http://ws.awin.com/aws.php",
                    "uri"        => "urn:http://ws.awin.com/aws.php",
                    "style"      => SOAP_RPC,
                    "use"        => SOAP_ENCODED,
                    'exceptions' => 0
                )
            );

            $results = $soap->getFullEarnings($startDate, $endDate, $publisherId, $authorizationToken, null);

            if ($results) {
                $cashback_percent = 0;
                foreach ($results as $cashback) {
                    $store = Store::where('advertiser_id', $cashback->programID)->first();
                    $click = ExitClick::where('id', $cashback->clickRef)->first(); 
                    if (!$click) {
                        $click = ExitClick::where('network_click_ref', $cashback->clickRef)->first(); 
                    if ($click) { 
                        $click_id = $click->id; 
                        $cashback_percent = $click->current_cashback_percentage;
                    } else {
                        $new_click = ExitClick::create([ 
                            'store_id' => $store->id, 
                            'user_id' => 1, 
                            'network_click_ref' => $cashback->clickRef,
                            'status' => 'pending', 
                            'exit_url' => '#',
                            'current_cashback_percentage' => $cashback_percent_setting 
                        ]);
                        $click_id = $new_click->id; 
                        $cashback_percent = $cashback_percent_setting;
                    }

                    $cashback_amount_for_user = ($cashback->commission / 100) * $cashback_percent; 
                    $commission_exist = UserCashback::where(['exit_click_id' =>  $click_id, 'network_commission_id' =>  $cashback->transactionID])->first(); 

                    $status = '';
                    if ($cashback->status == 'delayed') $status = 1;
                    else if ($cashback->status == 'cancelled') $status = 2;
                    else if ($cashback->status == 'confirmed') $status = 3;

                    if ($cashback->status == 'confirmed' &&  $cashback->paymentStatus != 'paid') $status = 1; 
                    
                    if (!$commission_exist) {
                        $commission = UserCashback::create([ 
                            'store_id' => $store->id,
                            'user_id' => $click->user_id ?? 1, 
                            'exit_click_id' => $click_id, 
                            'amount' => round($cashback_amount_for_user, 2), 
                            'network_commission' => $cashback->commission,
                            'network_commission_id' => $cashback->transactionID, 
                            'network_order_id' => $cashback->transactionID,
                            'order_value' => $cashback->saleValue, 
                            'status' => $status, 
                            'event_date' => \Carbon\Carbon::parse($cashback->date)->toDateTimeString(), 
                            'click_date' => \Carbon\Carbon::parse($cashback->clickthroughTime)->toDateTimeString(), 
                        ]);
 
                        CashbackStatusChange::create([
                            'user_cashback_id' => $commission->id,
                            'cashback_status_id' => $status
                        ]);
                    } else {
                        if ($commission_exist->status != $status) {
                            CashbackStatusChange::create([
                                'user_cashback_id' => $commission_exist->id,
                                'cashback_status_id' => $status
                            ]);
                        }
                        $commission_exist->update([
                            'store_id' => $store->id,
                            'user_id' => $click->user_id ?? 1,
                            'exit_click_id' => $click_id,
                            'amount' => round($cashback_amount_for_user, 2),
                            'network_commission' => $cashback->commission,
                            'order_value' => $cashback->saleValue,
                            'status' => $status,
                            'event_date' => \Carbon\Carbon::parse($cashback->date)->toDateTimeString(),
                            'click_date' => \Carbon\Carbon::parse($cashback->clickthroughTime)->toDateTimeString(),
                        ]);
                    }
                }
            }
        }
    }
}
