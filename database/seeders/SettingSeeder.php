<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;
use App\Models\ImporterSetting;
use App\Models\Language;
use App\Models\Network;
use App\Models\Currency;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = array(
            'Cashback Percentage' => 50,
            'Store Title' => 'Cashback Reborn',
            'Phone Number' => '0123456789',
            'Email' => 'support@trs.com',
            'Meta Description Coupons' => 'Get the best deals with online shopping coupons and discount voucher codes. Take advantage of our discounts to save while you shop!',
            'Meta Title Coupons' => 'Online Shopping Coupons | Discount Voucher Codes',
            'Meta Description Stores' => 'Come shop with us and earn cash back on purchases. Visit our stores today for a great selection of products.',
            'Meta Title Stores' => 'Earn Cashback in Store | Earn Cashback on Purchases',
            'Paypal Email' => 'paypal@trs.com',
            'Info Email' => 'info@trs.com',
            'Company Rights' => '© 2021 Cashback Reborn.',
            'Sign Up Email' => 'info@trs.com',
            'Currency' => 'GBP',
            'Footer Text' => '© 2021 Cashback Reborn.',
            'Meta Title' => 'Cashback Websites | Earn Cashback for Online Shopping',
            'Contact Us Email' => 'contact@trs.com',
            'Website Title' => 'Cashback Reborn',
            'Mail Driver' => 'smtp',
            'Mail Host' => 'smtp.mailtrap.io',
            'Mail Port' => '2525',
            'Mail Username' => '07f4dbf61b8122',
            'Mail Password' => 'd7fc5d57bc6eac',
            'Mail Email' => 'support@cashback-reborn.com',
            'Mail Name' => 'Cashback Reborn Support',
            'Currency' => 1,
            'Dashboard Logo' => 'default.png',
            'Website Logo' => 'default.png',
            'Favicon' => 'default.png',
            'Theme Color' => NULL,
            'Theme Skin' => 'blue',
            'Dashboard Menu Type' => 'top',
            'Payment Method Paypal' => 1,
            'Payment Method Bank' => 1,
            'Min Cashout Amount' => 1,
            'Welcome Bonus' => 2,
            'Facebook client id' => '3200039003655128',
            'Facebook client Secret' => '74f27da5a9219d4e98a072b2cda3a465',
            'Facebook Url' => 'http://localhost:8000/login/facebook/callback',
            'Google Client Id' => '7625208194-mukofel1nlmurbguafeffdbh91amothm.apps.googleusercontent.com',
            'Google Client Secret' => 'tZBziwUoyKgzbioTPKGTfYeL',
            'Google Url' => 'http://localhost:8000/login/google/callback',
            'Mailchimp Api Key' => 'd648b62b49e2fb3c5b58c5c2f83f52a5-us6',
            'Mailchimp List Id' => '23961d753f',
            'CJ Website ID' => '100424737',
            'CJ Requestor ID' => '5835313',
            'CJ Authorization Token' => '7v15dz0jk80tj3wwp5kmbvyx91',
            'Webgains API Key' => 'ef13cee1a4c5ec9ee5864d0e2d535604',
            'Webgains campaignId' => '1462945',
            'Webgains User Name' => 'khuramj',
            'Webgains Password' => 'p9bXV7w2e@nqhQ2',
            'Map Key' => 'AIzaSyAOxeH_CSvIYJQL8UCa9LnkUUi5AmVcI-c',
            'Awin Publisher ID' => '740509',
            'Awin Authorization Token' => '782f574c-8469-45ae-a37b-580d0c8c49c6',
            'Payment Method Charity' => 1,
        );

        foreach ($settings as $key => $value) {
            $set = SiteSetting::create([
                'title' => $key,
                'type' => str_replace([' ', '-', '.'], '_', strtolower($key)),
                'value' => $value,
                'default' => 1

            ]);
        }

        $networks = Network::all();

        foreach ($networks as $network) {
            $imp = ImporterSetting::create([
                'network_id' => $network->id,
                'import_stores' => 1,
                'import_vouchers' => 1,
                'import_cashbacks' => 1,
                'last_import_at' => \Carbon\Carbon::now()->toDateTimeString()
            ]);
        }

        $language = Language::create([
            'name' => 'English',
            'code' => 'en'
        ]);

        $currency = Currency::create([
            'name' => 'Pounds',
            'short_name' => 'GBP',
            'symbol' => '£'
        ]);
    }
}
