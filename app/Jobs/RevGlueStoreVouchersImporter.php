<?php

namespace App\Jobs;

use Exception;
use Carbon\Carbon;
use App\Models\Network;
use App\Models\Voucher;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RevGlueStoreVouchersImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 900;
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
        $this->network = Network::where('name', 'RevGlue')->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::get('https://www.revglue.com/partner/coupons/' . $this->siteSettings['revglue_api_key'] . '/json');
        if ($response->successful()) {
            // Convert response from array to object
            $response = $response->object()->response;
            if ($response->success) {
                $coupons = $response->coupons;
                if (!empty($coupons)) {
                    try {
                        foreach ($this->stores as $store) {
                            foreach ($coupons as $rgCoupon) {
                                if ($store->advertiser_id == $rgCoupon->rg_store_id) {
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
                    } catch (Exception $e) {
                        Log::error('Unexpected error while importing vouchers from RevGlue: ' . $e->getMessage());
                    }
                }
            }
        } else if ($response->failed()) {
            // Handle error for failed responses
            Log::error('Error while importing vouchers from RevGlue: ' . $response->status());
        } else if ($response->clientError()) {
            // Handle error for client responses
            Log::error('Get client error while import vouchers from RevGlue: ' . $response->clientError());
        } else if ($response->serverError()) {
            // Handle error for server responses
            Log::error('Get server error while import vouchers from RevGlue: ' . $response->serverError());
        }
    }
}
