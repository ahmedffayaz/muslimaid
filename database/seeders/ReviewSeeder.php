<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoreReview;
use App\Models\Store;
use Faker\Factory as Faker;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1,200) as $index) {


            $store = Store::findOrFail($faker->numberBetween(1,50));
            $review = StoreReview::create([
            'store_id' => $store->id,
            'reviewer' => $faker->name,
            'review' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
           
            
        ]); 
        }
    }
}
