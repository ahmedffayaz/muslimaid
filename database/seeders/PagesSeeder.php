<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
		DB::table('pages')->truncate();
		Schema::enableForeignKeyConstraints();
        $pages = array(
            array('id' => '1','title' => 'About','slug' => 'about','excerpt' => NULL,'default' => '1','status' => '1','created_at' => Carbon::now(),'updated_at' =>  Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '3','title' => 'Privacy Policy','slug' => 'privacy-policy','excerpt' => NULL,'default' => '1','status' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '4','title' => 'Terms and Conditions','slug' => 'terms-and-conditions','excerpt' => NULL,'default' => '1','status' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '5','title' => 'Cookie Policy','slug' => 'cookie-policy','excerpt' => NULL,'default' => '1','status' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '6','title' => 'Advertising & Partnerships','slug' => 'advertising-partnerships','excerpt' => NULL,'default' => '1','status' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '7','title' => 'Careers','slug' => 'careers','excerpt' => NULL,'default' => '1','status' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '8','title' => 'Getting Started','slug' => 'getting-started','excerpt' => NULL,'default' => '1','status' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '9','title' => 'Customer Service','slug' => 'customer-service','excerpt' => NULL,'default' => '1','status' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '10','title' => 'Donate to Charity','slug' => 'donate-to-charity','excerpt' => NULL,'default' => '1','status' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '11','title' => 'FAQs','slug' => 'faqs','excerpt' => NULL,'default' => '1','status' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '12','title' => 'Contact','slug' => 'contact','excerpt' => NULL,'default' => '1','status' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '14','title' => 'Offers','slug' => 'offers','excerpt' => NULL,'default' => '0','status' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '15','title' => 'Vouchers','slug' => 'vouchers','excerpt' => NULL,'default' => '0','status' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '16','title' => 'Our Apps','slug' => 'our-apps','excerpt' => NULL,'default' => '0','status' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '17','title' => 'Extensions','slug' => 'extensions','excerpt' => NULL,'default' => '0','status' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '18','title' => 'Browser extension policy','slug' => 'browser-extension-policy','excerpt' => NULL,'default' => '0','status' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '19','title' => 'Trending','slug' => 'trending','excerpt' => NULL,'default' => '0','status' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),     
          );

        Page::insert($pages);

    }
}
