<?php

namespace Database\Seeders;

use App\Models\StoreAddress;
use Carbon\Carbon;
use App\Models\Store;
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
        $store_id_data = Store::pluck('id')->toArray();
        Schema::disableForeignKeyConstraints();
        DB::table('store_addresses')->truncate();
        Schema::enableForeignKeyConstraints();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\store_addresses.csv');
        if (isset($csvToArray[0])) {
            $storeAddresses = [];
            $now = Carbon::now();
            foreach ($csvToArray as $i => $storeAddress) {
                $storeAddress['id'] = (!isset($storeAddress['id']) ? reset($storeAddress) : $storeAddress['id']);
                if (
                    !arrayValueExists($storeAddress, 'store_id')
                    || !arrayValueExists($storeAddress, 'city')
                    || !arrayValueExists($storeAddress, 'postal_code')
                    || !arrayValueExists($storeAddress, 'latitude')
                    || !arrayValueExists($storeAddress, 'longitude')
                    || !arrayValueExists($storeAddress, 'address')
                    || !in_array($storeAddress['store_id'], $store_id_data)
                ) {
                    continue;
                }
                $storeAddresses[] = [
                    'id' => $i + 1,
                    'store_id' => $storeAddress['store_id'],
                    'city' => $storeAddress['city'],
                    'postal_code' => $storeAddress['postal_code'],
                    'latitude' => $storeAddress['latitude'],
                    'longitude' => $storeAddress['longitude'],
                    'address' => $storeAddress['address'],
                    'created_at' => arrayValueExists($storeAddress, 'created_at') ? dbDate($storeAddress['created_at']) : $now,
                    'updated_at' => arrayValueExists($storeAddress, 'updated_at') ? dbDate($storeAddress['updated_at']) : $now,

                ];
            }
            foreach (array_chunk($storeAddresses, 500) as $storeAddressesChunk) {
                StoreAddress::insert($storeAddressesChunk);
            }
        }
    }
}
