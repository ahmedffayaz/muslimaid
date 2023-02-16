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
        $now = Carbon::parse(now())->format('Y-m-d H:i:s');
        Schema::disableForeignKeyConstraints();
        DB::table('store_images')->truncate();
        Schema::enableForeignKeyConstraints();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\store_images.csv');
        if (isset($csvToArray[0])) {
            $storeImages = [];
            foreach ($csvToArray as $storeImage) {
                $storeImage['id'] = (!isset($storeImage['id']) ? reset($storeImage) : $storeImage['id']);
                $store_data = Store::where('id',$storeImage['store_id'])->first();
                if (
                    !arrayValueExists($storeImage, 'id')
                    || !arrayValueExists($storeImage, 'store_id')
                    || !arrayValueExists($storeImage, 'title')
                    || !arrayValueExists($storeImage, 'image')
                    || !arrayValueExists($storeImage, 'image_type')
                    || !isset($store_data)
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
                $store_data = Store::where('id',$storeReview['store_id'])->first();
                if (
                    !arrayValueExists($storeReview, 'id')
                    || !arrayValueExists($storeReview, 'store_id')
                    || !arrayValueExists($storeReview, 'user_id')
                    || !isset($store_data)
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
                $store_data = Store::where('id',$storeSeoData['store_id'])->first();
                if (
                    !arrayValueExists($storeSeoData, 'id')
                    || !arrayValueExists($storeSeoData, 'store_id')
                    || !arrayValueExists($storeSeoData, 'url')
                    || !arrayValueExists($storeSeoData, 'type')
                    || !arrayValueExists($storeSeoData, 'key')
                    || !arrayValueExists($storeSeoData, 'value')
                    || !isset($store_data)
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
        Schema::disableForeignKeyConstraints();
        DB::table('category_store')->truncate();
        Schema::enableForeignKeyConstraints();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\category_store.csv');
        if (isset($csvToArray[0])) {
            $categoryStores = [];
            foreach ($csvToArray as $categoryStores) {
                $categoryStores['id'] = (!isset($categoryStores['id']) ? reset($categoryStores) : $categoryStores['id']);
                if ($categoryStores['category_id'] != '') {
                    $category = Category::find($categoryStores['category_id']);
                    $store = Store::find($categoryStores['store_id']);
                    if (isset($store) && isset($category)) {
                        $store->categories()->attach($category, ['network_category_id' => 1, 'created_at' => $categoryStores['created_at'], 'updated_at' => $categoryStores['updated_at']]);
                    }
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
                $store_data = Store::where('id',$storeCashback['store_id'])->first();
                if (
                    !arrayValueExists($storeCashback, 'id')
                    || !arrayValueExists($storeCashback, 'store_id')
                    || !isset($store_data)
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
