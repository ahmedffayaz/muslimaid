<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use Throwable;
use App\Models\Store;
use App\Models\ExitClick;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Models\RedeemedVoucher;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClickController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
            'cashback_type' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $deeplinkUrl = '';
            $storeId = decrypt($request->input('store_id'));
            $store = Store::findOrFail($storeId);

            // Override network's store cashback
            if ($store->override_network && $request->cashback_type == 'bonus_cashback' && !empty($request->input('cashback_id'))) {
                $cashback = $store->cashback->findOrFail(decrypt($request->input('cashback_id')));

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

            if (!$cashbackPercent) {
                $cashbackPercent = 0;
            }

            $click = ExitClick::create([
                'store_id' => $storeId,
                'user_id' => auth()->user()->id ?? 1,
                'network_id' => $networkId,
                'exit_url' => '#',
                'current_cashback_percentage' => $cashbackPercent
            ]);

            $click->exit_url = $trackingUrl . $clickIdentifier . $click->id . $deeplinkIdentifier . $deeplinkUrl;
            $click->update();

            $url = encrypt($click->exit_url);

            if ($request->input('voucher_id')) {
                $redeemed = RedeemedVoucher::create([
                    'user_id' => auth()->user()->id ?? 1,
                    'voucher_id' => $request->input('voucher_id'),
                ]);
            }
            $hashStoreId = encrypt($store->id);
            DB::commit();
            if ($request->ajax()) {
                return array(
                    'status' => JsonResponse::HTTP_OK,
                    'url' => route('click.redirect', [$hashStoreId, $url])
                );
            }
            return redirect()->route('click.redirect', $hashStoreId, $url);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json([
                    'status' => JsonResponse::HTTP_NOT_FOUND,
                    'error' => 'Something went wrong. Try again'
                ], JsonResponse::HTTP_NOT_FOUND);
            }
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

    public function redirect(Request $request, $hash, $url)
    {
        try {
            $storeId = decrypt($hash);
            $store = Store::find($storeId);
            $url = decrypt($url);
            $cashbackId = isset($request->cashbackId) ? decrypt($request->cashbackId) : '';

            return view('frontend.stores.exit-click', compact('store', 'url', 'cashbackId'));
        } catch (Throwable $th) {
            if (request()->ajax()) {
                return response()->json([
                    'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                    'error' => $th->getMessage()
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
            flash()->error('Something went wrong. Try again');
            return redirect()->back();
        }
    }
}
