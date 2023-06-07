<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Store;
use App\Jobs\Importer;
use App\Models\Network;
use App\Models\Voucher;
use App\Models\ExitClick;
use App\Jobs\AwinImporter;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use App\Models\StoreCashback;
use App\Jobs\WebgainsImporter;
use App\Models\ImporterSetting;
use App\Models\ImportedCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CashbackStatusChange;

class ImporterController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:run importer', ['only' => ['import']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin-dashboard.importer.index');
    }

    public function import(Request $request)
    {
        $settings = ImporterSetting::updateOrCreate([
            'network_id'   => $request->network_id,
        ], [
            'import_stores'     => $request->has('stores') ? 1 : 0,
            'import_vouchers'   => $request->has('vouchers') ? 1 : 0,
            'import_cashbacks'   => $request->has('cashback') ? 1 : 0,
            'last_import_at'   => Carbon::now()->toDateTimeString()
        ]);

        $importerOptions = [
            'stores' => isset($request->stores) ? 1 : 0,
            'vouchers' => isset($request->vouchers) ? 1 : 0,
            'cashback' => isset($request->cashback) ? 1 : 0
        ];

        if ($request->network_name == "CJ") {
            $importer = new Importer();
        } else if ($request->network_name == "Webgains") {
            $importer = new WebgainsImporter();
        } else if ($request->network_name == "Awin") {
            $importer = new AwinImporter();
        } else {
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'error' => 'Network not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        dispatch($importer);
    }

    public function import_commissions()
    {
        $total_callback = 0;
        $beforePostingDate = date('Y-m-d\TH:i:s\z', strtotime('-91 days'));
        $sincePostingDate = date('Y-m-d\TH:i:s\z', strtotime('-121 days'));
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://commissions.api.cj.com/query',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ publisherCommissions(forPublishers: ["5499477"], sincePostingDate:"' . $sincePostingDate . '",beforePostingDate:"' . $beforePostingDate . '"){count payloadComplete records {actionTrackerName clickReferringURL commissionId orderId websiteName advertiserName advertiserId pubCommissionAmountPubCurrency postingDate pubCommissionAmountUsd saleAmountPubCurrency actionStatus validationStatus clickDate eventDate shopperId   items { quantity perItemSaleAmountPubCurrency totalCommissionPubCurrency  }}}}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer 1jkkfyp5r28p43ghpsx4p1h588',
                'Content-Type: application/json'
            ),
        ));

        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }
        curl_close($curl);
        $result_array = json_decode($result, TRUE);

        if (
            array_key_exists('data', $result_array)
            && array_key_exists('publisherCommissions', $result_array['data'])
            && array_key_exists('records', $result_array['data']['publisherCommissions'])
        ) {
            foreach ($result_array['data']['publisherCommissions']['records'] as $cashback) {
                $store = Store::where('advertiser_id', $cashback['advertiserId'])->first();
                $click = ExitClick::where('id', $cashback['shopperId'])->first();

                $cashback_amount_for_user = ($cashback['pubCommissionAmountPubCurrency'] / 100) * $cashback_percent;

                $commission_exist = UserCashback::where('exit_click_id', $cashback['shopperId'])->first();

                $status = '';

                if ($cashback['actionStatus'] == 'new' || $cashback['actionStatus'] == 'extended' || $cashback['actionStatus'] == 'locked') {
                    $status = 1;
                } else if ($cashback['actionStatus'] == 'closed' && $cashback['ValidationStatus'] == 'ACCEPTED') {
                    $status = 3;
                } else if ($cashback['actionStatus'] == 'closed' && $cashback['ValidationStatus'] == 'DECLINED') {
                    $status = 2;
                }
                if (!$commission_exist) {
                    $commission = UserCashback::create([
                        'store_id' => $store->id,
                        'user_id' => $click->user_id ?? 0,
                        'exit_click_id' => $cashback['shopperId'],
                        'amount' => round($cashback_amount_for_user, 3),
                        'network_commission' => $cashback['pubCommissionAmountPubCurrency'],
                        'order_value' => $cashback['saleAmountPubCurrency'],
                        'status' => $status,
                        'event_date' => \Carbon\Carbon::parse($cashback['eventDate'])->toDateTimeString(),
                        'click_date' => \Carbon\Carbon::parse($cashback['clickDate'])->toDateTimeString(),
                    ]);
                } else {
                    $commission_exist->update([
                        'store_id' => $store->id,
                        'user_id' => $click->user_id ?? 0,
                        'exit_click_id' => $cashback['shopperId'],
                        'amount' => round($cashback_amount_for_user, 3),
                        'network_commission' => $cashback['pubCommissionAmountPubCurrency'],
                        'order_value' => $cashback['saleAmountPubCurrency'],
                        'status' => $status,
                        'event_date' => \Carbon\Carbon::parse($cashback['eventDate'])->toDateTimeString(),
                        'click_date' => \Carbon\Carbon::parse($cashback['clickDate'])->toDateTimeString(),
                    ]);

                    if ($commission_exist->status != $status) {
                        $change_status = CashbackStatusChange::create([
                            'user_cashback_id' => $commission_exist-- > id,
                            'cashback_status_id' => $status

                        ]);
                    }
                }
            }
        }
        flash()->success('cashbacks imported successfully');
        return redirect()->route(getAdminPrefix() . '.commissions.index');
    }

    public function import_coupons()
    {
        ini_set('max_execution_time', 3000); // 5 minutes

        //importing coupons
        $total_records = 1;
        $fetched_records = 0;
        $page = 1;

        while ($fetched_records < $total_records) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://link-search.api.cj.com/v2/link-search?website-id=100179843&promotion-type=coupon&advertiser-ids=joined&records-per-page=100&page-number=' . $page);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            $headers = array();
            $headers[] = 'Authorization: Bearer 1jkkfyp5r28p43ghpsx4p1h588';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $link_result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            $link_data = simplexml_load_string($link_result);
            $link_array = json_decode(json_encode($link_data), TRUE);
            if (array_key_exists('links', $link_array) && array_key_exists('link', $link_array['links'])) {
                if (array_key_exists(0, $link_array['links']['link'])) {
                    $links = $link_array['links']['link'];
                    foreach ($links as $link) {
                        $store = Store::where('advertiser_id', $link['advertiser-id'])->first();
                        if (array_key_exists('link-code-html', $link)) {
                            $html = $link['link-code-html'];
                            $doc = new \DOMDocument();
                            @$doc->loadHTML($html);
                            $xpath = new \DOMXPath($doc);
                            $img_src = $xpath->evaluate("string(//img/@src)");
                        }
                        $voucher = Voucher::create([
                            'image'           => $img_src,
                            'click_url'       => empty($link['clickUrl']) ? '#' : $link['clickUrl'],
                            'sale_commission' => empty($link['sale-commission']) ? NULL : $link['sale-commission'],
                            'store_id' => $store->id,
                            "description" => empty($link['description']) ? NULL : $link['description'],
                            "destination" => empty($link['destination']) ? NULL : $link['destination'],
                            "link_id" => empty($link['link-id']) ? NULL : $link['link-id'],
                            "link_name" => empty($link['link-name']) ? NULL : $link['link-name'],
                            "link_type" => empty($link['link-type']) ? NULL : $link['link-type'],
                            "promotion_end_date" => empty($link['promotion-end-date']) ? NULL : $link['promotion-end-date'],
                            "promotion_start_date" => empty($link['promotion-start-date']) ? NULL : $link['promotion-start-date'],
                            "promotion_type" => empty($link['promotion-type']) ? NULL : $link['promotion-type'],
                            "coupon_code" => empty($link['coupon-code']) ? NULL : $link['coupon-code'],
                        ]);
                    }
                } else {
                    $link = $link_array['links']['link'];
                    $store = Store::where('advertiser_id', $link['advertiser-id'])->first();
                    if (array_key_exists('link-code-html', $link)) {
                        $html = $link['link-code-html'];
                        $doc = new \DOMDocument();
                        @$doc->loadHTML($html);
                        $xpath = new \DOMXPath($doc);
                        $img_src = $xpath->evaluate("string(//img/@src)");
                    }
                    $voucher = Voucher::create([
                        'image'           => $img_src,
                        'click_url'       => empty($link['clickUrl']) ? '#' : $link['clickUrl'],
                        'sale_commission' => empty($link['sale-commission']) ? NULL : $link['sale-commission'],
                        'store_id' => $store->id,
                        "description" => empty($link['description']) ? NULL : $link['description'],
                        "destination" => empty($link['destination']) ? NULL : $link['destination'],
                        "link_id" => empty($link['link-id']) ? NULL : $link['link-id'],
                        "link_name" => empty($link['link-name']) ? NULL : $link['link-name'],
                        "link_type" => empty($link['link-type']) ? NULL : $link['link-type'],
                        "promotion_end_date" => empty($link['promotion-end-date']) ? NULL : $link['promotion-end-date'],
                        "promotion_start_date" => empty($link['promotion-start-date']) ? NULL : $link['promotion-start-date'],
                        "promotion_type" => empty($link['promotion-type']) ? NULL : $link['promotion-type'],
                        "coupon_code" => empty($link['coupon-code']) ? NULL : $link['coupon-code'],
                    ]);
                }
            }
            $total_records = $link_array['links']['@attributes']['total-matched'];
            $fetched_records = $fetched_records + $link_array['links']['@attributes']['records-returned'];
            $page++;
        }

        //importing deals
        $total_records = 1;
        $fetched_records = 0;
        $page = 1;

        while ($fetched_records < $total_records) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://link-search.api.cj.com/v2/link-search?website-id=100179843&promotion-type=sale/discount&advertiser-ids=joined&records-per-page=100&page-number=' . $page);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            $headers = array();
            $headers[] = 'Authorization: Bearer 1jkkfyp5r28p43ghpsx4p1h588';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $link_result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);

            $link_data = simplexml_load_string($link_result);
            $link_array = json_decode(json_encode($link_data), TRUE);

            if (array_key_exists('links', $link_array) && array_key_exists('link', $link_array['links'])) {
                if (array_key_exists(0, $link_array['links']['link'])) {
                    $links = $link_array['links']['link'];
                    foreach ($links as $link) {
                        $store = Store::where('advertiser_id', $link['advertiser-id'])->first();

                        if (array_key_exists('link-code-html', $link)) {
                            $html = $link['link-code-html'];
                            $doc = new \DOMDocument();
                            @$doc->loadHTML($html);
                            $xpath = new \DOMXPath($doc);
                            $img_src = $xpath->evaluate("string(//img/@src)");
                        }
                        $voucher = Voucher::create([
                            'image'           => $img_src,
                            'click_url'       => empty($link['clickUrl']) ? '#' : $link['clickUrl'],
                            'sale_commission' => empty($link['sale-commission']) ? NULL : $link['sale-commission'],
                            'store_id' => $store->id ?? 0,
                            "description" => empty($link['description']) ? NULL : $link['description'],
                            "destination" => empty($link['destination']) ? NULL : $link['destination'],
                            "link_id" => empty($link['link-id']) ? NULL : $link['link-id'],
                            "link_name" => empty($link['link-name']) ? NULL : $link['link-name'],
                            "link_type" => empty($link['link-type']) ? NULL : $link['link-type'],
                            "promotion_end_date" => empty($link['promotion-end-date']) ? NULL : $link['promotion-end-date'],
                            "promotion_start_date" => empty($link['promotion-start-date']) ? NULL : $link['promotion-start-date'],
                            "promotion_type" => empty($link['promotion-type']) ? NULL : $link['promotion-type'],
                            "coupon_code" => empty($link['coupon-code']) ? NULL : $link['coupon-code'],
                        ]);
                    }
                } else {
                    $link = $link_array['links']['link'];
                    $store = Store::where('advertiser_id', $link['advertiser-id'])->first();
                    if (array_key_exists('link-code-html', $link)) {
                        $html = $link['link-code-html'];
                        $doc = new \DOMDocument();
                        @$doc->loadHTML($html);
                        $xpath = new \DOMXPath($doc);
                        $img_src = $xpath->evaluate("string(//img/@src)");
                    }
                    $voucher = Voucher::create([
                        'image'           => $img_src,
                        'click_url'       => empty($link['clickUrl']) ? '#' : $link['clickUrl'],
                        'sale_commission' => empty($link['sale-commission']) ? NULL : $link['sale-commission'],
                        'store_id' => $store->id ?? 0,
                        "description" => empty($link['description']) ? NULL : $link['description'],
                        "destination" => empty($link['destination']) ? NULL : $link['destination'],
                        "link_id" => empty($link['link-id']) ? NULL : $link['link-id'],
                        "link_name" => empty($link['link-name']) ? NULL : $link['link-name'],
                        "link_type" => empty($link['link-type']) ? NULL : $link['link-type'],
                        "promotion_end_date" => empty($link['promotion-end-date']) ? NULL : $link['promotion-end-date'],
                        "promotion_start_date" => empty($link['promotion-start-date']) ? NULL : $link['promotion-start-date'],
                        "promotion_type" => empty($link['promotion-type']) ? NULL : $link['promotion-type'],
                        "coupon_code" => empty($link['coupon-code']) ? NULL : $link['coupon-code'],
                    ]);
                }
            }
            $total_records = $link_array['links']['@attributes']['total-matched'];
            $fetched_records = $fetched_records + $link_array['links']['@attributes']['records-returned'];
            $page++;
        }
        flash()->success('vouchers imported successfully');
        return redirect()->route(getAdminPrefix() . '.vouchers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        ini_set('max_execution_time', 3000); // 5 minutes

        $setting = ImporterSetting::where('network_id', 1)->first();

        //importing advertisers/stores/merchents

        if (true) {
            $total_records = 1;
            $fetched_records = 0;
            $page = 1;

            while ($fetched_records < $total_records) {
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://advertiser-lookup.api.cj.com/v2/advertiser-lookup?requestor-cid=5499477&advertiser-ids=2746196&records-per-page=50&page-number=' . $page);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

                $headers = array();
                $headers[] = 'Authorization: Bearer 1jkkfyp5r28p43ghpsx4p1h588';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);

                $data = simplexml_load_string($result);
                $array = json_decode(json_encode($data), TRUE);

                $adverts = $data->advertisers->advertiser;

                foreach ($adverts as $advertiser) {
                    try {
                        $store = Store::where('advertiser_id', $advertiser->{'advertiser-id'})->first();
                        if (true) {
                            $store = Store::create([
                                'name'         => $advertiser->{'advertiser-name'},
                                'advertiser_id' => $advertiser->{'advertiser-id'},
                                'network_id'   => 1,
                                'tracking_url' => $advertiser->{'program-url'},
                                'store_url'    => $advertiser->{'program-url'},
                            ]);

                            //importing cashbacks for current advertiser/store/merchent

                            $ch = curl_init();

                            curl_setopt($ch, CURLOPT_URL, 'https://link-search.api.cj.com/v2/link-search?website-id=100179843&link-type=banner&advertiser-ids=' . $advertiser->{'advertiser-id'});
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

                            $headers = array();
                            $headers[] = 'Authorization: Bearer 1jkkfyp5r28p43ghpsx4p1h588';
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                            $link_result = curl_exec($ch);
                            if (curl_errno($ch)) {
                                echo 'Error:' . curl_error($ch);
                            }
                            curl_close($ch);

                            $link_data = simplexml_load_string($link_result);
                            $link_array = json_decode(json_encode($link_data), TRUE);

                            $destination_url = $advertiser->{'program-url'};

                            $link_found = 0;
                            $link_url = '#';

                            if ($link_data->links->link) {
                                foreach ($link_data->links->link as $link) {
                                    if (!strcmp($link->destination, $destination_url)) {
                                        $link_found = 1;
                                        $link_url = $link->clickUrl;
                                    }
                                }

                                if ($link_found) {
                                    $store->tracking_url = $link_url;
                                } else {
                                    $store->tracking_url = $link_data->links->link[0]->clickUrl;
                                }

                                $store->update();
                            }

                            foreach ($advertiser->actions->action as $action) {
                                if ($action->commission->default) {
                                    $c_type = Str::contains($action->commission->default, '%') ? 'percentage' : 'fixed';
                                    if ($c_type == 'percentage') {
                                        $sale_commission =  str_replace('%', '', $action->commission->default);
                                    } else {
                                        $splited = explode(" ", $action->commission->default);
                                        $sale_commission = $splited[1];
                                    }

                                    $cb = StoreCashback::where('store_id', $store->id)->where('type', $c_type)->where('sale_commission', $action->commission->default)->first();
                                    if ($cb) {
                                        $cb->update([
                                            'detail' => $cb->detail . ', ' . $action->name . ' default',
                                            'network_detail' => $cb->network_detail . ', ' . $action->name . ' default',
                                        ]);
                                    } else {
                                        $cashback = StoreCashback::create([
                                            'type' => $c_type,
                                            'image'           => '#',
                                            'click_url'       =>  '#',
                                            'sale_commission' => $sale_commission,
                                            'cashback_name' => $action->name,
                                            'detail' => $action->name . ' default',
                                            'network_detail' => $action->name . ' default',
                                            'store_id' => $store->id,
                                        ]);
                                    }
                                }
                                if ($action->commission->itemlist) {
                                    foreach ($action->commission->itemlist as $item) {
                                        $c_type = Str::contains($item, '%') ? 'percentage' : 'fixed';
                                        if ($c_type == 'percentage') {
                                            $sale_commission =  str_replace('%', '', $item);
                                        } else {
                                            $splited = explode(" ", $item);
                                            $sale_commission = $splited[1];
                                        }

                                        $cb = StoreCashback::where('store_id', $store->id)->where('type', $c_type)->where('sale_commission', $item)->first();
                                        if ($cb) {
                                            $cb->update([
                                                'detail' => $cb->detail . ', ' . $action->name . ' ' . $item->attributes()->name,
                                                'network_detail' => $cb->network_detail . ', ' . $action->name . ' ' . $item->attributes()->name,
                                            ]);
                                        } else {
                                            $cashback = StoreCashback::create([
                                                'type' => $c_type,
                                                'image'           => '#',
                                                'click_url'       =>  '#',
                                                'sale_commission' => $sale_commission,
                                                'cashback_name' => $action->name,
                                                'detail' => $action->name . ' ' . $item->attributes()->name,
                                                'network_detail' => $action->name . ' ' . $item->attributes()->name,
                                                'store_id' => $store->id,
                                            ]);
                                        }
                                    }
                                }
                            }

                            if (!empty($advertiser->{'primary-category'}->{'parent'})) {
                                $category_parent = ImportedCategory::where('name', $advertiser->{'primary-category'}->{'parent'})->first();

                                if (!$category_parent) {
                                    $category_parent = new ImportedCategory();
                                    $category_parent->name = $advertiser->{'primary-category'}->{'parent'};
                                    $category_parent->network_id = 1;
                                    $category_parent->save();
                                }

                                DB::table('category_store')->insert([
                                    'store_id' => $store->id,
                                    'category_id' => $category_parent->mapped_to ?? 0,
                                    'network_category_id' => $category_parent->id,
                                ]);
                            }

                            if (!empty($advertiser->{'primary-category'}->{'child'})) {
                                $category_child = ImportedCategory::where('name', $advertiser->{'primary-category'}->{'child'})->first();

                                if (!$category_child) {
                                    $category_child = new ImportedCategory();
                                    $category_child->name = $advertiser->{'primary-category'}->{'child'};
                                    $category_child->parent_id = $category_parent->id ?? 0;
                                    $category_child->network_id = 1;
                                    $category_child->save();
                                }
                                DB::table('category_store')->insert([
                                    'store_id' => $store->id,
                                    'category_id' => $category_child->mapped_to ?? 0,
                                    'network_category_id' => $category_child->id,
                                ]);
                            }
                        }
                    } catch (Exception $e) {
                        flash()->error('Error while running importer');
                        return redirect()->route(getAdminPrefix() . '.stores.index');
                    }
                }

                $total_records = $data->{'advertisers'}->attributes()->{'total-matched'};
                $fetched_records = $fetched_records + $data->{'advertisers'}->attributes()->{'records-returned'};
                $page++;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $importer = new Importer();
        dispatch($importer);
    }

    public function saveSettings(Request $request)
    {
        try {
            ImporterSetting::updateOrCreate([
                'network_id'   => $request->network_id,
            ], [
                'import_stores'    => $request->has('stores') ? 1 : 0,
                'import_vouchers'  => $request->has('vouchers') ? 1 : 0,
                'import_cashbacks' => $request->has('cashback') ? 1 : 0,
            ]);

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Settings saved'
            ], JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong, try again'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function importerSettingForm($id)
    {
        $network = Network::where('id', $id)->first();
        $settings = SiteSetting::latest()->get()->pluck('value', 'type');
        return view('admin-dashboard.networks.importer_setting_form', compact('network', 'settings'));
    }
}
