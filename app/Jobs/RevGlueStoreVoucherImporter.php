<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Network;
use App\Models\Voucher;
use App\Models\SiteSetting;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Log;

// class RevGlueStoreVoucherImporter implements ShouldQueue
class RevGlueStoreVoucherImporter
{
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use Dispatchable, SerializesModels;

    private $stores;
    private $siteSettings;
    private $network;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($stores)
    {
        $this->stores = $stores;
        $this->siteSettings = SiteSetting::latest()->get()->pluck('value', 'type');
        $this->network = Network::whereName('RevGlue')->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $response = Http::get('https://www.revglue.com/partner/coupons/' . $this->siteSettings['revglue_api_key'] . '/json');
            if ($response->successful()) {
                $response = $response->object()->response;
                if ($response->success) {
                    foreach ($this->stores as $store) {
                        if (!empty($response->coupons)) {
                            foreach ($response->coupons as $rgCoupon) {
                                if ($store->advertiser_id != $rgCoupon->rg_store_id) continue;

                                Voucher::updateOrCreate([
                                    'advertiser_id' => $rgCoupon->coupons_id,
                                    'network_id' => $this->network->id,
                                    'store_id' => $store->id
                                ], [
                                    'name' => $rgCoupon->coupons_title,
                                    'description' => $rgCoupon->coupons_description,
                                    'tracking_url' => isset($rgCoupon->tracking_url) ? $rgCoupon->tracking_url : null,
                                    'deeplink_url' => $rgCoupon->deeplink,
                                    'promotion_type' => $rgCoupon->coupon_type == 'code' ? 'Coupon' : 'Sale/Discount',
                                    'coupon_code' => $rgCoupon->coupon_type == 'code'? $rgCoupon->coupon_code : null,
                                    'image' => null,
                                    'promotion_start_date' => Carbon::parse($rgCoupon->issue_date)->format('Y-m-d H:i:s'),
                                    'promotion_end_date' => Carbon::parse($rgCoupon->expiry_date)->format('Y-m-d H:i:s')
                                ]);
                            }
                        }
                    }
                } else {
                    Log::error('RevGlue coupons status got failed');
                }
            } else if ($response->failed()) {
                // Determine if the status code is >= 400
                Log::error('Get error while import coupons from RevGlue on response failed: ' . $response->failed());
            } else if ($response->clientError()) {
                // Determine if the response has a 400 level status code
                Log::error('Get client error while import coupons from RevGlue: ' . $response->clientError());
            } else if ($response->serverError()) {
                // Determine if the response has a 500 level status code
                Log::error('Get server error while import coupons from RevGlue: ' . $response->serverError());
            }
        } catch (Exception $e) {
            Log::error('Get error while import vouchers from RevGlue: ' . $e->getMessage());
        }
    }
}
