<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = array(
            array('name' => 'Africa'),
            array('name' => 'Asia'),
            array('name' => 'The Caribbean'),
            array('name' => 'Central America'),
            array('name' => 'North America'),
            array('name' => 'Europe'),
            array('name' => 'Oceania'),
            array('name' => 'South America'),
        );

        Region::insert($regions);
    }
}
