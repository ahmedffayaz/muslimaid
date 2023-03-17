<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CashbackStatus;
use Faker\Factory as Faker;

class CashbackStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $statuses = [
            ['status' => 'pending', 'details' => 'Assigned when the cashback is penidng'],
            ['status' => 'failed', 'details' => 'Assigned when the cashback is failed'],
            ['status' => 'confirmed', 'details' => 'Assigned when the cashback is confirmed'],
            ['status' => 'paid', 'details' => 'Assigned when the cashback is paid'],
            ['status' => 'processing', 'details' => 'Assigned when the cashback is in process of cashout'],
            ['status' => 'processing donation', 'details' => 'Assigned when the cashback is in process of processing donation'],
            ['status' => 'donated', 'details' => 'Assigned when the cashback is in process of donated to organization']
        ];

        foreach ($statuses as $status) {
            CashbackStatus::create($status);
        }
    }
}
