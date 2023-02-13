<?php

namespace Database\Seeders;

use App\Models\Charity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CharitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Schema::disableForeignKeyConstraints();
        DB::table('charities')->truncate();
        Schema::enableForeignKeyConstraints();

        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\charities.csv');
        $charities = [];
        $now = Carbon::now();
        foreach ($csvToArray as $charity) {
            !isset($charity['id']) ?  ($charity['id'] = reset($charity)) : '' ;
            $charities[] = [
                'id' => $charity['id'],
                'title' => $charity['title'],
                'country' => $charity['country'],
                'logo_type' => $charity['logo_type'],
                'logo_upload' => $charity['logo_upload'],
                'logo_link' => null,
                'charity_types_id' => $charity['charity_types_id'],
                'banner_type' => $charity['banner_type'],
                'banner_upload' => $charity['banner_upload'],
                'banner_link' => $charity['banner_link'],
                'description' => $charity['description'],
                'status' => $charity['status'],
                'created_at' => isset($charity['created_at']) ? $charity['created_at'] : $now,
                'updated_at' => isset($charity['updated_at']) ? $charity['updated_at'] : $now,
            ];
        }
        Charity::insert($charities);
    }
}
