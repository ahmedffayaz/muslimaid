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
            CategorySeeder::class,
            BlogSeeder::class,
            StoreSeeder::class,
            StoreAddressSeeder::class,
            StoreDataSeeder::class,
            ExitClickSeeder::class,
            UserCashbackSeeder::class,
            RolesPermissionsSeeder::class,
            UserSeeder::class,
            NetworkSeeder::class,
            CashbackStatusSeeder::class,
            SettingSeeder::class,
            PagesSeeder::class,
            MenuSeeder::class,
            SliderSeeder::class,
            EmailTemplatesSeeder::class,
            TestimonialSeeder::class,
            TicketCategorySeeder::class,
            RegionSeeder::class,
            CharitySeeder::class,
            CharityTypeSeeder::class,
        ]);
    }
}
