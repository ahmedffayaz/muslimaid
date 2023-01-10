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
		Network::insert([
			[
				'name' => 'CJ',
				'description' => 'CJ Affiliate Network',
				'click_ref' => 'ref',
				'logo' => 'cj-logo.svg',
			],
			[
				'name' => 'Webgains',
				'description' => 'Webgains Affiliate Network',
				'click_ref' => 'clickref',
				'logo' => 'webgains.png',
			],
			[
				'name' => 'Awin',
				'description' => 'Awin Affiliate Network',
				'click_ref' => 'clickref',
				'logo' => 'awin-logo.svg',
			],
		]);

		//slice
		// $network->token = '1jkkfyp5r28p43ghpsx4p1h588';
		// $network->requestor_cid = '5499477';
		// $network->website_id = '100179843';
	}
}
