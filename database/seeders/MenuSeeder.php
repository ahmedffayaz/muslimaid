<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('admin_menus')->truncate();
        DB::table('admin_menu_items')->truncate();
        Schema::enableForeignKeyConstraints();

        $menusArray = csvToArray('resources\\views\\frontend\\seeders\\admin_menus.csv');
        $menuItemsArray = csvToArray('resources\\views\\frontend\\seeders\\admin_menu_items.csv');

        $menus = [];
        $menuItems = [];

        $now = Carbon::now();

        foreach ($menusArray as $key => $menu) {
            $menus[] = [
                'id' => isset($menu['id']) && !empty($menu['id']) ? $menu['id'] : $key,
                'name' => $menu['name'],
                'title' => $menu['title'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach ($menuItemsArray as $key => $menuItem) {
            $menuItems[] = [
                'id' => isset($menuItem['id']) && !empty($menuItem['id']) ? $menuItem['id'] : $key,
                'label' => $menuItem['label'],
                'link' => $menuItem['link'],
                'parent' => isset($menuItem['parent']) && !empty($menuItem['parent']) ? $menuItem['parent'] : 0,
                'sort' => isset($menuItem['sort']) && !empty($menuItem['sort']) ? $menuItem['sort'] : 0,
                'class' => isset($menuItem['class']) && !empty($menuItem['class']) ? $menuItem['class'] : null,
                'menu' => isset($menuItem['menu']) && !empty($menuItem['menu']) ? $menuItem['menu'] : 1,
                'depth' => isset($menuItem['depth']) && !empty($menuItem['depth']) ? $menuItem['depth'] : 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('admin_menus')->insert($menus);
        DB::table('admin_menu_items')->insert($menuItems);
    }
}
