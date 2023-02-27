<?php

namespace App\Http\Controllers\Website;

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
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

            // Get network ID
            if ($store->override_network) {
                if ($request->cashback_type == 'bonus_cashback') {
                    $cashbackId = decrypt($request->input('cashback_id'));
                    if (!empty($cashbackId)) {
                        $storeCashback = $store->cashback->findOrFail($cashbackId);
                        if (empty($storeCashback)) {
                            if ($request->ajax()) {
                                return response()->json([
                                    'status' => JsonResponse::HTTP_NOT_FOUND,
                                    'error' => 'Something went wrong. Try again'
                                ], JsonResponse::HTTP_NOT_FOUND);
                            }
                            flash()->error('Something went wrong. Try again');
                            return redirect()->back();
                        }
                        $networkId = $storeCashback->network_id;

                        if (!empty($storeCashback->tracking_url)) {
                            $trackingUrl = $storeCashback->tracking_url;
                        }

                        if (!empty($storeCashback->click_url)) {
                            $trackingUrl = $storeCashback->click_url;
                        }

                        $deeplinkUrl = !empty($storeCashback->deeplink_url) ? $storeCashback->deeplink_url : $store->deeplink_url;
                    }
                } elseif ($request->cashback_type == 'cashback') {
                    $networkId = $store->network->id;
                    $trackingUrl = $store->tracking_url;
                    $deeplinkUrl = $store->deeplink_url;
                }
            } else {
                $networkId = $store->network->id;
                $trackingUrl = $store->tracking_url;
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
                'status' => 'pending',
                'exit_url' => '#',
                'current_cashback_percentage' => $cashbackPercent
            ]);

            // Get click ref & deeplink identifier
            if ($store->override_network) {
                $clickIdentifier = $click->network->click_ref;
                $deeplinkIdentifier = $click->network->deeplink_identifier;
            } else {
                $clickIdentifier = optional($store)->tracking_url ? $store->network->click_ref : '';
                $deeplinkIdentifier  = optional($store)->deeplink_url ? $store->network->deeplink_identifier : '';
            }

            $click->exit_url = $trackingUrl . $clickIdentifier . $click->id . $deeplinkIdentifier. $deeplinkUrl;
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
                    'url' => route('site.cashback', [$hashStoreId, $url])
                );
            }
            return redirect()->route('site.cashback', $hashStoreId, $url);
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

    public function getCashback($hash, $url)
    {
        try {
            $storeId = decrypt($hash);
            $store = Store::find($storeId);
            $url = decrypt($url);

            return view('frontend.pages.exit', compact('store', 'url'));
        } catch (Throwable $th) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $th->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
