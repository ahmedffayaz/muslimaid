<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            UserSeeder::class,
            NetworkSeeder::class,
            StoreSeeder::class,
            CategorySeeder::class,
            CashbackStatusSeeder::class,
           
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
