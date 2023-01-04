<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use App\Models\ImportedCategory;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class AwinCategoryImporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $network = null;
    private $csvFilePath = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($network, $csvFilePath)
    {
        $this->network = $network;
        $this->csvFilePath = $csvFilePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $header = null;
        $csvToArray = [];

        if (($handle = fopen(str_replace('/', '\\', storage_path('\\app\\' . $this->csvFilePath)), 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) $header = $row;
                else $csvToArray[] = array_combine($header, $row);
            }

            fclose($handle);
        }

        $parentCategories = [];

        foreach (array_column($csvToArray, 'parentSectors') as $parentSectors) {
            if (empty($parentSectors)) continue;

            foreach (explode('|', $parentSectors) as $parentSector) {
                if (!in_array($parentSector, $parentCategories)) {
                    $parentCategories[] = $parentSector;
                }
            }
        }

        $allCategories = [];

        foreach ($parentCategories as $parentCategory) {
            $allCategories[$parentCategory] = [];
        }

        foreach ($csvToArray as $row) {
            if (empty($row['subSectors'])) continue;

            foreach (explode('|', $row['subSectors']) as $subSector) {
                if (empty($row['parentSectors'])) continue;

                foreach (explode('|', $row['parentSectors']) as $parentSector) {
                    if (!in_array($subSector, $allCategories[$parentSector])) {
                        $allCategories[$parentSector][] = [
                            'network_id' => $this->network->id,
                            'name' => $subSector,
                            'mapped_to' => 0,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                    }
                }
            }
        }

        foreach ($allCategories as $parentCategory => $subCategories) {
            $parent = ImportedCategory::create([
                'network_id' => $this->network->id,
                'name' => $parentCategory,
                'parent_id' => 0,
                'mapped_to' => 0,
            ]);
            
            foreach ($subCategories as $subCategory) {
                ImportedCategory::create([
                    'network_id' => $this->network->id,
                    'name' => $subCategory['name'],
                    'parent_id' => $parent->id,
                    'mapped_to' => 0,
                ]);
            }
        }

        Storage::delete($this->csvFilePath);
    }
}
