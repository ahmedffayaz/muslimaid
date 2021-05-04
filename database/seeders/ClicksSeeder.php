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

        foreach (range(1,150) as $index) {

            $click = ExitClick::create([
                'store_id'=>$faker->numberBetween(1,50),
                'user_id'=>$faker->numberBetween(1,50),
                'status'=>'pending',
                'exit_url'=>'#',
    
            ]);
           
	}
    }
}
