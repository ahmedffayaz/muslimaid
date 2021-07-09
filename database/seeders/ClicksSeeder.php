<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExitClick;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ClicksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1,5000) as $index) {

            $click = ExitClick::create([
                'store_id'=>$faker->numberBetween(1,40),
                'user_id'=>$faker->numberBetween(1,1000),
                'status'=>'pending',
                'exit_url'=>'#',
    
            ]);
           
	}
    }
}
