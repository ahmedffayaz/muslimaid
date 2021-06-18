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
        $settings = array('Cashback Percentage'=>50, 
                            'Store Title'=>'Cashback Reborn',
                            'Phone Number' => '0123456789',
                            'Email'=>'support@trs.com',
                            'Meta Description Coupons'=>'Get the best deals with online shopping coupons and discount voucher codes. Take advantage of our discounts to save while you shop!',
                            'Meta Title Coupons'=>'Online Shopping Coupons | Discount Voucher Codes',
                            'Meta Description Stores'=>'Come shop with us and earn cash back on purchases. Visit our stores today for a great selection of products.',
                            'Meta Title Stores'=>'Earn Cashback in Store | Earn Cashback on Purchases',
                            'Paypal Email'=> 'paypal@trs.com',
                            'Info Email'=>'info@trs.com',
                            'Company Rights'=>'© 2021 Cashback Reborn.',
                            'Sign Up Email'=>'info@trs.com',
                            'Currency'=>'GBP',
                            'Meta Description'=>'Enjoy a rewarding shopping experience and get unlimited cashback discounts. Simply visit our website to earn cashback with online shopping.',
                            'Meta Keywords'=>'Cashback, voucher codes, comparison',
                            'Footer Text'=>'© 2021 Cashback Reborn.',
                            'Meta Title'=>'Cashback Websites | Earn Cashback for Online Shopping',
                            'Contact Us Email'=>'contact@trs.com',
                            'Website Title'=>'Cashback Reborn',
                            'Mail Driver'=>'smtp',
                            'Mail Host'=>'smtp.mailtrap.io',
                            'Mail Port'=>'2525',
                            'Mail Username'=>'7d552105f38912',
                            'Mail Password'=>'5cacef874f5744',
                            'Mail Email'=>'support@cashback-reborn.com',
                            'Mail Name'=>'Cashback Reborn Support',
                            'Currency' =>1,
                            'Dashboare Logo'=>'dashboard_logo_1623648325.png',
                            'Website Logo'=>'website_logo_1623648325.png',
                            'Favicon'=>'favicon_1623648325.png',
                            'Theme Color'=>NULL,
                            'Theme Skin'=>'blue',
                            'Dashboard Menu Type'=>'top'


                            );

        foreach ($settings as $key => $value) {

            $set = SiteSetting::create([
                'title'=>$key,
                'type'=>str_replace([' ','-','.'],'_', strtolower($key)),
                'value'=>$value,
                'default'=>1
    
            ]);
        }

        $networks = Network::all();

        foreach($networks as $network){
            $imp = ImporterSetting::create([
                'network_id'   => $network->id,
                'import_stores'     =>1,
                'import_vouchers'   => 1,
                'import_cashbacks'   => 1,
                'last_import_at'   => \Carbon\Carbon::now()->toDateTimeString()
              
            ]);
        }

        

        $language = Language::create([
            'name' => 'English',
            'code' => 'en'
        ]);

        $currency = Currency::create([
            'name'=>'Pounds',
            'short_name'=>'GBP',
            'symbol'=>'£'
        ]);
     
       
    }
}
