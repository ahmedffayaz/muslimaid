<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Cashout;
use App\Models\User;

class CashoutSeeder extends Seeder
{
    private $count = 10;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $usersCount = User::count();

        $cashouts = [];

        for ($i = 1; $i <= $this->count; $i++) {
            $cashouts[] = [
                'user_id' => $faker->numberBetween(1, $usersCount),
                'amount' => $faker->numberBetween(10, 500),
                'cashout_type' => null,
                'paypal_email' => $faker->email,
                'address' => null,
                'city' => null,
                'postcode' => null,
                'country' => null,
                'account_name' => null,
                'bank_title' => null,
                'account_number' => null,
                'bank_sort_code' => null,
                'new_cashout' => '0',
                'bic' => null,
                'payment_method' => 'paypal',
                'status' => 'paid',
            ];
        }

        foreach (array_chunk($cashouts, 500) as $cashoutsChunk) {
            Cashout::insert($cashoutsChunk);
        }
    }
}
