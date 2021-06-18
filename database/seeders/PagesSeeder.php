<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
            array('id' => '1','title' => 'About','slug' => 'about','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-09 12:30:34','updated_at' => '2021-06-09 12:30:34'),
            array('id' => '3','title' => 'Privacy Policy','slug' => 'privacy-policy','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 08:25:01','updated_at' => '2021-06-10 08:25:01'),
            array('id' => '4','title' => 'Terms and Conditions','slug' => 'terms-and-conditions','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 08:32:37','updated_at' => '2021-06-10 08:32:37'),
            array('id' => '5','title' => 'Cookie Policy','slug' => 'cookie-policy','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 08:34:45','updated_at' => '2021-06-10 08:34:45'),
            array('id' => '6','title' => 'Advertising & Partnerships','slug' => 'advertising-partnerships','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 10:02:03','updated_at' => '2021-06-10 10:02:03'),
            array('id' => '7','title' => 'Careers','slug' => 'careers','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 10:19:43','updated_at' => '2021-06-10 10:19:43'),
            array('id' => '8','title' => 'Getting Started','slug' => 'getting-started','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 10:20:01','updated_at' => '2021-06-10 10:20:01'),
            array('id' => '9','title' => 'Customer Service','slug' => 'customer-service','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 10:20:13','updated_at' => '2021-06-10 10:20:13'),
            array('id' => '10','title' => 'Donate to Charity','slug' => 'donate-to-charity','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 10:20:22','updated_at' => '2021-06-10 10:20:22'),
            array('id' => '11','title' => 'FAQs','slug' => 'faqs','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 10:20:32','updated_at' => '2021-06-10 10:45:11'),
            array('id' => '12','title' => 'Contact','slug' => 'contact','excerpt' => NULL,'default' => '1','status' => '1','created_at' => '2021-06-10 10:20:32','updated_at' => '2021-06-10 10:45:11'),
            array('id' => '14','title' => 'Offers','slug' => 'offers','excerpt' => NULL,'default' => '0','status' => '1','created_at' => '2021-06-15 12:58:53','updated_at' => '2021-06-15 12:58:53'),
            array('id' => '15','title' => 'Vouchers','slug' => 'vouchers','excerpt' => NULL,'default' => '0','status' => '1','created_at' => '2021-06-15 13:06:36','updated_at' => '2021-06-15 13:06:36')
          );

          foreach ($pages as $page) {
            $page = new Page;
            $page->title = $page['title'];
            $page->slug = $page['slug'];
            $page->excerpt = $page['excerpt'];
            $page->status = $page['status'];
            $page->default = $page['default'];
            $page->save();
          }
          
    }
}
