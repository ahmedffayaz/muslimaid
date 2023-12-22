<?php

namespace App\Jobs;

use App\Models\Network;
use App\Models\Category;
use Illuminate\Bus\Queueable;
use App\Models\ImportedCategory;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Log;

class RevGlueStoreCategoriesImporter implements ShouldQueue
// class RevGlueStoreCategoriesImporter
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    // use Dispatchable, SerializesModels;

    protected $network;
    protected $chunk;
    protected $explodeCategoryIds;
    protected $store;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chunk, $store, $explodeCategoryIds)
    {
        $this->network = Network::whereName('RevGlue')->first();
        $this->chunk = $chunk;
        $this->store = $store;
        $this->explodeCategoryIds = $explodeCategoryIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {Log::info($this->chunk);
        try {
            foreach ($this->chunk as $rgCategory) {
                foreach ($this->explodeCategoryIds as $explodeCategoryId) {
                    if ($rgCategory['cashback_category_id'] == $explodeCategoryId) {
                        $importedCategory = ImportedCategory::where('name', $rgCategory['cashback_category_title'])->first();
                        if (!$importedCategory) {
                            $importedCategory = new ImportedCategory();
                            $importedCategory->name = $rgCategory['cashback_category_title'];
                            $importedCategory->network_id = $this->network->id;
                            $importedCategory->save();

                            $dbCategory = Category::select('id', 'name', 'network_id')->where('name', $importedCategory->name)->where('network_id', $this->network->id)->first();
                            $importedCategory->mapped_to = !empty($dbCategory->id) ? $dbCategory->id : 0;
                            $importedCategory->save();
                        }
                        DB::table('category_store')->insert([
                            'store_id' => $this->store->id,
                            'category_id' => $importedCategory->mapped_to ?? 0,
                            'network_category_id' => $importedCategory->id,
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error('Get error while import store and save network categories from RevGlue: ' . $e->getMessage());
        }
    }
}
