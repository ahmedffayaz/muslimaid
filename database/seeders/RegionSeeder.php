<?php

namespace Database\Seeders;

use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('regions')->truncate();
        Schema::enableForeignKeyConstraints();

        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\regions.csv');

        if (isset($csvToArray[0])) {
            $regions = [];
            $now = Carbon::parse(now())->format('Y-m-d H:i:s');
            foreach ($csvToArray as $region) {
                $region['id'] = (!isset($region['id']) ? reset($region) : $region['id']);
                if (
                    !arrayValueExists($region, 'id')
                    || !arrayValueExists($region, 'name')
                ) {
                    continue;
                }
                $regions[] = [
                    'id' => $region['id'],
                    'name' => $region['name'],
                    'created_at' => isset($region['created_at']) ? $region['created_at'] : $now,
                    'updated_at' => isset($region['updated_at']) ? $region['updated_at'] : $now,
                ];
            }
            foreach (array_chunk($regions, 500) as $regionsChunk) {
                Region::insert($regionsChunk);
            }
        }

        $now = Carbon::now();

    }
}
