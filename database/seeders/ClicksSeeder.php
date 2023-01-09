<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExitClick;
use Faker\Factory as Faker;

class ClicksSeeder extends Seeder
{
    private $count = 1000;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $clicks = [];

        for ($i = 0; $i < $this->count; $i++) {
            $clicks[] = [
                'store_id' => $faker->numberBetween(1, 50),
                'user_id' => $faker->numberBetween(1, 1000),
                'status' => 'pending',
                'exit_url' => '#',
            ];
        }

        foreach (array_chunk($clicks, 500) as $clickChunk) {
            ExitClick::insert($clickChunk);
        }
    }
}
