<?php

namespace Database\Seeders;

use App\Models\StoreImage;
use App\Models\StoreReview;
use App\Models\StoreSeoData;
use App\Models\StoreCashback;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StoreDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Store Images
        Schema::disableForeignKeyConstraints();
        DB::table('store_images')->truncate();
        Schema::enableForeignKeyConstraints();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\store_images.csv');
        if(isset($csvToArray[0])){
            $store_images = [];
            $now = Carbon::now();
            foreach ($csvToArray as $store_image) {
                !isset($store_image['id']) ?  ($store_image['id'] = reset($store_image)) : '' ;
                $store_images = [
                    'id' => $store_image['id'],
                    'store_id' => $store_image['store_id'],
                    'title' => $store_image['title'],
                    'image' => $store_image['image'],
                    'image_type' => $store_image['image_type'],
                    'is_uploaded' => $store_image['is_uploaded'],
                    'is_fake' => 0, 
                    'created_at' => $store_image['created_at'],
                    'updated_at' => $store_image['updated_at'],
                  
                ];
                StoreImage::insert($store_images);
            }
        }

        // Store Reviews
        Schema::disableForeignKeyConstraints();
        DB::table('store_reviews')->truncate();
        Schema::enableForeignKeyConstraints();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\store_reviews.csv');
        if(isset($csvToArray[0])){
            $store_reviews = [];
            $now = Carbon::now();
            foreach ($csvToArray as $store_review) {
                !isset($store_review['id']) ?  ($store_review['id'] = reset($store_review)) : '' ;
                $store_reviews = [
                    'id' => $store_review['id'],
                    'store_id' => $store_review['store_id'],
                    'user_id' => $store_review['user_id'],
                    'review' => $store_review['review'],
                    'rating' => $store_review['rating'],
                    'status' => $store_review['status'],
                    'created_at' => $store_review['created_at'],
                    'updated_at' => $store_review['updated_at'],
                  
                ];
                StoreReview::insert($store_reviews);
            }
        }

        // Store SEO Data
        Schema::disableForeignKeyConstraints();
        DB::table('store_seo_data')->truncate();
        Schema::enableForeignKeyConstraints();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\store_seo_data.csv');
        if(isset($csvToArray[0])){
            $store_seo_datas = [];
            $now = Carbon::now();
            foreach ($csvToArray as $store_seo_data) {
                !isset($store_seo_data['id']) ?  ($store_seo_data['id'] = reset($store_seo_data)) : '' ;
                $store_seo_datas = [
                    'id' => $store_seo_data['id'],
                    'store_id' => $store_seo_data['store_id'],
                    'url' => $store_seo_data['url'],
                    'type' => $store_seo_data['type'],
                    'key' => $store_seo_data['key'],
                    'value' => $store_seo_data['value'],
                    'created_at' => $store_seo_data['created_at'],
                    'updated_at' => $store_seo_data['updated_at'],
                ];
                StoreSeoData::insert($store_seo_datas);
            }
        }


        // Store Category 
        Schema::disableForeignKeyConstraints();
        DB::table('category_store')->truncate();
        Schema::enableForeignKeyConstraints();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\category_store.csv');
        if(isset($csvToArray[0])){
            $category_stores = [];
            $now = Carbon::now();
            foreach ($csvToArray as $category_stores) {
                !isset($category_stores['id']) ?  ($category_stores['id'] = reset($category_stores)) : '' ;
                if($category_stores['category_id'] != ''){
                    DB::insert("INSERT INTO `category_store`(`id`, `store_id`, `category_id`, `network_category_id`, `created_at`, `updated_at`) VALUES (".$category_stores['id'].",".$category_stores['store_id'].",".$category_stores['category_id'].",".$category_stores['network_category_id'].",'".$category_stores['created_at']."','".$category_stores['updated_at']."')");
                }
            }
        }
    }
}
