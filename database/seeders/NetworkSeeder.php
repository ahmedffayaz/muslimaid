<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Network;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class NetworkSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Schema::disableForeignKeyConstraints();
		DB::table('networks')->truncate();
		Schema::enableForeignKeyConstraints();
		$csvToArray = csvToArray('resources\\views\\frontend\\seeders\\networks.csv');
		if (isset($csvToArray[0])) {
			$networks = [];
			$now = Carbon::now();
			foreach ($csvToArray as $network) {
				!isset($network['id']) ?  ($network['id'] = reset($network)) : '';
				if (
					!arrayValueExists($network, 'id')
					|| !arrayValueExists($network, 'name')
					|| !arrayValueExists($network, 'description')
					|| !arrayValueExists($network, 'click_ref')
					|| !arrayValueExists($network, 'deeplink_identifier')
					|| !arrayValueExists($network, 'logo')
				) {
					continue;
				}
				$networks[] = [
					'id' => $network['id'],
					'name' => $network['name'],
					'description' => $network['description'],
					'click_ref' => $network['click_ref'],
					'deeplink_identifier' => $network['deeplink_identifier'],
					'logo' => $network['logo'],
					'created_at' => arrayValueExists($network, 'created_at') ? Carbon::parse($network['created_at'])->format('Y-m-d H:i:s') : $now,
					'updated_at' => arrayValueExists($network, 'updated_at') ? Carbon::parse($network['updated_at'])->format('Y-m-d H:i:s') : $now,
					'deleted_at' => null,
				];
			}
			Network::insert($networks);
		} else {
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
}
