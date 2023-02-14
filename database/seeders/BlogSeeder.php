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
        $blogs = [];
        $now = Carbon::now();
        foreach($csvToArray as $blog){
            !isset($blog['id']) ?  ($blog['id'] = reset($blog)) : '' ;
            $blogs[] = [
                'id' => $blog['id'],
                'title' => $blog['title'],
                'slug' => Str::slug($blog['title']),
                'featured_image' => $blog['featured_image'],
                'excerpt' => arrayValueExists($blog, 'excerpt') ? $blog['excerpt'] : null,
                'url' => $blog['url'],
                'meta_keyword' => arrayValueExists($blog, 'meta_keyword') ? $blog['meta_keyword'] : null,
                'meta_description' => arrayValueExists($blog, 'meta_description') ? $blog['meta_description']: null,
                'created_at' => arrayValueExists($blog, 'created_at') ? Carbon::parse($blog['created_at'])->format('Y-m-d H:i:s') : $now,
                'updated_at' => arrayValueExists($blog, 'updated_at') ? Carbon::parse($blog['updated_at'])->format('Y-m-d H:i:s') : $now,
            ];
        }
        Blog::insert($blogs);
    }
}
