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
                'deeplink_identifier' => 'u',
				'logo' => 'cj-logo.svg',
			],
			[
				'name' => 'Webgains',
				'description' => 'Webgains Affiliate Network',
				'click_ref' => 'clickref',
                'deeplink_identifier' => 'u',
				'logo' => 'webgains.png',
			],
			[
				'name' => 'Awin',
				'description' => 'Awin Affiliate Network',
				'click_ref' => 'clickref',
                'deeplink_identifier' => 'u',
				'logo' => 'awin-logo.svg',
			],
		]);
	}
}
