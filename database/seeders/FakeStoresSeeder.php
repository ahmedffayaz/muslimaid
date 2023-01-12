<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FakeStoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            StoreSeeder::class,
            ClicksSeeder::class,
            CashbackSeeder::class,
            ReviewSeeder::class,
            CashoutSeeder::class,
        ]);
    }
}
