<?php

namespace Database\Seeders;

use App\Models\StoreAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;

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
        if (isset($csvToArray[0])) {
            $storeAddresses = [];
            foreach ($csvToArray as $storeAddress) {
                $storeAddress['id'] = (!isset($storeAddress['id']) ? reset($storeAddress) : $storeAddress['id']);
                $storeAddresses[] = [
                    'id' => $storeAddress['id'],
                    'store_id' => $storeAddress['store_id'],
                    'city' => $storeAddress['city'],
                    'postal_code' => $storeAddress['postal_code'],
                    'latitude' => $storeAddress['latitude'],
                    'longitude' => $storeAddress['longitude'],
                    'address' => $storeAddress['address'],
                    'created_at' => $storeAddress['created_at'],
                    'updated_at' => $storeAddress['updated_at'],

                ];
            }
            foreach (array_chunk($storeAddresses, 500) as $storeAddressesChunk) {
                StoreAddress::insert($storeAddressesChunk);
            }
        }
    }
}
