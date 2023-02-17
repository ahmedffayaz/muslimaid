<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Store;
use App\Models\StoreImage;
use App\Models\StoreReview;
use App\Models\StoreSeoData;
use App\Models\StoreCashback;
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
        $store_id_data = Store::pluck('id')->toArray();
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
                    || !in_array($storeImage['store_id'], $store_id_data)
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
                    'created_at' => arrayValueExists($storeImage, 'created_at') ? dbDate($storeImage['created_at']) : $now,
                    'updated_at' => arrayValueExists($storeImage, 'updated_at') ? dbDate($storeImage['updated_at']) : $now,
                ];
            }
            foreach (array_chunk($storeImages, 500) as $storeImagesChunk) {
                StoreImage::insert($storeImagesChunk);
            }
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
                    || !in_array($storeReview['store_id'], $store_id_data)
                ) {
                    continue;
                }
                $storeReviews[] = [
                    'id' => $storeReview['id'],
                    'store_id' => $storeReview['store_id'],
                    'user_id' => $storeReview['user_id'],
                    'review' => arrayValueExists($storeReview, 'review') ? $storeReview['review'] : null,
                    'rating' => arrayValueExists($storeReview, 'rating') ? $storeReview['rating'] : 5,
                    'status' => arrayValueExists($storeReview, 'status') ? $storeReview['status'] : 'active',
                    'created_at' => arrayValueExists($storeReview, 'created_at') ? dbDate($storeReview['created_at']) : $now,
                    'updated_at' => arrayValueExists($storeReview, 'updated_at') ? dbDate($storeReview['updated_at']) : $now,
                ];
            }
            foreach (array_chunk($storeReviews, 500) as $storeReviewsChunk) {
                StoreReview::insert($storeReviewsChunk);
            }
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
                    || !in_array($storeSeoData['store_id'], $store_id_data)
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
                    'created_at' => arrayValueExists($storeSeoData, 'created_at') ? dbDate($storeSeoData['created_at']) : $now,
                    'updated_at' => arrayValueExists($storeSeoData, 'updated_at') ? dbDate($storeSeoData['updated_at']) : $now,
                ];
            }
            foreach (array_chunk($storeSeoRows, 500) as $storeSeoRowsChunk) {
                StoreSeoData::insert($storeSeoRowsChunk);
            }
        }

        // Store Category 
        $category_id_data = Category::pluck('id')->toArray();
        Schema::disableForeignKeyConstraints();
        DB::table('category_store')->truncate();
        Schema::enableForeignKeyConstraints();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\category_store.csv');
        if (isset($csvToArray[0])) {
            $categoryStores = [];
            foreach ($csvToArray as $row) {
                $row['id'] = (!isset($row['id']) ? reset($row) : $row['id']);
                if ($row['category_id'] != '') {
                    if (
                        !in_array($row['store_id'], $store_id_data)
                        || !in_array($row['category_id'], $category_id_data)
                    ) {
                        continue;
                    }
                    $categoryStores[] = [
                        'category_id' => $row['category_id'],
                        'store_id' => $row['store_id'],
                        'network_category_id' => 1,
                        'created_at' => $row['created_at'],
                        'updated_at' => $row['updated_at'],
                    ];
                }
            }
            if (!empty($categoryStores)) {
                foreach (array_chunk($categoryStores, 500) as $categoryStoresChunk) {
                    DB::table('category_store')->insert($categoryStoresChunk);
                }
            }
        }

        // Store Cashback Data
        Schema::disableForeignKeyConstraints();
        DB::table('store_cashbacks')->truncate();
        Schema::enableForeignKeyConstraints();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\store_cashbacks.csv');
        if (isset($csvToArray[0])) {
            $storeCashbackData = [];
            foreach ($csvToArray as $storeCashback) {
                $storeCashback['id'] = (!isset($storeCashback['id']) ? reset($storeCashback) : $storeCashback['id']);
                if (
                    !arrayValueExists($storeCashback, 'id')
                    || !arrayValueExists($storeCashback, 'store_id')
                    || !in_array($storeCashback['store_id'], $store_id_data)
                ) {
                    continue;
                }
                $storeCashbackData[] = [
                    'id' => $storeCashback['id'],
                    'store_id' => $storeCashback['store_id'],
                    'type' => arrayValueExists($storeCashback, 'type') ? $storeCashback['type'] : null,
                    'cashback_name' => arrayValueExists($storeCashback, 'cashback_name') ? $storeCashback['cashback_name'] : null,
                    'image' => arrayValueExists($storeCashback, 'image') ? $storeCashback['image'] : null,
                    'click_url' => arrayValueExists($storeCashback, 'click_url') ? $storeCashback['click_url'] : null,
                    'sale_commission' => arrayValueExists($storeCashback, 'sale_commission') ? $storeCashback['sale_commission'] : null,
                    'currency' => arrayValueExists($storeCashback, 'currency') ? $storeCashback['currency'] : null,
                    'detail' => arrayValueExists($storeCashback, 'detail') ? $storeCashback['detail'] : null,
                    'network_detail' => arrayValueExists($storeCashback, 'network_detail') ? $storeCashback['network_detail'] : null,
                    'deeplink_url' => arrayValueExists($storeCashback, 'deeplink_url') ? $storeCashback['deeplink_url'] : null,
                    'tracking_url' => arrayValueExists($storeCashback, 'tracking_url') ? $storeCashback['tracking_url'] : null,
                    'network_id' => (arrayValueExists($storeCashback, 'network_id') &&  $storeCashback['network_id'] != '') ? $storeCashback['network_id'] : 0,
                    'default' => arrayValueExists($storeCashback, 'default') ? $storeCashback['default'] : null,
                    'created_at' => arrayValueExists($storeCashback, 'created_at') ? dbDate($storeCashback['created_at']) : $now,
                    'updated_at' => arrayValueExists($storeCashback, 'updated_at') ? dbDate($storeCashback['updated_at']) : $now,
                    'deleted_at' => null,
                ];
            }
            foreach (array_chunk($storeCashbackData, 500) as $storeCashbackDataChunk) {
                StoreCashback::insert($storeCashbackDataChunk);
            }
        }
    }
}
