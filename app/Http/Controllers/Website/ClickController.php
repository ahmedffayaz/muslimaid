<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExitClick;
use App\Models\Store;
use App\Models\SiteSetting;
use App\Models\RedeemedVoucher;

class ClickController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store = Store::findOrFail(74);
        $url = 'danishmemon.com';
        return view('frontend.pages.exit', compact('store', 'url'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $deeplink_url = '';
        $store = Store::where('id', $request->input('store_id'))->first();

        $custom_cashback_percentage = $store->custom_cashback_percentage;

        if ($custom_cashback_percentage) {
            $cashback_percent = $custom_cashback_percentage;
        } else {
            $cashback_percent = SiteSetting::where('type', 'cashback_percentage')->first()->value;
        }

        if (!$cashback_percent) {
            $cashback_percent = 0;
        }

        $click = ExitClick::create([
            'store_id' => $request->input('store_id'),
            'user_id' => $request->input('user_id'),
            'network_id' => $store->network->id,
            'status' => 'pending',
            'exit_url' => '#',
            'current_cashback_percentage' => $cashback_percent
        ]);

        if (!empty($store->deeplink_url)) {
            $deeplink_url = '&' . $store->network->deeplink_identifier . '=' . $store->deeplink_url;
        }

        if ($store->network->id == 1) {
            $click->exit_url = $request->input('url') . '?' . $store->network->click_ref . '=' . $click->id . $deeplink_url;
            $click->update();
        } else if ($store->network->id == 2) {
            $click->exit_url = $request->input('url') . '&' . $store->network->click_ref . '=' . $click->id . $deeplink_url;
            $click->update();
        }

        $url = $click->exit_url;

        if ($request->input('voucher_id')) {
            $redeemed = RedeemedVoucher::create([
                'user_id' => $request->input('user_id'),
                'voucher_id' => $request->input('voucher_id'),
            ]);
        }

        return view('frontend.pages.exit', compact('store', 'url'));
    }
}
