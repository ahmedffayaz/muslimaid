<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Store;
use App\Models\StoreCashback;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


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

        foreach (range(1,50) as $index) {

            $store = new Store();
            $store->network_id = 1;
            $store->name = $faker->company;
            $store->slug = \Str::slug($store->name);
            $store->tracking_url = $faker->url;
            $store->store_url = $faker->url;
            $store->save();	 

            foreach (range(1,3) as $i){
                $cashback = new StoreCashback();
                $cashback->store_id = $store->id;
                $cashback->type = 'percentage';
                $cashback->detail = $faker->text($maxNbChars = 100);
                $cashback->network_detail = $faker->text($maxNbChars = 100);
                $cashback->sale_commission = $faker->numberBetween(1,10).'%';
                $cashback->save();
            }
            	

            DB::table('category_store')->insert([
	           'store_id'=>$store->id,
               'category_id'=> $faker->numberBetween(1,5)
	        ]);
           
	}
    }
}
