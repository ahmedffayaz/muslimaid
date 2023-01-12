<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = array(
            array('id' => '1','title' => 'About','slug' => 'about','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-09 12:30:34','updated_at' => '2021-06-09 12:30:34','banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '3','title' => 'Privacy Policy','slug' => 'privacy-policy','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 08:25:01','updated_at' => '2021-06-10 08:25:01','banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '4','title' => 'Terms and Conditions','slug' => 'terms-and-conditions','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 08:32:37','updated_at' => '2021-06-10 08:32:37','banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '5','title' => 'Cookie Policy','slug' => 'cookie-policy','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 08:34:45','updated_at' => '2021-06-10 08:34:45','banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '6','title' => 'Advertising & Partnerships','slug' => 'advertising-partnerships','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 10:02:03','updated_at' => '2021-06-10 10:02:03','banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '7','title' => 'Careers','slug' => 'careers','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 10:19:43','updated_at' => '2021-06-10 10:19:43','banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '8','title' => 'Getting Started','slug' => 'getting-started','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 10:20:01','updated_at' => '2021-06-10 10:20:01','banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '9','title' => 'Customer Service','slug' => 'customer-service','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 10:20:13','updated_at' => '2021-06-10 10:20:13','banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '10','title' => 'Donate to Charity','slug' => 'donate-to-charity','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 10:20:22','updated_at' => '2021-06-10 10:20:22','banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '11','title' => 'FAQs','slug' => 'faqs','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 10:20:32','updated_at' => '2021-06-10 10:45:11','banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '12','title' => 'Contact','slug' => 'contact','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 10:20:32','updated_at' => '2021-06-10 10:45:11','banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '14','title' => 'Offers','slug' => 'offers','excerpt' => NULL,'default' => '0','status' => '1','created_at' => '2021-06-15 12:58:53','updated_at' => '2021-06-15 12:58:53','banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '15','title' => 'Vouchers','slug' => 'vouchers','excerpt' => NULL,'default' => '0','status' => '1','created_at' => '2021-06-15 13:06:36','updated_at' => '2021-06-15 13:06:36','banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '16','title' => 'Our Apps','slug' => 'our-apps','excerpt' => NULL,'default' => '0','status' => '1','created_at' => '2022-12-28 13:06:36','updated_at' => '2022-12-28 13:06:36','banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '17','title' => 'Extensions','slug' => 'extensions','excerpt' => NULL,'default' => '0','status' => '1','created_at' => '2022-12-28 13:06:36','updated_at' => '2022-12-28 13:06:36','banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
            array('id' => '18','title' => 'Browser extension policy','slug' => 'browser-extension-policy','excerpt' => NULL,'default' => '0','status' => '1','created_at' => '2022-12-28 13:06:36','updated_at' => '2022-12-28 13:06:36','banner_image' => Null,'description' => 'Cashback is here to support you supporting Black-owned businesses.','meta_description' => Null,'meta_keyword' => Null),
          );

        Page::insert($pages);

    }
}
