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
use App\Http\Resources\SearchResources;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function mainSearch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'search_text' => ['required'],
            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 406,
                    'message' => $validator->errors()->first()
                ];
                return response()->json($data, 406);
            } else {
                $search = $request->search_text;
                $page = Page::whereSlug('stores')->whereType('system')->whereStatus('active')->pluck('banner_image')->firstOrFail();
                $stores =  Store::where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('storeRuleData', function ($query) use ($search) {
                        $query->where('key', 'meta:keywords')->where('value', 'like', '%' . $search . '%');
                    })->whereStatus('active')->get();

                if ($stores->count() == 0) {
                    $data = [
                        'status' => JsonResponse::HTTP_OK,
                        'message' => 'No store found'
                    ];
                    return response()->json($data, JsonResponse::HTTP_OK);
                }

                $data = [
                    'status' => JsonResponse::HTTP_OK,
                    'message' => 'Success',
                    'data' => [
                        'main_banner_image' => getBannerImageUrl($page),
                        'stores' => SearchResources::collection($stores)
                    ]
                ];
                return response()->json($data, JsonResponse::HTTP_OK);
            }
        } catch (Exception $e) {
            $data = [
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage() . 'Something went wrong, try again.'
            ];
            return response()->json($data, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
