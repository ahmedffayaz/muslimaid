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
            RolesPermissionsSeeder::class,
            UserSeeder::class,
            NetworkSeeder::class,
            StoreSeeder::class,
            CategorySeeder::class,
            CashbackStatusSeeder::class,
            ClicksSeeder::class,
            SettingSeeder::class,
            CashbackSeeder::class,
            ReviewSeeder::class,
            CashoutSeeder::class,
            PagesSeeder::class,
            MenuSeeder::class,
            SliderSeeder::class,
            EmailTemplatesSeeder::class
           
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
