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
        Schema::disableForeignKeyConstraints();
        DB::table('charity_types')->truncate();
        Schema::enableForeignKeyConstraints();

        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\charity_types.csv');
        $charityTypes = [];
        $now = Carbon::now();
        foreach ($csvToArray as $charityType) {
            !isset($charityType['id']) ?  ($charityType['id'] = reset($charityType)) : '';
            if (
                !arrayValueExists($charityType, 'id')
                || !arrayValueExists($charityType, 'title')
            ) {
                continue;
            }
            $charityTypes[] = [
                'id' => $charityType['id'],
                'title' => $charityType['title'],
                'status' => arrayValueExists($charityType, 'status') ? $charityType['status'] :  1,
                'created_at' => arrayValueExists($charityType, 'created_at') ? dbDate($charityType['created_at']) : $now,
                'updated_at' => arrayValueExists($charityType, 'updated_at') ? dbDate($charityType['updated_at']) : $now,
            ];
        }
        CharityType::insert($charityTypes);
    }
}
