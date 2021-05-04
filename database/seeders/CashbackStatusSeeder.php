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

        $statuses = ['pending', 'failed','confirmed', 'paid'];
        foreach($statuses as $statusName){
            $status = new CashbackStatus();
            $status->status = $statusName;
            $status->save();
        }
    }
}
