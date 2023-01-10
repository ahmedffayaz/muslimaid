<?php

namespace Database\Seeders;

use App\Models\Store;

use App\Models\StoreImage;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\Models\StoreCashback;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        $faker = Faker::create();

        $stores = [];
        $storesImages = [];
        $storesCashbacks = [];
        $categoriesStores = [];

        for ($i = 1; $i <= $this->count; $i++) {
            $storeName = $faker->company;

            $stores[] = [
                'network_id' => 1,
                'name' => $storeName,
                'slug' => Str::slug($storeName),
                'tracking_url' => $faker->url,
                'store_url' => $faker->url,
                'status' => 'active',
                'is_fake' => 1,
                'description' => $faker->text(500),
                'feature_homepage' => $faker->randomElement([1, 0]),
                'feature_sidebar' => $faker->randomElement([1, 0]),
            ];

            $storesImages[] = [
                'store_id' => $i,
                'title' => 'logo',
                'image' => $faker->numberBetween(1, 20) . '.png',
                'image_type' => 'store_logo',
                'is_uploaded' => 1,
                'is_fake' => 1,
            ];

            foreach (range(1, 10) as $index) {
                $storesCashbacks[] = [
                    'store_id' => $i,
                    'type' => 'percentage',
                    'detail' => $faker->text(60),
                    'network_detail' => $faker->text(60),
                    'sale_commission' => $faker->numberBetween(1, 10),
                    'default' => 1,
                    'click_url' => $faker->url,
                ];
            }

            $categoriesStores[] = [
                'store_id' => $i,
                'category_id' => $faker->numberBetween(1, 5)
            ];
        }

        foreach (array_chunk($stores, 500) as $storesChunk) {
            Store::insert($storesChunk);
        }

        foreach (array_chunk($storesImages, 500) as $storesImagesChunk) {
            StoreImage::insert($storesImagesChunk);
        }

        foreach (array_chunk($storesCashbacks, 500) as $storesCashbacksChunk) {
            StoreCashback::insert($storesCashbacksChunk);
        }

        foreach (array_chunk($categoriesStores, 500) as $categoriesStoresChunk) {
            DB::table('category_store')->insert($categoriesStoresChunk);
        }
    }
}
