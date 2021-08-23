<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = array(
            array('id' => '1','name' => 'Main Menu','title' => 'Main Menu','created_at' => '2021-06-10 13:52:11','updated_at' => '2021-06-10 13:52:11'),
            array('id' => '2','name' => 'Footer Menu-column 1','title' => 'About','created_at' => '2021-07-14 05:07:05','updated_at' => '2021-07-14 05:07:05'),
            array('id' => '3','name' => 'Footer Menu-column 2','title' => 'Here to help','created_at' => '2021-07-14 05:10:43','updated_at' => '2021-07-14 05:10:43'),
            array('id' => '4','name' => 'Footer Menu-column 3','title' => 'Hot offers','created_at' => '2021-07-14 05:10:54','updated_at' => '2021-07-14 05:10:54'),
            array('id' => '5','name' => 'Footer Menu-column 4','title' => 'Policies','created_at' => '2021-07-14 05:11:06','updated_at' => '2021-07-14 05:11:06')
          );


        foreach ($menus as $menu) {
            DB::table('admin_menus')->insert([
                'name'=>$menu['name'],
                'title'=>$menu['title'],
             ]);
        }

        $admin_menu_items = array(
            array('id' => '1','label' => 'Home','link' => '/','parent' => '0','sort' => '0','class' => NULL,'menu' => '1','depth' => '0','created_at' => '2021-06-10 13:52:22','updated_at' => '2021-06-14 12:13:37'),
            array('id' => '2','label' => 'Offers','link' => '/pages/offers','parent' => '0','sort' => '1','class' => NULL,'menu' => '1','depth' => '0','created_at' => '2021-06-10 13:52:30','updated_at' => '2021-06-15 13:11:23'),
            array('id' => '3','label' => 'Vouchers','link' => '/pages/vouchers','parent' => '0','sort' => '2','class' => NULL,'menu' => '1','depth' => '0','created_at' => '2021-06-11 09:35:16','updated_at' => '2021-06-15 13:11:33'),
            array('id' => '4','label' => 'About','link' => '/pages/about','parent' => '0','sort' => '3','class' => NULL,'menu' => '1','depth' => '0','created_at' => '2021-06-11 09:35:38','updated_at' => '2021-06-11 09:35:38'),
            array('id' => '5','label' => 'Blog','link' => '/blog','parent' => '0','sort' => '4','class' => NULL,'menu' => '1','depth' => '0','created_at' => '2021-06-11 09:35:56','updated_at' => '2021-06-11 09:35:56'),
            array('id' => '6','label' => 'Contact','link' => '/pages/contact','parent' => '0','sort' => '5','class' => NULL,'menu' => '1','depth' => '0','created_at' => '2021-06-11 09:36:15','updated_at' => '2021-06-11 09:36:15'),
            array('id' => '9','label' => 'About us','link' => '/pages/about','parent' => '0','sort' => '0','class' => NULL,'menu' => '2','depth' => '0','created_at' => '2021-07-14 05:07:54','updated_at' => '2021-07-14 05:08:45'),
            array('id' => '10','label' => 'Advertising & Partnerships','link' => '/pages/advertising-partnerships','parent' => '0','sort' => '1','class' => NULL,'menu' => '2','depth' => '0','created_at' => '2021-07-14 05:08:44','updated_at' => '2021-07-14 05:09:35'),
            array('id' => '11','label' => 'Careers','link' => '/pages/careers','parent' => '0','sort' => '2','class' => NULL,'menu' => '2','depth' => '0','created_at' => '2021-07-14 05:09:34','updated_at' => '2021-07-14 05:10:14'),
            array('id' => '12','label' => 'Getting Started','link' => '/pages/getting-started','parent' => '0','sort' => '0','class' => NULL,'menu' => '3','depth' => '0','created_at' => '2021-07-14 05:12:27','updated_at' => '2021-07-14 05:12:49'),
            array('id' => '13','label' => 'Customer Service','link' => '/pages/customer-service','parent' => '0','sort' => '1','class' => NULL,'menu' => '3','depth' => '0','created_at' => '2021-07-14 05:12:48','updated_at' => '2021-07-14 05:13:11'),
            array('id' => '14','label' => 'Donate to Charity','link' => '/pages/donate-to-charity','parent' => '0','sort' => '2','class' => NULL,'menu' => '3','depth' => '0','created_at' => '2021-07-14 05:13:10','updated_at' => '2021-07-14 05:13:29'),
            array('id' => '15','label' => 'FAQs','link' => '/pages/faqs','parent' => '0','sort' => '3','class' => NULL,'menu' => '3','depth' => '0','created_at' => '2021-07-14 05:13:29','updated_at' => '2021-07-14 05:13:35'),
            array('id' => '16','label' => 'Privacy Policy','link' => '/pages/privacy-policy','parent' => '0','sort' => '0','class' => NULL,'menu' => '5','depth' => '0','created_at' => '2021-07-14 05:15:21','updated_at' => '2021-07-14 05:15:43'),
            array('id' => '17','label' => 'Cookies Policy','link' => '/pages/cookie-policy','parent' => '0','sort' => '1','class' => NULL,'menu' => '5','depth' => '0','created_at' => '2021-07-14 05:15:42','updated_at' => '2021-07-14 05:16:10'),
            array('id' => '18','label' => 'Terms and Conditions','link' => '/pages/terms-and-conditions','parent' => '0','sort' => '2','class' => NULL,'menu' => '5','depth' => '0','created_at' => '2021-07-14 05:16:09','updated_at' => '2021-07-14 05:16:13'),
            array('id' => '19','label' => 'Trending','link' => '/trending','parent' => '0','sort' => '0','class' => NULL,'menu' => '4','depth' => '0','created_at' => '2021-07-14 05:20:48','updated_at' => '2021-07-14 05:22:26'),
            array('id' => '20','label' => 'Top cashback deals','link' => '#','parent' => '0','sort' => '1','class' => NULL,'menu' => '4','depth' => '0','created_at' => '2021-07-14 05:22:25','updated_at' => '2021-07-14 05:22:56'),
            array('id' => '21','label' => 'Top voucher codes','link' => '#','parent' => '0','sort' => '3','class' => NULL,'menu' => '4','depth' => '0','created_at' => '2021-07-14 05:22:55','updated_at' => '2021-07-14 05:22:55')
          );

          foreach ($admin_menu_items as $item) {
            DB::table('admin_menu_items')->insert([
                'label'=>$item['label'],
                'link'=>$item['link'],
                'parent'=>$item['parent'],
                'sort'=>$item['sort'],
                'class'=>$item['class'],
                'menu'=>$item['menu'],
                'depth'=>$item['depth'],
             ]);
        }
          
        


    }
}
