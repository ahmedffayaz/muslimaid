<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\StoreReview;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    private $count = 3000;

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

        $reviews = [];

        for ($i = 1; $i <= $this->count; $i++) {
            $reviews[] = [
                'store_id' => $faker->numberBetween(1, $storesCount),
                'user_id' => $faker->numberBetween(1, $usersCount),
                'review' => $faker->paragraph(3, true),
                'rating' =>  $faker->numberBetween(4, 5)
            ];
        }

        foreach (array_chunk($reviews, 500) as $reviewsChunk) {
            StoreReview::insert($reviewsChunk);
        }
    }
}
