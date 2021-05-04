<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Network;

class NetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $network = new Network();
		$network->name = 'CJ';
		$network->description = 'CJ Affliate Network';
		$network->click_ref = 'ref';
		$network->save();
    }
}
