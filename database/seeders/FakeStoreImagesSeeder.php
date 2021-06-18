<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Store;
use App\Models\StoreImage;

class FakeStoreImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $stores = Store::all();

        foreach ($stores as $store) {
            if(!$store->logo->count()){
            $storelogo = StoreImage::create([
                'store_id'=>$store->id,
                'title' => 'logo',
                // 'image' => $faker->image(public_path('storage/stores/images'),350,350,$faker->randomElement($array = array ('business','animals','sports','fashion')),null,true,null,false),
                'image' => $faker->numberBetween(1,20).'.png',
                'image_type'=>'store_logo',
                'is_uploaded'=>1
            ]);
        }
            
        }
    }
}
