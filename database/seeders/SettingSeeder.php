<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Network;
use App\Models\Currency;
use App\Models\Language;
use App\Models\SiteSetting;
use App\Models\ImporterSetting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\site_settings.csv');

        $settings = [];

        $now = Carbon::now();

        foreach ($csvToArray as $setting) {
            $settings[] = [
                'title' => $setting['title'],
                'type' => $setting['type'],
                'value' => ($setting['type'] == "facebook_url" || $setting['type'] == "google_url") ?  (url('/') .  $setting['value']) : $setting['value'],
                'default' => isset($setting['default']) && !empty($setting['default']) ? $setting['default'] : 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        SiteSetting::insert($settings);

        $networks = Network::all();
        $importerSetting = [];

        foreach ($networks as $network) {
            $importerSetting[] = [
                'network_id' => $network->id,
                'import_stores' => 1,
                'import_vouchers' => 1,
                'import_cashbacks' => 1,
                'last_import_at' => $now
            ];
        }

        ImporterSetting::insert($importerSetting);

        Language::create([
            'name' => 'English',
            'code' => 'en'
        ]);

        Currency::create([
            'name' => 'Pounds',
            'short_name' => 'GBP',
            'symbol' => '£'
        ]);
    }
}
