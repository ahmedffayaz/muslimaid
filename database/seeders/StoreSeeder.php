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
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) {
            $storeName = $faker->company;

            $store = Store::create([
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
            ]);

            StoreImage::create([
                'store_id' => $store->id,
                'title' => 'logo',
                'image' => $faker->numberBetween(1, 20) . '.png',
                'image_type' => 'store_logo',
                'is_uploaded' => 1,
                'is_fake' => 1
            ]);

            foreach (range(1, 10) as $i) {
                StoreCashback::create([
                    'store_id' => $store->id,
                    'type' => 'percentage',
                    'detail' => $faker->text(100),
                    'network_detail' => $faker->text(100),
                    'sale_commission' => $faker->numberBetween(1, 10),
                    'default' => 1,
                    'click_url' => $faker->url,
                ]);
            }

            DB::table('category_store')->insert([
                'store_id' => $store->id,
                'category_id' => $faker->numberBetween(1, 5)
            ]);
        }
    }
}
