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
        Schema::disableForeignKeyConstraints();
        DB::table('charities')->truncate();
        Schema::enableForeignKeyConstraints();

        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\charities.csv');
        $charities = [];
        $now = Carbon::now();
        foreach ($csvToArray as $charity) {
            !isset($charity['id']) ?  ($charity['id'] = reset($charity)) : '' ;
            if (
                !arrayValueExists($charity, 'id')
                || !arrayValueExists($charity, 'title')
                || !arrayValueExists($charity, 'country')
            ) {
                continue;
            }
            $charities[] = [
                'id' => $charity['id'],
                'title' => $charity['title'],
                'country' => $charity['country'],
                'logo_type' => arrayValueExists($charity, 'logo_type') ? $charity['logo_type'] : null,
                'logo_upload' => arrayValueExists($charity, 'logo_upload') ? $charity['logo_upload'] : null,
                'logo_link' => null,
                'charity_types_id' => $charity['charity_types_id'] == 0 ? $charity['charity_types_id'] : 7,
                'banner_type' => arrayValueExists($charity, 'banner_type') ? $charity['banner_type'] : null,
                'banner_upload' => arrayValueExists($charity, 'banner_upload') ? $charity['banner_upload'] : null,
                'banner_link' => arrayValueExists($charity, 'banner_link') ? $charity['banner_link'] : null,
                'description' => arrayValueExists($charity, 'description') ? $charity['description'] : null,
                'status' => arrayValueExists($charity, 'status') ? $charity['status'] :  1,
                'created_at' =>  arrayValueExists($charity, 'created_at') ? Carbon::parse($charity['created_at'])->format('Y-m-d H:i:s') : $now,
                'updated_at' =>  arrayValueExists($charity, 'updated_at') ? Carbon::parse($charity['updated_at'])->format('Y-m-d H:i:s') : $now,
            ];
        }
        Charity::insert($charities);
    }
}
