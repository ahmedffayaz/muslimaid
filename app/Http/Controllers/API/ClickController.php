<?php

namespace App\Http\Controllers\API;

use App\Models\Store;
use App\Models\ExitClick;
use App\Models\SiteSetting;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Sanctum\Sanctum;

class ClickController extends Controller
{
    use ApiResponser;

    public function getCashbackStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'store_id' => 'required',
                'cashback_type' => 'required'
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
            $store = Store::findOrFail($request->store_id);
            $cashbackId = $request->input('cashback_id');
            $cashbackType = $request->input('cashback_type');

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

            if (empty($deeplinkIdentifier) && !empty($deeplinkUrl)) $deeplinkIdentifier = '&url=';

            // Cashback percentage
            $customCashbackPercentage = $store->custom_cashback_percentage;

            $cashbackPercent = $customCashbackPercentage ? $customCashbackPercentage : SiteSetting::where('type', 'cashback_percentage')->first()->value;

            if (!$cashbackPercent) $cashbackPercent = 0;

            $adminUser = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->first();

            $user = $request->hasHeader('Authorization') ? $request->user('sanctum') : $adminUser;

            $click = ExitClick::create([
                'store_id' => $store->id,
                'user_id' => $user->id,
                'network_id' => $networkId,
                'exit_url' => '#',
                'current_cashback_percentage' => $cashbackPercent
            ]);

            $click->exit_url = $trackingUrl . $clickIdentifier . $click->id . $deeplinkIdentifier . $deeplinkUrl;
            $click->update();

            $hashUrl = encrypt($click->exit_url);
            $clickId = $click->id;
            $userRefId = $user->short_ref_id;

            DB::commit();

            $url = !empty($request->cashback_id)
                ? route('click.redirect', [$store->id, $userRefId, $hashUrl]) . '?click_id=' . $clickId . '&cashback_id=' . $request->cashback_id
                : $url = route('click.redirect', [$store->id, $userRefId, $hashUrl]) . '?click_id=' . $clickId;

            $data = [
                'status' => 200,
                'message' => 'Success',
                'data' => [
                    'url' => $url
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

    public function getCouponCashbackStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'store_id' => 'required|numeric',
                'voucher_id' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 406,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ];
                return response()->json($data, 406);
            }

            $store = Store::findOrFail($request->input('store_id'));
            $voucher = Voucher::findOrFail($request->input('voucher_id'));

            $trackingUrl = $voucher->tracking_url ? $voucher->tracking_url : $voucher->store->tracking_url;
            $clickIdentifier = $voucher->store->network->click_ref ? $voucher->store->network->click_ref : '';
            $deeplinkUrl = $voucher->deeplink_url ? $voucher->deeplink_url : $voucher->store->deeplink_url;
            $deeplinkIdentifier = $voucher->store->network->deeplink_identifier ? $voucher->store->network->deeplink_identifier : '';

            if (empty($deeplinkIdentifier) && !empty($deeplinkUrl)) $deeplinkIdentifier = '&url=';

            $customCashbackPercentage = $store->custom_cashback_percentage;
            $cashbackPercent = $customCashbackPercentage ? $customCashbackPercentage : SiteSetting::where('type', 'cashback_percentage')->first()->value;

            if (!$cashbackPercent) $cashbackPercent = 0;

            $adminUser = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->first();

            $user = $request->hasHeader('Authorization') ? $request->user('sanctum') : $adminUser;

            DB::beginTransaction();

            $click = ExitClick::create([
                'store_id' => $store->id,
                'user_id' => $user->id,
                'network_id' => $store->network->id,
                'exit_url' => '#',
                'voucher_id' => $voucher->id,
                'current_cashback_percentage' => $cashbackPercent
            ]);

            $click->exit_url = $deeplinkUrl != null ? $trackingUrl . $clickIdentifier . $click->id . $deeplinkIdentifier . $deeplinkUrl : $trackingUrl . $clickIdentifier . $click->id;
            $click->update();

            $clickId = $click->id;
            $hashUrl = encrypt($click->exit_url);
            $userRefId = $user->short_ref_id;

            DB::commit();

            $url = !empty($request->cashback_id)
                ? route('click.redirect', [$store->id, $userRefId, $hashUrl]) . '?click_id=' . $clickId . '&voucher_id=' . $voucher->id
                : $url = route('click.redirect', [$store->id, $userRefId, $hashUrl]) . '?click_id=' . $clickId;

            $data = [
                'status' => 200,
                'message' => 'Success',
                'data' => [
                    'url' => $url
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
