<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExitClick;
use App\Models\Network;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
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
        $storesCount = Store::count();
        $usersCount = User::count();
        $networkCount = Network::count();

        $now = Carbon::now();

        $clicks = [];

        for ($i = 0; $i < $this->count; $i++) {
            $clicks[] = [
                'store_id' => $faker->numberBetween(1, $storesCount),
                'user_id' => $faker->numberBetween(1, $usersCount),
                'network_id' => $faker->numberBetween(1, $networkCount),
                'status' => 'pending',
                'exit_url' => '#',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($clicks, 500) as $clickChunk) {
            ExitClick::insert($clickChunk);
        }
    }
}
