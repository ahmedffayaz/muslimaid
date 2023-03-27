<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Tag;
use App\Models\Slide;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Home\SlideResource;
use App\Http\Resources\Home\FeaturedStoreResource;
use App\Http\Resources\Home\FeaturedCategoryResource;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $slides = Slide::whereHas('slider', function ($query) {
                $query->whereName('Mobile Home');
            })->with('store')->orderBy('order', 'ASC')->limit(10)->get();

            $featureTag = Tag::where('title', 'app_featured1_homepage')->pluck('id')->first();

            $featuredStores = Store::whereHas('tags', function ($query) use ($featureTag) {
                isset($featureTag) ? $query->where('title', 'app_featured1_homepage') : $query->where('title', 'app_featured_homepage');
            })->with('cashbacks')->latest()->limit(10)->get();

            $featuredCategories = Category::whereHas('tags', function ($query) use ($featureTag) {
                isset($featureTag) ? $query->where('title', 'app_featured1_homepage') : $query->where('title', 'app_featured_homepage');
            })->with('stores')->latest()->limit(10)->get();

            $data = [
                'status' => JsonResponse::HTTP_OK,
                'data' => [
                    'base_url' => url('/'),
                    'main_banner_images' => SlideResource::collection($slides),
                    'featured_stores' => FeaturedStoreResource::collection($featuredStores),
                    'featured_categories' => FeaturedCategoryResource::collection($featuredCategories)
                ]
            ];
            return response()->json($data, JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            return response()->json('Something went wrong, try again');
        }
    }
}
