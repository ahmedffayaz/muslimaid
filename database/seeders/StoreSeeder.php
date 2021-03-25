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

        foreach (range(1,10) as $index) {

            $store = new Store();
            $store->network_id = 1;
            $store->name = $faker->company;
            $store->tracking_url = '#';
            $store->store_url = '#';
            $store->save();	 
            
            $cashback = new StoreCashback();
            $cashback->store_id = $store->id;
            $cashback->type = 'percentage';
            $cashback->value = 2;            
            $cashback->save();	

            DB::table('category_store')->insert([
	           'store_id'=>$store->id,
               'category_id'=> $faker->numberBetween(1,5)
	        ]);
           
	}
    }
}
