<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('categories')->truncate();
        Schema::enableForeignKeyConstraints();

        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\categories.csv');
        $categories = [];
        $now = Carbon::now();
        $slugArray = array();
        foreach ($csvToArray as $category) {
            $category['id'] = !isset($category['id']) ? reset($category) : $category['id'];

            if (!arrayValueExists($category, 'id') || !arrayValueExists($category, 'name') || $category['id'] == 145) continue;
            $slug = Str::slug($category['name']);
            if (in_array($slug, $slugArray)) {
                $slug = $slug . $category['id'];
            }
            array_push($slugArray, $slug);

            $categories[] = [
                'id' => $category['id'],
                'parent_id' => arrayValueExists($category, 'parent_id') ? $category['parent_id'] : 0,
                'name' => $category['name'],
                'slug' =>  $slug,
                'description' => arrayValueExists($category, 'description') ? $category['description'] : null,
                'sort' => arrayValueExists($category, 'sort') ? $category['sort'] : 0,
                'logo_type' => arrayValueExists($category, 'logo_type') ? $category['logo_type'] : null,
                'logo_upload' => arrayValueExists($category, 'logo_upload') ? $category['logo_upload'] : null,
                'logo_link' => arrayValueExists($category, 'logo_link') ? $category['logo_link'] : null,
                'banner_type' => arrayValueExists($category, 'banner_type') ? $category['banner_type'] : null,
                'banner_upload' => arrayValueExists($category, 'banner_upload') ? $category['banner_upload'] : null,
                'banner_link' => arrayValueExists($category, 'banner_link') ? $category['banner_link'] : null,
                'status' => isset($category['status']) && $category['status'] == '1' ? 1 : 0,
                'is_map_enable' => isset($category['is_map_enable']) && $category['is_map_enable'] == '1' ? 1 : 0,
                'url' => arrayValueExists($category, 'url') ? $category['url'] : null,
                'meta_keyword' => arrayValueExists($category, 'meta_keyword') ? $category['meta_keyword'] : null,
                'meta_description' => arrayValueExists($category, 'meta_description') ? $category['meta_description'] : null,
                'created_at' => isset($category['created_at']) ? dbDate($category['created_at']) : $now,
                'updated_at' => isset($category['updated_at']) ? dbDate($category['updated_at']) : $now,
            ];
        }

        foreach (array_chunk($categories, 500) as $categoriesChunk) {
            Category::insert($categoriesChunk);
        }
    }
}
