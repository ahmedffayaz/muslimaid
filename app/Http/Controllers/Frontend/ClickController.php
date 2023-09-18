<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\Store;
use App\Models\ExitClick;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Models\RedeemedVoucher;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClickController extends Controller
{
    public function store($storeId, $userId = null, Request $request)
    {
        try {
            DB::beginTransaction();
            $deeplinkUrl = '';

            $store = Store::findOrFail($storeId);
            $cashbackId = $request->cashback_id;
            $cashbackType = $request->cashback_type;

            // Override network's store cashback
            if ($store->override_network && $cashbackType == 'bonus_cashback' && !empty($cashbackId)) {
                $cashback = $store->cashback->findOrFail($cashbackId);

                if (!$cashback->tracking_url) {
                    $networkId = $store->network->id;
                    $trackingUrl = $store->tracking_url;
                    $clickIdentifier = $store->network->click_ref;

                    $deeplinkIdentifier  = optional($store)->deeplink_url ? $store->network->deeplink_identifier : '';
                    $deeplinkUrl = optional($store)->deeplink_url ? $store->deeplink_url : '';
                } else {
                    $networkId = $cashback->network_id;
                    $trackingUrl = $cashback->tracking_url;
                    $clickIdentifier = $cashback->network->click_ref;

                    $deeplinkIdentifier = optional($cashback)->deeplink_url ? $cashback->network->deeplink_identifier : '';
                    $deeplinkUrl = optional($cashback)->deeplink_url ? $cashback->deeplink_url : '';
                }
            } else {
                $networkId = $store->network->id;
                $clickIdentifier = $store->network->click_ref;
                $trackingUrl = $store->tracking_url;

                $deeplinkIdentifier  = optional($store)->deeplink_url ? $store->network->deeplink_identifier : '';
                $deeplinkUrl = $store->deeplink_url;
            }

            // Cashback percentage
            $customCashbackPercentage = $store->custom_cashback_percentage;

            $cashbackPercent = $customCashbackPercentage ? $customCashbackPercentage : SiteSetting::where('type', 'cashback_percentage')->first()->value;

            if (!$cashbackPercent) $cashbackPercent = 0;

            $click = ExitClick::create([
                'store_id' => $store->id,
                'user_id' => auth()->user()->id ?? 1,
                'network_id' => $networkId,
                'exit_url' => '#',
                'current_cashback_percentage' => $cashbackPercent
            ]);

            $click->exit_url = $trackingUrl . $clickIdentifier . $click->id . $deeplinkIdentifier . $deeplinkUrl;
            $click->update();

            $url = $click->exit_url;

            $clickId = $click->id;

            if ($request->input('voucher_id')) {
                $redeemed = RedeemedVoucher::create([
                    'user_id' => auth()->user()->id ?? 1,
                    'voucher_id' => $request->input('voucher_id'),
                ]);
            }

            DB::commit();

            return view('frontend.stores.exit-click', compact('store', 'cashbackId', 'cashbackType', 'url', 'clickId'));
        } catch (ModelNotFoundException $e) {
            flash()->error('Something went wrong. Try again');
            return redirect()->back();
        } catch (Exception $e) {
            flash()->error('Something went wrong. Try again');
            return redirect()->back();
        }
    }

    public function redeemVoucher(Request $request, $storeId, $voucherId)
    {
        $validator = Validator::make($request->all(), [
            'store_id' => 'required',
            'voucher_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $validator->errors()->first()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $storeId = decrypt($request->store_id);
        $voucherId = decrypt($request->voucher_id);
        $store = Store::findOrFail($storeId);
        $voucher = Voucher::findOrFail($voucherId);

        $trackingUrl = $voucher->tracking_url ? $voucher->tracking_url : $voucher->store->tracking_url;
        $clickIdentifier = $voucher->store->network->click_ref;
        $deeplinkUrl = $voucher->deeplink_url ? $voucher->deeplink_url : $voucher->store->deeplink_url;
        $deeplinkIdentifier = $voucher->store->network->deeplink_identifier;

        $customCashbackPercentage = $store->custom_cashback_percentage;
        $cashbackPercent = $customCashbackPercentage ? $customCashbackPercentage : SiteSetting::where('type', 'cashback_percentage')->first()->value;

        if (!$cashbackPercent) {
            $cashbackPercent = 0;
        }

        try {
            DB::beginTransaction();
            $click = ExitClick::create([
                'store_id' => $storeId,
                'user_id' => auth()->user()->id ?? 1,
                'network_id' => $store->network->id,
                'exit_url' => '#',
                'voucher_id' => $voucherId,
                'current_cashback_percentage' => $cashbackPercent
            ]);
            $click->exit_url = $deeplinkUrl != null ? $trackingUrl . $clickIdentifier . $click->id . $deeplinkIdentifier . $deeplinkUrl : $trackingUrl . $clickIdentifier . $click->id;
            $click->update();
            $hashStoreId = encrypt($store->id);
            $url = encrypt($click->exit_url);
            DB::commit();
            if ($request->ajax()) {
                return array(
                    'status' => JsonResponse::HTTP_OK,
                    'url' => route('click.redirect', [$hashStoreId, $url])
                );
            }
            return redirect()->route('click.redirect', $hashStoreId, $url);
        } catch (Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json([
                    'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                    'error' => 'Something went wrong. Try again'
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
            flash()->error('Something went wrong. Try again');
            return redirect()->back();
        }
    }
    public function decryptVoucher($voucher)
    {
        $voucherCode = decrypt($voucher);
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'voucherCode' => $voucherCode
        ], JsonResponse::HTTP_OK);
    }
}
