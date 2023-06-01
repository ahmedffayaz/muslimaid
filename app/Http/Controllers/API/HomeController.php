<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Tag;
use App\Models\Slide;
use App\Models\Store;
use App\Models\Category;
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
                    $query->where('title', 'app_featured1_homepage');
                })->latest()->take(10)->whereStatus('active')->get();


            $featuredCategories = Category::whereHas('tags', function ($query) use ($featuredTag) {
                $query->where('title', 'app_featured1_homepage');
            })->with('stores')->whereStatus(1)->limit(10)->get();


            $data = [
                'status' => 200,
                'message' => 'Success',
                'data' => [
                    'base_url' => url('/'),
                    'main_banner_images' => SlideResource::collection($slides),
                    'featured_stores' => StoreDetailResource::collection($featuredStores),
                    'featured_categories' => FeaturedCategoryResource::collection($featuredCategories)
                ]
            ];
            return response()->json($data, 200);
        } catch (Exception $e) {
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again',
                'data' => []
            ];
            return response()->json($data, 500);
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
                    'message' => $validator->errors()->first(),
                    'data' => []
                ];
                return response()->json($data, 406);
            } else {
                $search = $request->search_text;
                $page = Page::whereSlug('stores')->whereType('system')->whereStatus('active')->pluck('banner_image')->firstOrFail();
                $stores =  Store::where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('storeRuleData', function ($query) use ($search) {
                        $query->where('key', 'meta:keywords')->where('value', 'like', '%' . $search . '%');
                    })->whereStatus('active')->paginate(20)->appends(request()->input());

                if ($stores->count() == 0) {
                    $data = [
                        'status' => 200,
                        'message' => 'No store found',
                        'data' => []
                    ];
                    return response()->json($data, 200);
                }

                $data = [
                    'status' => 200,
                    'message' => 'Success',
                    'data' => [
                        'main_banner_image' => getBannerImageUrl($page),
                        'stores' => SearchResources::collection($stores),
                        'meta_data' => [
                            "next" => $stores->nextPageUrl(),
                            "previous" => $stores->previousPageUrl(),
                            "per_page" => 20,
                            "total" => $stores->total(),
                            "current_page" => $stores->currentPage(),
                            "total_pages" => $stores->lastPage(),
                            "first" => $stores->firstItem(),
                            "last" => $stores->lastItem()
                        ]
                    ]
                ];
                return response()->json($data, 200);
            }
        } catch (Exception $e) {
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }
}
