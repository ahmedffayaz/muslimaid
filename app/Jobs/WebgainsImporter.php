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

class WebgainsImporter implements ShouldQueue
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
        //fetching importer settings
        $network = Network::where('id', 2)->first();
        $setting = ImporterSetting::where('network_id', 2)->first();
        $settings = SiteSetting::latest()->get()->pluck('value', 'type');

        if ($setting->import_stores == 1) {

            $cu = curl_init();

            curl_setopt_array($cu, array(
                CURLOPT_URL => 'https://api.webgains.com/2.0/programs?key=' . $settings['webgains_api_key'] . '&programsjoined=1&campaignId=' . $settings['webgains_campaignid'],
                CURLOPT_RETURNTRANSFER => 1,
            ));
            curl_setopt($cu, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($cu, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($cu);
            if (curl_errno($cu)) {
                echo 'Error:' . curl_error($cu);
            }
            curl_close($cu);
            $result_array = json_decode($result, TRUE);
            foreach ($result_array as $results) {
                try {

                    $store = Store::where('advertiser_id', $results['id'])->first();
                    if ((!$store) && $results['status'] == 'live' && $results['membershipStatus'] == '10') {
                        $https_link = substr($results['textLink'], 0, 4);
                        if ($https_link == "http") {
                            $store_link = str_replace("mycampaignid", $settings['webgains_campaignid'], $results['textLink']);
                        } else {
                            $store_link = "NULL";
                        }
                        $store = Store::create([
                            'name'         => $results['name'],
                            'slug'         => Str::slug($results['name']),
                            'advertiser_id' => $results['id'],
                            'network_id'   => 2,
                            'tracking_url' => $store_link,
                            'store_url'    => $results['homepageURL'],
                            'status' => 'error',
                            'status_description' => '',
                            'network_status' => $results['status'] . ', ' . $results['membershipStatusText'],
                        ]);

                        if (isset($results['events'])) {
                            foreach ($results['events'] as $event) {
                                foreach ($event['commissionSchemes'] as $commissionScheme) {
                                    foreach ($commissionScheme['commissionItems'] as $Item) {
                                        $index = 0;
                                        foreach ($Item['tiers'] as $tier) {
                                            if ($index == '1') {
                                                break;
                                            }
                                            $c_type = Str::contains($tier['commission'], '%') ? 'percentage' : 'fixed';
                                            if ($c_type == 'percentage') {
                                                $sale_commission =  str_replace('%', '', $tier['commission']);
                                                $currency = null;
                                            } else {
                                                $splited = explode(" ", $tier['commission']);
                                                $sale_commission = $splited[0];
                                                $currency = $splited[1];
                                            }
                                            $index++;
                                        }
                                        $cb = StoreCashback::where('store_id', $store->id)->where('type', $c_type)->where('sale_commission', $tier['commission'])->first();
                                        if ($cb) {
                                            $cb->update([
                                                'detail' => $cb->detail . ', ' . $event['name'] . ' default',
                                                'network_detail' => $cb->network_detail . ', ' . $event['name'] . ' default',
                                            ]);
                                        } else {
                                            $cashback = StoreCashback::create([
                                                'type' => $c_type,
                                                'image'           => '#',
                                                'click_url'       =>  '#',
                                                'sale_commission' => $sale_commission,
                                                'currency' => $currency,
                                                'cashback_name' => $event['name'],
                                                'detail' => $event['name'] . ' default',
                                                'network_detail' => $event['name'] . ' default',
                                                'store_id' => $store->id,
                                                'default' => 1
                                            ]);
                                        }
                                    }
                                }
                            }
                        }

                        if (!empty($results['categories'])) {
                            foreach ($results['categories'] as $category) {
                                $category_parent = ImportedCategory::where('name', $category['name'])->first();
                                if (!$category_parent) {
                                    $category_parent = new ImportedCategory();
                                    $category_parent->name = $category['name'];
                                    $category_parent->network_id = 2;
                                    $category_parent->save();
                                }
                                DB::table('category_store')->insert([
                                    'store_id' => $store->id,
                                    'category_id' => $category_parent->mapped_to ?? 0,
                                    'network_category_id' => $category_parent->id,
                                ]);
                            }
                        }
                        $faker = Faker::create();
                        $storelogo = StoreImage::create([
                            'store_id' => $store->id,
                            'title' => 'logo',
                            'image' => $faker->numberBetween(1, 20) . '.png',
                            'image_type' => 'store_logo',
                            'is_uploaded' => 1,
                            'is_fake' => 1
                        ]);
                    } else if ($results['status'] == 'live' && $results['membershipStatus'] == '10') {
                        if (!$store->override_cashback) {
                            $https_link = substr($results['textLink'], 0, 4);
                            if ($https_link == "http") {
                                $store_link = str_replace("mycampaignid", $settings['webgains_campaignid'], $results['textLink']);
                            } else {
                                $store_link = "NULL";
                            }
                            $store->tracking_url = $store_link;
                            $store->update();
                            if (isset($results['events'])) {
                                if (count($store->cashbacks)) {
                                    $store->cashbacks()->delete();
                                }
                                foreach ($results['events'] as $event) {
                                    foreach ($event['commissionSchemes'] as $commissionScheme) {
                                        foreach ($commissionScheme['commissionItems'] as $Item) {
                                            $index = 0;
                                            foreach ($Item['tiers'] as $tier) {
                                                if ($index == '1') {
                                                    break;
                                                }
                                                $c_type = Str::contains($tier['commission'], '%') ? 'percentage' : 'fixed';
                                                if ($c_type == 'percentage') {
                                                    $sale_commission =  str_replace('%', '', $tier['commission']);
                                                    $currency = null;
                                                } else {
                                                    $splited = explode(" ", $tier['commission']);
                                                    $sale_commission = $splited[0];
                                                    $currency = $splited[1];
                                                }
                                                $index++;
                                            }
                                            $cb = StoreCashback::where('store_id', $store->id)->where('type', $c_type)->where('sale_commission', $tier['commission'])->first();
                                            if ($cb) {
                                                $cb->update([
                                                    'detail' => $cb->detail . ', ' . $event['name'] . ' default',
                                                    'network_detail' => $cb->network_detail . ', ' . $event['name'] . ' default',
                                                ]);
                                            } else {
                                                $cashback = StoreCashback::create([
                                                    'type' => $c_type,
                                                    'image'           => '#',
                                                    'click_url'       =>  '#',
                                                    'sale_commission' => $sale_commission,
                                                    'currency' => $currency,
                                                    'cashback_name' => $event['name'],
                                                    'detail' => $event['name'] . ' default',
                                                    'network_detail' => $event['name'] . ' default',
                                                    'store_id' => $store->id,
                                                    'default' => 1
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                            if (!$store->override_categories) {

                                DB::table('category_store')->where('store_id', $store->id)->delete();

                                if (!empty($results['categories'])) {
                                    foreach ($results['categories'] as $category) {
                                        $category_parent = ImportedCategory::where('name', $category['name'])->first();
                                        if (!$category_parent) {
                                            $category_parent = new ImportedCategory();
                                            $category_parent->name = $category['name'];
                                            $category_parent->network_id = 2;
                                            $category_parent->save();
                                        }
                                        DB::table('category_store')->insert([
                                            'store_id' => $store->id,
                                            'category_id' => $category_parent->mapped_to ?? 0,
                                            'network_category_id' => $category_parent->id,
                                        ]);
                                    }
                                }
                            }
                        }
                        $store->status_description = '';
                        $logo_exits = StoreImage::where(['store_id' => $store->id, 'title' => 'logo'])->first();
                        if ($logo_exits && $store->cashbacks && $store->categories && $store->description) {
                            $store->update(['status' => 'pending review']);
                        } else {
                            $store->status = 'error';
                            if (!$logo_exits) {
                                $store->status_description = $store->status_description . ' ' . 'image,';
                            }
                            if (!$store->cashbacks) {
                                $store->status_description = $store->status_description . ' ' . 'cashbacks,';
                            }
                            if (!$store->categories) {
                                $store->status_description = $store->status_description . ' ' . 'categories,';
                            }
                            if (!$store->description) {
                                $store->status_description = $store->status_description . ' ' . 'description';
                            }
                            $store->update();
                        }
                    }
                } catch (Exception $e) {
                    flash()->error('Error while running importer');
                    return redirect()->route(getAdminPrefix() . '.stores.index');
                }
            }
        }

        if ($setting->import_vouchers == 1) {

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.webgains.com/2.0/vouchers?key=' . $settings['webgains_api_key'] . '&campaignId=' . $settings['webgains_campaignid'] . '&networks=UK&joined=1',
                CURLOPT_RETURNTRANSFER => 1,
            ));

            $results = curl_exec($curl);
            if (curl_errno($curl)) {
                echo 'Error:' . curl_error($curl);
            }
            curl_close($curl);
            $result_array1 = json_decode($results, TRUE);

            foreach ($result_array1 as $result) {
                $store = Store::where('advertiser_id', $result['programId'])->first();
                $voucher_exist = Voucher::where('link_id', $result['id'])->first();
                if (!$voucher_exist) {
                    $voucher = Voucher::create([
                        'click_url'       => empty($result['trackingUrl']) ? '#' : $result['trackingUrl'],
                        'sale_commission' => empty($result['discount']) ? NULL : $result['discount'],
                        'store_id' => $store->id ?? 0,
                        "description" => empty($result['description']) ? NULL : $result['description'],
                        "destination" => empty($result['destinationUrl']) ? NULL : $result['destinationUrl'],
                        "link_id" => empty($result['id']) ? NULL : $result['id'],
                        "link_name" => empty($result['program_name']) ? NULL : $result['program_name'],
                        "link_type" => "Text Link",
                        "promotion_end_date" => empty($result['expiryDate']) ? NULL : \Carbon\Carbon::parse($result['expiryDate'])->format('Y-m-d H:i:s'),
                        "promotion_start_date" => empty($result['startDate']) ? NULL : \Carbon\Carbon::parse($result['startDate'])->format('Y-m-d H:i:s'),
                        "promotion_type" => empty($result['code']) ? "Coupon" : "Sale/Discount",
                        "coupon_code" => empty($result['code']) ? NULL : $result['code'],
                    ]);
                }
            }
        }

        if ($setting->import_cashbacks == 1) {
            $cashback_percent_setting = SiteSetting::where('type', 'cashback_percentage')->first()->value;
            $startdate = date('Y-m-d\TH:i:s', strtotime(' -31 days'));
            $enddate = date('Y-m-d\TH:i:s');
            $campaignid = $settings['webgains_campaignid'];
            $username = $settings['webgains_user_name'];
            $password = $settings['webgains_password'];
            $soap = new \SoapClient(
                NULL,
                array(
                    "location"   => "http://ws.webgains.com/aws.php",
                    "uri"        => "urn:http://ws.webgains.com/aws.php",
                    "style"      => SOAP_RPC,
                    "use"        => SOAP_ENCODED,
                    'exceptions' => 0
                )
            );

            $results = $soap->getFullEarnings($startdate, $enddate, $campaignid, $username, $password);

            if ($results) {
                $cashback_percent = 0;
                foreach ($results as $cashback) {
                    $store = Store::where('advertiser_id', $cashback->programID)->first();
                    $click = ExitClick::where('id', $cashback->clickRef)->first();
                    if (!$click) {
                        $click = ExitClick::where('network_click_ref', $cashback->clickRef)->first();
                    }
                    if ($click) {
                        $click_id = $click->id;
                        $cashback_percent = $click->current_cashback_percentage;
                    } else {
                        $new_click = ExitClick::create([
                            'store_id' => $store->id,
                            'user_id' => 1,
                            'network_click_ref' => $cashback->clickRef,
                            'exit_url' => '#',
                            'current_cashback_percentage' => $cashback_percent_setting
                        ]);
                        $click_id = $new_click->id;
                        $cashback_percent = $cashback_percent_setting;
                    }
                    $cashback_amount_for_user = ($cashback->commission / 100) * $cashback_percent;
                    $commission_exist = UserCashback::where(['exit_click_id' =>  $click_id, 'network_commission_id' =>  $cashback->transactionID])->first();
                    $status = '';
                    if ($cashback->status == 'delayed') {
                        $status = 1;
                    } else if ($cashback->status == 'cancelled') {
                        $status = 2;
                    } else if ($cashback->status == 'confirmed') {
                        $status = 3;
                    }
                    if ($cashback->status == 'confirmed' &&  $cashback->paymentStatus != 'paid') {
                        $status = 1;
                    }
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

                        $change_status = CashbackStatusChange::create([
                            'user_cashback_id' => $commission->id,
                            'cashback_status_id' => $status
                        ]);
                    } else {
                        if ($commission_exist->status != $status) {
                            $change_status = CashbackStatusChange::create([
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
