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
            array('id' => '1','name' => 'Main Menu','created_at' => '2021-06-10 13:52:11','updated_at' => '2021-06-10 13:52:11')
          );


        foreach ($menus as $menu) {
            DB::table('admin_menus')->insert([
                'name'=>$menu['name'],
             ]);
        }

        $admin_menu_items = array(
            array('id' => '1','label' => 'Home','link' => '/','parent' => '0','sort' => '0','class' => NULL,'menu' => '1','depth' => '0','created_at' => '2021-06-10 13:52:22','updated_at' => '2021-06-14 12:13:37'),
            array('id' => '2','label' => 'Offers','link' => '/pages/offers','parent' => '0','sort' => '1','class' => NULL,'menu' => '1','depth' => '0','created_at' => '2021-06-10 13:52:30','updated_at' => '2021-06-15 13:11:23'),
            array('id' => '3','label' => 'Vouchers','link' => '/pages/vouchers','parent' => '0','sort' => '2','class' => NULL,'menu' => '1','depth' => '0','created_at' => '2021-06-11 09:35:16','updated_at' => '2021-06-15 13:11:33'),
            array('id' => '4','label' => 'About','link' => '/pages/about','parent' => '0','sort' => '3','class' => NULL,'menu' => '1','depth' => '0','created_at' => '2021-06-11 09:35:38','updated_at' => '2021-06-11 09:35:38'),
            array('id' => '5','label' => 'Blog','link' => '/blog','parent' => '0','sort' => '4','class' => NULL,'menu' => '1','depth' => '0','created_at' => '2021-06-11 09:35:56','updated_at' => '2021-06-11 09:35:56'),
            array('id' => '6','label' => 'Contact','link' => 'pages/contact','parent' => '0','sort' => '5','class' => NULL,'menu' => '1','depth' => '0','created_at' => '2021-06-11 09:36:15','updated_at' => '2021-06-11 09:36:15')
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
