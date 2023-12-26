<?php

namespace App\Jobs;

use Exception;
use App\Models\Store;
use App\Models\Network;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use App\Models\ImporterSetting;
use App\Jobs\RevGlueStoresImporter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use App\Jobs\RevGlueCategoriesImporter;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\RevGlueStoreVouchersImporter;
use App\Jobs\RevGlueUserCashbacksImporter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RevGlueImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $network;
    protected $siteSettings;
    protected $importerSetting;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->network = Network::where('name', 'RevGlue')->first();
        $this->siteSettings = SiteSetting::latest()->get()->pluck('value', 'type');
        $this->importerSetting = ImporterSetting::whereNetworkId($this->network->id)->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->importerSetting->import_categories == 1) $this->importCategories();
        if ($this->importerSetting->import_stores == 1) $this->importStores();
        if ($this->importerSetting->import_cashbacks == 1) $this->importUserCashbacks();
        if ($this->importerSetting->import_vouchers == 1) $this->importVouchers();
    }

    /**
     * Import stores from RevGlue network.
     *
     * @return void
     */
    private function importStores()
    {
        try {
            $response = Http::get('https://www.revglue.com/partner/cashback_stores/' . $this->siteSettings['revglue_api_key'] . '/json');

            if ($response->successful()) {
                $response = $response->object()->response;
                if ($response->success) {
                    // Use chunk to process stores in smaller batches
                    collect($response->stores)->chunk(5)->each(function ($chunk, $key) {
                        RevGlueStoresImporter::dispatch($chunk)->delay(now()->addMinutes($key + 1));
                    });
                } else {
                    Log::error('RevGlue store status got failed');
                }
            } else if ($response->failed()) {
                // Determine if the status code is >= 400
                Log::error('Get error while import stores from RevGlue on response failed: ' . $response->failed());
            } else if ($response->clientError()) {
                // Determine if the response has a 400 level status code
                Log::error('Get client error while import stores from RevGlue: ' . $response->clientError());
            } else if ($response->serverError()) {
                // Determine if the response has a 500 level status code
                Log::error('Get server error while import stores from RevGlue: ' . $response->serverError());
            }
        } catch (Exception $e) {
            Log::error('Get error while import stores from RevGlue: ' . $e->getMessage());
        }
    }

    /**
     * For importing user cashbacks
     */
    private function importUserCashbacks()
    {
        try {
            $response = Http::get('https://www.revglue.com/partner/get_revembed_commission/' . $this->siteSettings['revglue_api_key'] . '/UE4Wr8O9Nl7BURIBGIVY8HSJhwNi7RXYiMPc06puPVkoh9Y6xC');
            if ($response->successful()) {
                $response = $response->object()->response;
                if ($response->success) {
                    collect($response->commissions)->chunk(15)->each(function ($chunk, $key) {
                        RevGlueUserCashbacksImporter::dispatch($chunk)->delay(now()->addMinutes($key + 1));
                    });
                } else {
                    Log::error('RevGlue user cashbacks status got failed');
                }
            } else if ($response->failed()) {
                // Determine if the status code is >= 400
                Log::error('Get error while import user cashbacks from RevGlue on response failed: ' . $response->failed());
            } else if ($response->clientError()) {
                // Determine if the response has a 400 level status code
                Log::error('Get client error while import user cashbacks from RevGlue: ' . $response->clientError());
            } else if ($response->serverError()) {
                // Determine if the response has a 500 level status code
                Log::error('Get server error while import user cashbacks from RevGlue: ' . $response->serverError());
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * For importing categories
     */
    private function importCategories()
    {
        try {
        $response = Http::get('https://www.revglue.com/partner/cashback_categories/' . $this->siteSettings['revglue_api_key'] . '/json');

            if ($response->successful()) {
                $response = $response->object()->response;
                if ($response->success) {
                    // Use chunk to process stores in smaller batches
                    collect($response->categories)->chunk(150)->each(function ($chunk, $key) {
                        RevGlueCategoriesImporter::dispatch($chunk)->delay(now()->addMinutes($key + 1));
                    });
                } else {
                    Log::error('RevGlue categories status got failed');
                }
            } else if ($response->failed()) {
                // Determine if the status code is >= 400
                Log::error('Get error while import categories from RevGlue: ' . $response->failed());
            } else if ($response->clientError()) {
                // Determine if the response has a 400 level status code
                Log::error('Get client error while import categories from RevGlue: ' . $response->clientError());
            } else if ($response->serverError()) {
                // Determine if the response has a 500 level status code
                Log::error('Get server error while import categories from RevGlue: ' . $response->serverError());
            }
        } catch (Exception $e) {
            Log::error('Get Error while import categories from RevGlue: ' . $e->getMessage());
        }
    }

    /**
     * For importing vouchers
     */
    private function importVouchers()
    {
        // We have API call limit for '20' calls per minute (for safe side make it '15'), so we divide and conquer
        $storesChunks = Store::where('network_id', $this->network->id)->orderBy('id', 'DESC')->get()->chunk(15);

        foreach ($storesChunks as $key => $storesChunk) {
            RevGlueStoreVouchersImporter::dispatch($storesChunk)->delay(now()->addMinutes($key + 1));
        }
    }
}
