<?php

namespace App\Jobs;

use Exception;
use Carbon\Carbon;
use App\Models\Store;
use App\Models\Network;
use App\Models\Category;
use App\Models\StoreImage;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use App\Models\StoreCashback;
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

class RevGlueStoreImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $network;
    protected $siteSettings;
    protected $importerSetting;
    protected $chunk;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chunk)
    {
        $this->network = Network::whereName('RevGlue')->first();
        $this->siteSettings = SiteSetting::latest()->get()->pluck('value', 'type');
        $this->chunk = $chunk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            foreach ($this->chunk as $rgStore) {
                $slug = Str::slug($rgStore->store_title);
                $count = Store::where('slug', $slug)->count(); // Check if the slug already exists

                // If the slug already exists, append a unique identifier
                if ($count > 0) $slug = $slug . '-' . uniqid();

                $dbStore = Store::where('network_id', $this->network->id)->where('advertiser_id', $rgStore->rg_store_id)->first();
                if (!$dbStore) {
                    $newStore = Store::create([
                        'network_id' => $this->network->id,
                        'advertiser_id' => $rgStore->rg_store_id,
                        'name' => $rgStore->store_title,
                        'description' => $rgStore->store_description,
                        'slug' => $slug,
                        'tracking_url' => rtrim($rgStore->deeplink, '/'),
                        'store_url' => rtrim($rgStore->website_url, '/'),
                        'status' => 'pending review',
                        'status_description' => null,
                        'network_status' => $rgStore->status
                    ]);

                    // Save store images
                    $storeImageTypes = [
                        'store_logo_small' => ['title' => 'logo', 'property' => 'image_url'],
                        'store_logo_large' => ['title' => 'large logo', 'property' => 'store_icon_large'],
                        'store_banner_small' => ['title' => 'Cover', 'property' => 'store_banner_small'],
                        'store_banner_large' => ['title' => 'large cover', 'property' => 'store_banner_large'],
                    ];
                    $currentTime = Carbon::now();
                    foreach ($storeImageTypes as $imageType => $imageData) {
                        $storeImage = [
                            'store_id' => $newStore->id,
                            'title' => $imageData['title'],
                            'image' => empty($rgStore->{$imageData['property']}) ? (mt_rand(1, 20) . '.png') : $rgStore->{$imageData['property']},
                            'image_type' => $imageType,
                            'is_uploaded' => '',
                            'is_fake' => empty($rgStore->{$imageData['property']}) ? 1 : 0,
                            'created_at' => $currentTime,
                            'updated_at' => $currentTime,
                        ];
                        StoreImage::insert($storeImage);
                    }

                    $this->rgStoreCategories($rgStore, $newStore); // Save network categories
                    // $this->storeCashback($newStore); // Update store cashbacks
                } else {
                    if ($rgStore->status == 'active') {
                        // Update store
                        $dbStore->update([
                            'name' => $rgStore->store_title,
                            'description' => $rgStore->store_description,
                            'tracking_url' => rtrim($rgStore->deeplink, '/'),
                            'store_url' => rtrim($rgStore->website_url, '/')
                        ]);

                        $imageTypes = [
                            'store_logo_small' => 'image_url',
                            'store_logo_large' => 'store_icon_large',
                            'store_banner_small' => 'store_banner_small',
                            'store_banner_large' => 'store_banner_large',
                        ];

                        // Update store images
                        foreach ($imageTypes as $imageType => $property) {
                            $imageProperty = $rgStore->{$property};
                            $dbStore->images()->where('image_type', $imageType)->update(['image' => empty($imageProperty) ? (mt_rand(1, 20) . '.png') : $imageProperty]);
                        }

                        // Update network categories
                        if (!$dbStore->override_categories) {
                            DB::table('category_store')->where('store_id', $dbStore->id)->delete(); // delete store's category ids
                            $this->rgStoreCategories($rgStore, $dbStore); // update network categories
                            // $this->storeCashback($dbStore); // Update store cashbacks
                        }
                    }

                    if ($rgStore->status != 'active') $dbStore->update(['status' => 'closed', 'network_status' => $rgStore->status]);
                }
            }
        } catch (Exception $e) {
            Log::error('Get error while import stores from RevGlue: ' . $e->getMessage());
        }
    }

    private function rgStoreCategories($rgStore, $store)
    {
        try {
            if (!empty($rgStore->cashback_category_ids)) {
                $rgStoreCategoriesId = $rgStore->cashback_category_ids;
                $explodeCategoryIds = explode(',', $rgStoreCategoriesId);
                if ($explodeCategoryIds) {
                    // Get RevGlue API Categories
                    $response = Http::get('https://www.revglue.com/partner/cashback_categories/' . $this->siteSettings['revglue_api_key'] . '/json');
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
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function storeCashback($store)
    {
        try {
            $response = Http::get('https://www.revglue.com/partner/stores_cashback/' . $this->siteSettings['revglue_api_key'] . '/json');
            if ($response->successful()) {
                $response = $response->object()->response;
                if ($response->success) {
                    if (!empty($response->stores)) {
                        foreach ($response->stores as $cashback) {
                            if ($store->advertiser_id != $cashback->rg_store_id) continue;
                            StoreCashback::updateOrCreate([
                                'advertiser_id' => $cashback->store_cashback_id,
                                'network_id' => $this->network->id,
                                'store_id' => $store->id
                            ], [
                                'type' => $cashback->cashback_type,
                                'cashback_name' => null,
                                'image' => '#',
                                'click_url' =>  '#',
                                'sale_commission' => $cashback->cashback_value,
                                'currency' => null,
                                'detail' => $cashback->description,
                                'network_detail' => null
                            ]);
                            try {
                                setStoreDefaultCashback($store->id, false);
                            } catch (Exception $e) {
                                Log::error('Get error while make default cashback: ' . $e->getMessage());
                            }
                        }
                    }
                } else {
                    Log::error('RevGlue store cashbacks status got failed');
                }
            } else if ($response->failed()) {
                // Determine if the status code is >= 400
                Log::error('Get error while import store cashbacks from RevGlue on response failed: ' . $response->failed());
            } else if ($response->clientError()) {
                // Determine if the response has a 400 level status code
                Log::error('Get client error while import store cashbacks from RevGlue: ' . $response->clientError());
            } else if ($response->serverError()) {
                // Determine if the response has a 500 level status code
                Log::error('Get server error while import store cashbacks from RevGlue: ' . $response->serverError());
            }
        } catch (Exception $e) {
            Log::error('Get error while import store cashbacks from RevGlue: ' . $e->getMessage());
        }
    }
}
