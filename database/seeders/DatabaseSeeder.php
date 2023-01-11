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
        $this->call($this->essentialSeeders());
        $this->call($this->fakeNetworkSeeders());
    }

    /**
     * Seed essential data required for application to run.
     *
     * @return void
     */
    private function essentialSeeders()
    {
        return [
            RolesPermissionsSeeder::class,
            UserSeeder::class,
            NetworkSeeder::class,
            CategorySeeder::class,
            CashbackStatusSeeder::class,
            SettingSeeder::class,
            PagesSeeder::class,
            MenuSeeder::class,
            SliderSeeder::class,
            EmailTemplatesSeeder::class,
            TestimonialSeeder::class,
            TicketCategorySeeder::class,
            RegionSeeder::class
        ];
    }

    /**
     * Seed fake data that supposed to be fetched by the networks.
     *
     * @return void
     */
    private function fakeNetworkSeeders()
    {
        return [
            StoreSeeder::class,
            ClicksSeeder::class,
            CashbackSeeder::class,
            ReviewSeeder::class,
            CashoutSeeder::class,
        ];
    }
}
