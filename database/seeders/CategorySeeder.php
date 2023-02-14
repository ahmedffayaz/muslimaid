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

        foreach ($csvToArray as $category) {
            $category['id'] = (!isset($category['id']) ? reset($category) : $category['id']);

            if (!arrayValueExists($category, 'id')) continue;
            if (!arrayValueExists($category, 'name')) continue;

            $categories[] = [
                'id' => $category['id'],
                'parent_id' => arrayValueExists($category, 'parent_id') ? $category['parent_id'] : 0,
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'sort' => arrayValueExists($category, 'sort') ? $category['sort'] : null,
                'logo_type' => arrayValueExists($category, 'logo_type') ? $category['logo_type'] : null,
                'logo_upload' => arrayValueExists($category, 'logo_upload') ? $category['logo_upload'] : null,
                'logo_link' => arrayValueExists($category, 'logo_link') ? $category['logo_link'] : null,
                'banner_type' => arrayValueExists($category, 'banner_type') ? $category['banner_type'] : null,
                'banner_upload' => arrayValueExists($category, 'banner_upload') ? $category['banner_upload'] : null,
                'banner_link' => arrayValueExists($category, 'banner_link') ? $category['banner_link'] : null,
                'feature_homepage' => isset($category['feature_homepage']) && $category['feature_homepage'] == '1' ? 1 : 0,
                'feature_sidebar' => isset($category['feature_sidebar']) && $category['feature_sidebar'] == '1' ? 1 : 0,
                'status' => isset($category['status']) && $category['status'] == '1' ? 1 : 0,
                'is_map_enable' => isset($category['is_map_enable']) && $category['is_map_enable'] == '1' ? 1 : 0,
                'url' => arrayValueExists($category, 'url') ? $category['url'] : null,
                'meta_keyword' => arrayValueExists($category, 'meta_keyword') ? $category['meta_keyword'] : null,
                'meta_description' => arrayValueExists($category, 'meta_description') ? $category['meta_description'] : null,
                'created_at' => isset($category['created_at']) ? $category['created_at'] : $now,
                'updated_at' => isset($category['updated_at']) ? $category['updated_at'] : $now,
            ];
        }
        
        foreach (array_chunk($categories, 500) as $categoriesChunk) {
            Category::insert($categoriesChunk);
        }
    }
}
