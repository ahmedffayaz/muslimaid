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

        $subSectorsRows = array_column($csvToArray, 'subSectors');
        $dbCategories = ImportedCategory::whereNetworkId($this->network->id)->get()->toArray();
        $newCategories = [];
        $now = Carbon::now()->format('Y-m-d H:i:s');

        foreach ($subSectorsRows as $subSectorsRow) {
            if (empty($subSectorsRow)) continue;

            $subSectors = explode('|', $subSectorsRow);

            foreach ($subSectors as $subSector) {
                if (
                    !in_array($subSector, array_column($newCategories, 'name'))
                    && !in_array($subSector, array_column($dbCategories, 'name'))
                ) {
                    $newCategories[] = [
                        'network_id' => $this->network->id,
                        'name' => $subSector,
                        'parent_id' => 0,
                        'mapped_to' => 0,
                        'created_at' => $now,
                    ];
                }
            }
        }

        if (count($newCategories) > 0) {
            $categoriesChunks = count($newCategories) > 100
                ? array_chunk($newCategories, 100)
                : [$newCategories];

            foreach ($categoriesChunks as $categoriesChunk) {
                ImportedCategory::insert($categoriesChunk);
            }
        }

        // TODO: assign categories to stores

        Storage::delete($this->csvFilePath);
    }
}
