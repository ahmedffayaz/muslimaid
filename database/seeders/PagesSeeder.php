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

        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\pages.csv');

        $pages = [];

        $now = Carbon::now();

        foreach ($csvToArray as $page) {
            $pages[] = [
                'id' => $page['id'],
                'title' => $page['title'],
                'slug' => $page['slug'],
                'excerpt' => empty($page['excerpt']) ? null : $page['excerpt'],
                'status' => $page['status'],
                'meta_description' => empty($page['meta_description']) ? null : $page['meta_description'],
                'meta_keyword' => empty($page['meta_keyword']) ? null : $page['meta_keyword'],
                'banner_image' => empty($page['banner_image']) ? null : $page['banner_image'],
                'description' => empty($page['description']) ? null : $page['description'],
                'default' => $page['default'],
                'created_at' => $now,
                'updated_at' =>  $now,
            ];
        }

        Page::insert($pages);
    }
}
