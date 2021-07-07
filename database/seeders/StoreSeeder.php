<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Store;
use App\Models\StoreCashback;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\StoreImage;



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

        foreach (range(1,1000) as $index) {

            $store = new Store();
            $store->network_id = 1;
            $store->name = $faker->company;
            $store->slug = \Str::slug($store->name);
            $store->tracking_url = $faker->url;
            $store->store_url = $faker->url;
            $store->status ='active';
            $store->is_fake =1;
            $store->description = $faker->text($maxNbChars = 500);  
            $store->feature_homepage = $faker->randomElement($array = array ('1','0'));
            $store->feature_sidebar = $faker->randomElement($array = array ('1','0'));
            $store->save();	 

            $storelogo = StoreImage::create([
                'store_id'=>$store->id,
                'title' => 'logo',
                'image' => $faker->numberBetween(1,20).'.png',
                'image_type'=>'store_logo',
                'is_uploaded'=>1,
                'is_fake' =>1
            ]);

            foreach (range(1,10) as $i){
                $cashback = new StoreCashback();
                $cashback->store_id = $store->id;
                $cashback->type = 'percentage';
                $cashback->detail = $faker->text($maxNbChars = 100);
                $cashback->network_detail = $faker->text($maxNbChars = 100);
                $cashback->sale_commission = $faker->numberBetween(1,10);
                $cashback->default= 1;
                $cashback->click_url = $faker->url;
                $cashback->save();
            }
            	

            DB::table('category_store')->insert([
	           'store_id'=>$store->id,
               'category_id'=> $faker->numberBetween(1,5)
	        ]);
           
	}
    }
}
