<?php

namespace Database\Seeders;

use App\Models\StoreImage;
use App\Models\StoreReview;
use App\Models\StoreSeoData;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;

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
        $now = Carbon::parse(now())->format('Y-m-d H:i:s');
        Schema::disableForeignKeyConstraints();
        DB::table('store_images')->truncate();
        Schema::enableForeignKeyConstraints();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\store_images.csv');
        if (isset($csvToArray[0])) {
            $storeImages = [];
            foreach ($csvToArray as $storeImage) {
                $storeImage['id'] = (!isset($storeImage['id']) ? reset($storeImage) : $storeImage['id']);
                if (
                    !arrayValueExists($storeImage, 'id')
                    || !arrayValueExists($storeImage, 'store_id')
                    || !arrayValueExists($storeImage, 'title')
                    || !arrayValueExists($storeImage, 'image')
                    || !arrayValueExists($storeImage, 'image_type')
                ) {
                    continue;
                }
                $storeImages[] = [
                    'id' => $storeImage['id'],
                    'store_id' => $storeImage['store_id'],
                    'title' => $storeImage['title'],
                    'image' => $storeImage['image'],
                    'image_type' => $storeImage['image_type'],
                    'is_uploaded' => $storeImage['is_uploaded'],
                    'is_fake' => 0,
                    'created_at' => arrayValueExists($storeImage, 'created_at') ? Carbon::parse($storeImage['created_at'])->format('Y-m-d H:i:s') : $now,
                    'updated_at' => arrayValueExists($storeImage, 'updated_at') ? Carbon::parse($storeImage['updated_at'])->format('Y-m-d H:i:s') : $now,
                ];
            }
        }
        foreach (array_chunk($storeImages, 500) as $storeImagesChunk) {
            StoreImage::insert($storeImagesChunk);
        }

        // Store Reviews
        Schema::disableForeignKeyConstraints();
        DB::table('store_reviews')->truncate();
        Schema::enableForeignKeyConstraints();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\store_reviews.csv');
        if (isset($csvToArray[0])) {
            $storeReviews = [];
            foreach ($csvToArray as $storeReview) {
                $storeReview['id'] = (!isset($storeReview['id']) ? reset($storeReview) : $storeReview['id']);
                if (
                    !arrayValueExists($storeReview, 'id')
                    || !arrayValueExists($storeReview, 'store_id')
                    || !arrayValueExists($storeReview, 'user_id')
                ) {
                    continue;
                }
                $storeReviews[] = [
                    'id' => $storeReview['id'],
                    'store_id' => $storeReview['store_id'],
                    'user_id' => $storeReview['user_id'],
                    'review' => arrayValueExists($storeReview, 'review') ? $storeReview['review']: null,
                    'rating' => arrayValueExists($storeReview, 'rating') ? $storeReview['rating']: 5,
                    'status' => arrayValueExists($storeReview, 'status') ? $storeReview['status']: 'active',
                    'created_at' => arrayValueExists($storeReview, 'created_at') ? Carbon::parse($storeReview['created_at'])->format('Y-m-d H:i:s') : $now,
                    'updated_at' => arrayValueExists($storeReview, 'updated_at') ? Carbon::parse($storeReview['updated_at'])->format('Y-m-d H:i:s') : $now,
                ];
            }
        }
        foreach (array_chunk($storeReviews, 500) as $storeReviewsChunk) {
            StoreReview::insert($storeReviewsChunk);
        }

        // Store SEO Data
        Schema::disableForeignKeyConstraints();
        DB::table('store_seo_data')->truncate();
        Schema::enableForeignKeyConstraints();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\store_seo_data.csv');
        if (isset($csvToArray[0])) {
            $storeSeoRows = [];
            foreach ($csvToArray as $storeSeoData) {
                $storeSeoData['id'] = (!isset($storeSeoData['id']) ? reset($storeSeoData) : $storeSeoData['id']);
                if (
                    !arrayValueExists($storeSeoData, 'id')
                    || !arrayValueExists($storeSeoData, 'store_id')
                    || !arrayValueExists($storeSeoData, 'url')
                    || !arrayValueExists($storeSeoData, 'type')
                    || !arrayValueExists($storeSeoData, 'key')
                    || !arrayValueExists($storeSeoData, 'value')
                ) {
                    continue;
                }
                $storeSeoRows[] = [
                    'id' => $storeSeoData['id'],
                    'store_id' => $storeSeoData['store_id'],
                    'url' => $storeSeoData['url'],
                    'type' => $storeSeoData['type'],
                    'key' => $storeSeoData['key'],
                    'value' => $storeSeoData['value'],
                    'created_at' => arrayValueExists($storeSeoData, 'created_at') ? Carbon::parse($storeSeoData['created_at'])->format('Y-m-d H:i:s') : $now,
                    'updated_at' => arrayValueExists($storeSeoData, 'updated_at') ? Carbon::parse($storeSeoData['updated_at'])->format('Y-m-d H:i:s') : $now,
                ];
            }
        }
        foreach (array_chunk($storeSeoRows, 500) as $storeSeoRowsChunk) {
            StoreSeoData::insert($storeSeoRowsChunk);
        }


        // Store Category 
        Schema::disableForeignKeyConstraints();
        DB::table('category_store')->truncate();
        Schema::enableForeignKeyConstraints();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\category_store.csv');
        if (isset($csvToArray[0])) {
            $categoryStores = [];
            foreach ($csvToArray as $categoryStores) {
                $categoryStores['id'] = (!isset($categoryStores['id']) ? reset($categoryStores) : $categoryStores['id']);
                if ($categoryStores['category_id'] != '') {
                    DB::insert("INSERT INTO `category_store`(`id`, `store_id`, `category_id`, `network_category_id`, `created_at`, `updated_at`) VALUES (" . $categoryStores['id'] . "," . $categoryStores['store_id'] . "," . $categoryStores['category_id'] . "," . $categoryStores['network_category_id'] . ",'" . $categoryStores['created_at'] . "','" . $categoryStores['updated_at'] . "')");
                }
            }
        }
    }
}
