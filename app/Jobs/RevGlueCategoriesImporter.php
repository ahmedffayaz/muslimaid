<?php

namespace App\Jobs;

use App\Models\Network;
use App\Models\Category;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Log;

class RevGlueCategoriesImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $network;
    protected $chunk;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chunk)
    {
        $this->network = Network::whereName('RevGlue')->first();
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
            if (!empty($this->chunk)) {
                $cashbackCategoryIds = [];

                foreach ($this->chunk as $category) {
                    if (isset($category->cashback_category_id)) {
                        $cashbackCategoryIds[] = $category->cashback_category_id;
                    }
                }

                if (!empty($cashbackCategoryIds)) {
                    $dbCategories = Category::where('network_id', $this->network->id)
                        ->whereIn('advertiser_id', $cashbackCategoryIds)
                        ->pluck('advertiser_id')
                        ->toArray();

                    foreach ($this->chunk as $category) {
                        $rgCategoryId = $category->cashback_category_id;
                        // Find the parent_id based on advertiser_id
                        $parentCategoryId = Category::where('network_id', $this->network->id)
                            ->where('advertiser_id', $category->parent_category_id)
                            ->value('id');

                        // If parent ID is not found, set it to 0
                        $parentCategoryId = $parentCategoryId ?? 0;

                        if (!empty($dbCategories) && in_array($rgCategoryId, $dbCategories)) {
                            Category::where('network_id', $this->network->id)
                                ->where('advertiser_id', $category->cashback_category_id)
                                ->update([
                                    'parent_id' => $parentCategoryId, // Use the retrieved parent_id
                                    'name' => $category->cashback_category_title,
                                    'description' => $category->description,
                                    'logo_type' => 'link',
                                    'logo_link' => $category->small_icon,
                                    'banner_type' => 'link',
                                    'banner_link' => $category->banner,
                                    'status' => $category->status == 'active' ? 1 : 0
                                ]);
                        } else {
                            Category::create([
                                'network_id' => $this->network->id,
                                'advertiser_id' => $category->cashback_category_id,
                                'parent_id' => $parentCategoryId, // Use the retrieved parent_id
                                'name' => $category->cashback_category_title,
                                'slug' => Str::slug($category->cashback_category_title),
                                'description' => $category->description,
                                'sort' => 1,
                                'logo_type' => 'link',
                                'logo_link' => $category->small_icon,
                                'banner_type' => 'link',
                                'banner_link' => $category->banner,
                                'status' => $category->status == 'active' ? 1 : 0,
                                'visibility' => 'visible',
                                'is_map_enable' => 0
                            ]);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Log::error('Get error on save RevGlue categories in DB: ' . $e->getMessage());
        }
    }
}
