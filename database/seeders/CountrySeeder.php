<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('countries')->truncate();
        Schema::enableForeignKeyConstraints();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\countries.csv');
        if (isset($csvToArray[0])) {
            $countries = [];
            $now = Carbon::parse(now())->format('Y-m-d H:i:s');
            foreach ($csvToArray as $country) {
                $country['id'] = (!isset($country['id']) ? reset($country) : $country['id']);
                if (
                    !arrayValueExists($country, 'id')
                    || !arrayValueExists($country, 'name')
                ) {
                    continue;
                }
                $countries[] = [
                    'id' => $country['id'],
                    'name' => $country['name'] ,
                    'iso_code' => $country['iso_code'],
                    'region_id' => $country['region_id'],
                    'currency_id' => $country['currency_id'],
                    'upload_type' => $country['upload_type'],
                    'type_value' => $country['type_value'],
                    'status' => ($country['status'] == 1) ? '1': '0',
                    'created_at' => arrayValueExists($country, 'created_at') ? dbDate($country['created_at']) : $now,
                    'updated_at' => arrayValueExists($country, 'updated_at') ? dbDate($country['updated_at']) : $now,

                ];
            }
            foreach (array_chunk($countries, 500) as $countriesChunk) {
                Country::insert($countriesChunk);
            }
        }
    }
}
