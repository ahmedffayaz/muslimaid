<?php

namespace App\Http\Controllers\API;

use App\Models\Store;
use App\Models\ExitClick;
use App\Models\SiteSetting;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\RedeemedVoucher;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;




class ClickController extends Controller
{
    use ApiResponser;
    public function getCashbackStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'store_id' => 'required',
                'cashback_type' => 'required',
                'cashback_id' => 'required'
            ]);
            if ($validator->fails()) {
                $data = [
                    'status' => 406,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ];
                return response()->json($data, 406);
            }
            DB::beginTransaction();
            $deeplinkUrl = '';
            $storeId = $request->input('store_id');
            $user_id = $request->input('user_id');
            $store = Store::findOrFail($storeId);

            // Override network's store cashback
            if ($store->override_network && $request->cashback_type == 'bonus_cashback' && !empty($request->input('cashback_id'))) {
                $cashback = $store->cashback->findOrFail($request->input('cashback_id'));
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
                'user_id' => $user_id ?? 1,
                'network_id' => $networkId,
                'exit_url' => '#',
                'current_cashback_percentage' => $cashbackPercent
            ]);

            $click->exit_url = $trackingUrl . $clickIdentifier . $click->id . $deeplinkIdentifier . $deeplinkUrl;
            $click->update();

            $url = encrypt($click->exit_url);

            DB::commit();

            if ($request->input('voucher_id')) {
                $redeemed = RedeemedVoucher::create([
                    'user_id' => $user_id ?? 1,
                    'voucher_id' => $request->input('voucher_id'),
                ]);
            }
            $hashStoreId = encrypt($store->id);
            $data = [
                'status' => 200,
                'message' => 'Success',
                'data' => [
                    'url' => route('click.redirect', [$hashStoreId, $url]) . '?cashbackId=' . encrypt($request->cashback_id)
                ]
            ];
            return response()->json($data, 200);
        } catch (ModelNotFoundException $ex) { // Store not found
            DB::rollBack();
            $data = [
                'status' => 404,
                'message' => 'Store not found',
                'data' => []
            ];
            return response()->json($data, 404);
            dd($ex);
        } catch (Exception $ex) { // Anything that went wrong
            DB::rollBack();
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }
}
