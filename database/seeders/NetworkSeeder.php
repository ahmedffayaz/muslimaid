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
		$network->description = 'CJ Affiliate Network';
		$network->click_ref = 'ref';
		$network->logo = 'cj-logo.svg';
		$network->save();

        $network = new Network();
		$network->name = 'Webgains';
		$network->description = 'Webgains Affiliate Network';
		$network->click_ref = 'clickref';
		$network->logo = 'webgains.png';
		$network->save();

        $network = new Network();
		$network->name = 'Awin';
		$network->description = 'Awin Affiliate Network';
		$network->click_ref = 'clickref';
		$network->logo = 'logo-awin-black.svg';
		$network->save();

        //slice
        // $network->token = '1jkkfyp5r28p43ghpsx4p1h588';
		// $network->requestor_cid = '5499477';
		// $network->website_id = '100179843';
    }
}
