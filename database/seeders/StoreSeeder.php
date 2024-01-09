<?php

namespace Database\Seeders;

use App\Models\Store;

use App\Models\StoreImage;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\Models\StoreCashback;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class StoreSeeder extends Seeder
{
    private $count = 50;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('stores')->truncate();
        Schema::enableForeignKeyConstraints();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\stores.csv');
        if (isset($csvToArray[0])) {
            $stores = [];
            $now = Carbon::parse(now())->format('Y-m-d H:i:s');
            foreach ($csvToArray as $store) {
                $store['id'] = (!isset($store['id']) ? reset($store) : $store['id']);
                if (
                    !arrayValueExists($store, 'id')
                    || !arrayValueExists($store, 'name')
                    || !arrayValueExists($store, 'network_id')
                ) {
                    continue;
                }
                $stores[] = [
                    'id' => $store['id'],
                    'network_id' => $store['network_id'] == '' ? 0 : $store['network_id'],
                    'advertiser_id' => null,
                    'name' => $store['name'],
                    'description' => $store['description'],
                    'slug' => Str::slug($store['name']),
                    'terms_conditions' => $store['terms_conditions'],
                    'extra_info' => null,
                    'tracking_url' => $store['tracking_url'],
                    'store_url' => $store['store_url'],
                    'network_status' => null,
                    'status_description' => null,
                    'override_cashback' => 1,
                    'override_network' => 1,
                    'override_categories' => 1,
                    'editor_pick' => 0,
                    'status' => ($store['status'] == 1) ? 'active': 'inactive',
                    'is_fake' =>  0,
                    'address' => $store['address'],
                    'city' => $store['city'],
                    'postal_code' => $store['postal_code'],
                    'latitude' => $store['latitude'],
                    'longitude' => $store['longitude'],
                    'rating' => 0,
                    'created_at' => arrayValueExists($store, 'created_at') ? dbDate($store['created_at']) : $now,
                    'updated_at' => arrayValueExists($store, 'updated_at') ? dbDate($store['updated_at']) : $now,
                    'deleted_at' => null,

                ];
            }
            foreach (array_chunk($stores, 500) as $storesChunk) {
                Store::insert($storesChunk);
            }
        }
    }
}
