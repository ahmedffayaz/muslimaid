<?php

namespace Database\Seeders;

use App\Models\CharityType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class CharityTypeSeeder extends Seeder
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
        DB::table('charity_types')->truncate();
        Schema::enableForeignKeyConstraints();

        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\charity_types.csv');
        $charity_types = [];
        $now = Carbon::now();
        foreach($csvToArray as $charity_type){
            !isset($charity_type['id']) ?  ($charity_type['id'] = reset($charity_type)) : '' ;
            $charity_types[] = [
                'id' => $charity_type['id'],
                'title' => $charity_type['title'],
                'status' => $charity_type['status'],
                'created_at' => isset($charity_type['created_at']) ? $charity_type['created_at'] : $now,
                'updated_at' => isset($charity_type['updated_at']) ? $charity_type['updated_at'] : $now,
            ];
        }
        CharityType::insert($charity_types);
    }
}
