<?php

namespace App\Jobs;

use Exception;
use App\Models\Network;
use App\Models\Category;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use App\Models\ImportedCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RevGlueNetworkCategoriesImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chunkStores;
    protected $network;
    protected $siteSettings;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chunkStores)
    {
        $this->chunkStores = $chunkStores;
        $this->network = Network::where('name', 'RevGlue')->first();
        $this->siteSettings = SiteSetting::latest()->get()->pluck('value', 'type');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            foreach ($this->chunkStores as $store) {
                $rgStores = Http::get('https://www.revglue.com/partner/cashback_stores/' . $this->siteSettings['revglue_api_key'] . '/json');
                if ($rgStores->successful()) {
                    $rgStoresResponse = $rgStores->object()->response;
                    if ($rgStoresResponse->success) {
                        foreach ($rgStoresResponse->stores as $rgStore) {
                            if (!empty($rgStore->cashback_category_ids)) {
                                $rgStoreCategoriesId = $rgStore->cashback_category_ids;
                                $explodeCategoryIds = explode(',', $rgStoreCategoriesId);
                                if ($explodeCategoryIds) {
                                    $response = Http::get('https://www.revglue.com/partner/cashback_categories/' . $this->siteSettings['revglue_api_key'] . '/json');
                                    // Get RevGlue API Categories
                                    if ($response->successful()) {
                                        $response = $response->object()->response;
                                        if ($response->success) {
                                            foreach ($response->categories as $rgCategory) {
                                                foreach ($explodeCategoryIds as $explodeCategoryId) {
                                                    if ($rgCategory->cashback_category_id == $explodeCategoryId) {
                                                        $importedCategory = ImportedCategory::where('name', $rgCategory->cashback_category_title)->first();
                                                        if (!$importedCategory) {
                                                            $importedCategory = new ImportedCategory();
                                                            $importedCategory->name = $rgCategory->cashback_category_title;
                                                            $importedCategory->network_id = $this->network->id;
                                                            $importedCategory->save();

                                                            $dbCategory = Category::select('id', 'name', 'network_id')->where('name', $importedCategory->name)->where('network_id', $this->network->id)->first();
                                                            $importedCategory->mapped_to = !empty($dbCategory->id) ? $dbCategory->id : 0;
                                                            $importedCategory->save();
                                                        }
                                                        DB::table('category_store')->insert([
                                                            'store_id' => $store->id,
                                                            'category_id' => $importedCategory->mapped_to ?? 0,
                                                            'network_category_id' => $importedCategory->id,
                                                        ]);
                                                    }
                                                }
                                            }
                                        } else {
                                            Log::error('RevGlue network categories API status got failed');
                                        }
                                    } else if ($response->failed()) {
                                        // Determine if the status code is >= 400
                                        Log::error('Get error while import network categories from RevGlue: ' . $response->failed());
                                    } else if ($response->clientError()) {
                                        // Determine if the response has a 400 level status code
                                        Log::error('Get client error while import network categories from RevGlue: ' . $response->clientError());
                                    } else if ($response->serverError()) {
                                        // Determine if the response has a 500 level status code
                                        Log::error('Get server error while import network categories from RevGlue: ' . $response->serverError());
                                    }
                                }
                            }
                        }
                    } else {
                        Log::error('Get error on stores API while import network categories status got failed');
                    }
                } else if ($rgStores->failed()) {
                    // Determine if the status code is >= 400
                    Log::error('Get error on stores while import network categories from RevGlue: ' . $rgStores->failed());
                } else if ($rgStores->clientError()) {
                    // Determine if the response has a 400 level status code
                    Log::error('Get client error on stores while import network categories from RevGlue: ' . $rgStores->clientError());
                } else if ($rgStores->serverError()) {
                    // Determine if the response has a 500 level status code
                    Log::error('Get server error on stores while import network categories from RevGlue: ' . $rgStores->serverError());
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
