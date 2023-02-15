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
        $deeplinkUrl = '';
        $store = Store::where('id', $request->input('store_id'))->first();

        // Get network ID
        if ($store->override_network) {
            $networkId = $request->input('network_id');
        } else {
            $networkId = $store->network->id;
        }

        // Cashback percentage
        $customCashbackPercentage = $store->custom_cashback_percentage;

        if ($customCashbackPercentage) {
            $cashbackPercent = $customCashbackPercentage;
        } else {
            $cashbackPercent = SiteSetting::where('type', 'cashback_percentage')->first()->value;
        }

        if (!$cashbackPercent) {
            $cashbackPercent = 0;
        }

        $click = ExitClick::create([
            'store_id' => $request->input('store_id'),
            'user_id' => $request->input('user_id'),
            'network_id' => $networkId,
            'status' => 'pending',
            'exit_url' => '#',
            'current_cashback_percentage' => $cashbackPercent
        ]);

        // Get click ref & deeplink identifier
        if ($store->override_network) {
            $clickRef = $click->network->click_ref;
            $deeplinkIdentifier = $click->network->deeplink_identifier;
        } else {
            $clickRef = $store->network->click_ref;
            $deeplinkIdentifier = $store->network->deeplink_identifier;
        }

        // Get deeplink URl
        if (!empty($request->deeplink_url)) {
            $deeplinkUrl = '&' . $deeplinkIdentifier . '=' . $request->input('deeplink_url');
        }

        if ($store->network->id == 1) {
            $click->exit_url = $request->input('url') . '?' . $clickRef . '=' . $click->id . $deeplinkUrl;
            $click->update();
        }

        if ($store->network->id == 2) {
            $click->exit_url = $request->input('url') . '&' . $clickRef . '=' . $click->id . $deeplinkUrl;
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
