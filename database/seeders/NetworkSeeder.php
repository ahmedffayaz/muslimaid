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
		$network->token = '7v15dz0jk80tj3wwp5kmbvyx91';
		$network->requestor_cid = '5835313';
		$network->website_id = '100424737';
		$network->save();


        //slice
        // $network->token = '1jkkfyp5r28p43ghpsx4p1h588';
		// $network->requestor_cid = '5499477';
		// $network->website_id = '100179843';
    }
}
