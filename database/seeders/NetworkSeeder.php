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
        if(isset($csvToArray[0])){
			$networks = [];
            $now = Carbon::now();
            foreach ($csvToArray as $network) {
                !isset($network['id']) ?  ($network['id'] = reset($network)) : '' ;
                $networks = [
                    'id' => $network['id'],
                    'name' => $network['name'],
                    'description' => $network['description'],
                    'click_ref' => $network['click_ref'],
                    'deeplink_identifier' => $network['deeplink_identifier'],
                    'logo' => $network['logo'],
                    'created_at' => $network['created_at'],
                    'updated_at' => $network['updated_at'],
                    'deleted_at' => null,
                  
                ];
                Network::insert($networks);
            }
		}else{
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
