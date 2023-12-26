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
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RevGlueStoresImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 900;
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
        $this->network = Network::where('name', 'RevGlue')->first();
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
                $this->processStore($rgStore);
            }
        } catch (Exception $e) {
            Log::error('Get error while import stores from RevGlue: ' . $e->getMessage());
        }
    }

    private function processStore($rgStore)
    {
        $slug = Str::slug($rgStore->store_title);
        $count = Store::select('id', 'slug')->where('slug', $slug)->count(); // Check if the slug already exists
        // If the slug already exists, append a unique identifier
        if ($count > 0)
            $slug = $slug . '-' . uniqid();

        try {
            $dbStore = Store::where(['network_id' => $this->network->id, 'advertiser_id' => $rgStore->rg_store_id])->first();

            if (!$dbStore) {
                try {
                    DB::beginTransaction();
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

                    // Update store cashbacks
                    $this->storeCashback($newStore);
                    StoreCashback::where('store_id', $newStore->id)->update(['default' => false]);
                    $highestCashback = StoreCashback::where('store_id', $newStore->id)->orderByDesc('sale_commission')->first();
                    if (!empty($highestCashback)) $highestCashback->update(['default' => true]);
                    DB::commit();
                } catch (Exception $e) {
                    Log::error('Get error while import new stores from RevGlue API: ' . $e->getMessage());
                }
            } else {
                try {
                    if ($rgStore->status == 'active') {
                        DB::beginTransaction();
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
                            $this->storeCashback($dbStore); // Update store cashbacks
                        }
                        DB::commit();
                    }

                    if ($rgStore->status != 'active') {
                        DB::beginTransaction();
                        $dbStore->update(['status' => 'closed', 'network_status' => $rgStore->status]);
                        DB::commit();
                    }
                } catch (Exception $e) {
                    Log::error('Get error while update store from RevGlue API: ' . $e->getMessage());
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error processing store from RevGlue: ' . $e->getMessage());

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
                            $rgCategories = collect($response->categories);
                            $rgCategories->filter(function ($rgCategory) use ($explodeCategoryIds) {
                                return in_array($rgCategory->cashback_category_id, $explodeCategoryIds);
                            })->each(function ($rgCategry) use ($store) {
                                $importedCategory = $this->getOrCreateImportedCategory($rgCategry);
                                DB::table('category_store')->updateOrInsert(
                                    [
                                        'store_id' => $store->id,
                                        'network_category_id' => $importedCategory->id,
                                    ],
                                    [
                                        'category_id' => $importedCategory->mapped_to ?? 0,
                                    ]
                                );
                            });
                        }
                    } else if ($response->failed()) {
                        // Handle error for failed responses
                        Log::error('Error while importing network categories from RevGlue on response failed: ' . $response->status());
                    } else if ($response->clientError()) {
                        // Handle error for client responses
                        Log::error('Get client error while import network categories from RevGlue: ' . $response->clientError());
                    } else if ($response->serverError()) {
                        // Handle error for server responses
                        Log::error('Get server error while import network categories from RevGlue: ' . $response->serverError());
                    }
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function getOrCreateImportedCategory($rgCategory) {
        return Cache::remember('imported_category_' . $rgCategory->cashback_category_id, now()->addHours(24), function () use ($rgCategory) {
            $importedCategory = ImportedCategory::firstOrNew(['name' => $rgCategory->cashback_category_title, 'network_id' => $this->network->id]);

            if (!$importedCategory->exists) {
                $importedCategory->save();

                $dbCategory = Category::firstOrNew(['name' => $importedCategory->name, 'network_id' => $this->network->id]);
                $importedCategory->mapped_to = $dbCategory->id ?? 0;
                $importedCategory->save();
            }

            return $importedCategory;
        });
    }

    private function storeCashback($store)
    {
        $response = Http::get('https://www.revglue.com/partner/stores_cashback/' . $this->siteSettings['revglue_api_key'] . '/json');

        if ($response->successful()) {
            $response = $response->object()->response;
            if ($response->success && !empty($response->stores)) {
                try {
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
                            Log::error('Get error while make default cashback in RevGlue API: ' . $e->getMessage());
                        }
                    }
                } catch (Exception $e) {
                    Log::error('Get error while import store cashbacks from RevGlue API: ' . $e->getMessage());
                }
            }
        } else if ($response->failed()) {
            // Handle error for failed responses
            Log::error('Error while importing store cashback from RevGlue on response failed: ' . $response->status());
        } else if ($response->clientError()) {
            // Handle error for client responses
            Log::error('Get client error while import store cashback from RevGlue: ' . $response->clientError());
        } else if ($response->serverError()) {
            // Handle error for server responses
            Log::error('Get server error while import store cashback from RevGlue: ' . $response->serverError());
        }
    }
}
