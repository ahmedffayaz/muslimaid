<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Tag;
use App\Models\Slide;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use App\Http\Resources\Home\SlideResource;
use App\Http\Resources\StoreDetailResource;
use App\Http\Resources\Home\FeaturedCategoryResource;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $slides = Slide::whereHas('slider', function ($query) {
                $query->whereName('Mobile Home');
            })->with(['store' => function ($query) {
                $query->select('id', 'name', 'slug');
            }])->orderBy('order', 'ASC')->limit(10)->get();

            $featuredTag = Tag::where('title', 'app_featured1_homepage')->pluck('id')->first();

            $featuredStores = Store::select('id', 'name', 'slug', 'status')
                ->whereHas('tags', function ($query) use ($featuredTag) {
                    $query->when(isset($featuredTag), function ($query) {
                        $query->where('title', 'app_featured1_homepage');
                    }, function ($query) {
                        $query->where('title', 'app_featured_homepage');
                    });
                })->latest()->take(10)->whereStatus('active')->get();

            $featuredCategories = Category::whereHas('tags', function ($query) use ($featuredTag) {
                $query->when(isset($featuredTag), function ($query) {
                    $query->where('title', 'app_featured1_homepage');
                }, function ($query) {
                    $query->where('title', 'app_featured_homepage');
                });
            })->with(['stores' => function ($query) use ($featuredTag) {
                $query->whereHas('categories.tags', function ($query) use ($featuredTag) {
                    $query->when(isset($featuredTag), function ($query) {
                        $query->where('title', 'app_featured1_homepage');
                    }, function ($query) {
                        $query->where('title', 'app_featured_homepage');
                    });
                })->whereStatus('active')->limit(10);
            }])->whereStatus(1)->limit(10)->get();

            $data = [
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Success',
                'data' => [
                    'base_url' => url('/'),
                    'main_banner_images' => SlideResource::collection($slides),
                    'featured_stores' => StoreDetailResource::collection($featuredStores),
                    'featured_categories' => FeaturedCategoryResource::collection($featuredCategories)
                ]
            ];
            return response()->json($data, JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            $data = [
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, try again'
            ];
            return response()->json($data, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
