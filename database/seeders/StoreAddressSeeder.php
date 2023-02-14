<?php

namespace Database\Seeders;

use App\Models\StoreAddress;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StoreAddressSeeder extends Seeder
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
        DB::table('store_addresses')->truncate();
        Schema::enableForeignKeyConstraints();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\store_addresses.csv');
        if(isset($csvToArray[0])){
            $store_addresses = [];
            $now = Carbon::now();
            foreach ($csvToArray as $store_address) {
                !isset($store_address['id']) ?  ($store_address['id'] = reset($store_address)) : '' ;
                $store_addresses = [
                    'id' => $store_address['id'],
                    'store_id' => $store_address['store_id'],
                    'city' => $store_address['city'],
                    'postal_code' => $store_address['postal_code'],
                    'latitude' => $store_address['latitude'],
                    'longitude' => $store_address['longitude'],
                    'address' => $store_address['address'], 
                    'created_at' => $store_address['created_at'],
                    'updated_at' => $store_address['updated_at'],
                  
                ];
                StoreAddress::insert($store_addresses);
            }
        }
    }
}
