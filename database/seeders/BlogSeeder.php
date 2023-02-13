<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('blogs')->truncate();
        Schema::enableForeignKeyConstraints();

        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\blogs.csv');
        $charity_types = [];
        $now = Carbon::now();
        foreach($csvToArray as $charity_type){
            !isset($charity_type['id']) ?  ($charity_type['id'] = reset($charity_type)) : '' ;
            $charity_types[] = [
                'id' => $charity_type['id'],
                'title' => $charity_type['title'],
                'slug' => Str::slug($charity_type['title']),
                'featured_image' => $charity_type['featured_image'],
                'excerpt' => $charity_type['excerpt'],
                'url' => $charity_type['url'],
                'meta_keyword' => $charity_type['meta_keyword'],
                'meta_description' => $charity_type['meta_description'],
                'created_at' => isset($charity_type['created_at']) ? $charity_type['created_at'] : $now,
                'updated_at' => isset($charity_type['updated_at']) ? $charity_type['updated_at'] : $now,
            ];
        }
        Blog::insert($charity_types);
    }
}
