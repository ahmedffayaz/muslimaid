<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Page;
use App\Models\Store;
use App\Models\Category;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\SubCategoryResource;
use App\Http\Resources\StoreDetailResource;

class CategoryController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        try {
            $page = Page::where('slug', 'categories')->whereType('system')->pluck('banner_image')->firstOrFail();
            $categories = Category::when($request->has('letter'), function ($query) use ($request) {
                $query->where('name', 'like', $request->input('letter') . '%');
            })->when($request->has('parent_id'), function ($query) use ($request) {
                $query->where('id', $request->input('parent_id'));
            })->with(['childs' => function ($query) {
                $query->orderBy('name', 'asc')->withCount('stores');
            }])->withCount('stores')->where('parent_id', 0)->orderBy('name', 'asc')->paginate(20)->appends(request()->input());


            if ($categories->count() == 0) {
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
                    'main_banner' => getBannerImageUrl($page),
                    'categories' => CategoryResource::collection($categories),
                    'meta_data' => [
                        "next" => $categories->nextPageUrl(),
                        "previous" => $categories->previousPageUrl(),
                        "per_page" => 20,
                        "total" => $categories->total(),
                        "current_page" => $categories->currentPage(),
                        "total_pages" => $categories->lastPage(),
                        "first" => $categories->firstItem(),
                        "last" => $categories->lastItem()
                    ]
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

    public function show(Request $request, $slug)
    {
        try {
            $stores = Store::when($request->has('letter'), function ($query) use ($request) {
                $query->where('name', 'like', $request->input('letter') . '%');
            })->whereHas('categories', function ($query) use ($slug) {
                $query->whereSlug($slug)->where('parent_id', '!=', 0)->whereStatus(1);
            })->with(['categories' => function ($query) use ($slug) {
                $query->whereSlug($slug)->where('parent_id', '!=', 0)->whereStatus(1);
            }])->whereStatus('active')->paginate(20)->appends(request()->input());

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
                'message' => 'success',
                'data' => [
                    'categories' => [
                        [
                            'main_banner' => getBannerImageUrl($stores[0]->categories->first()->banner_upload),
                            'title' => $stores[0]->categories->first()->name,
                            'stores' => StoreResource::collection($stores),
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
                    ],
                ]
            ];

            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong, try again',
                'data' => []
            ], 500);
        }
    }

    public function getCashblackYourDoor(Request $request, $slug)
    {
        try {

            $stores = Store::when($request->has('letter'), function ($query) use ($request) {
                $query->where('name', 'like', $request->input('letter') . '%');
            })->when($request->orderBy == 'latest', function ($query) use ($request) {
                $query->latest();
            })->when($request->orgerBy == 'popularity', function ($query) use ($request) {
                $query->withCount('clicks')->orderByDesc('clicks_count');
            })->when($request->orderBy == 'cashback-amount', function ($query) use ($request) {
                $query->whereHas('cashbacks', function ($query) {
                    $query->whereType('fixed');
                })->get()->filter(function ($query) {
                    $cashback = $query->getCashback();
                    return (strpos($cashback, '£') !== false);
                });
            })->when($request->orderBy == 'cashback-percentage', function ($query) {
                $query->whereHas('cashbacks', function ($query) {
                    $query->where('type', 'percentage');
                })->get()->filter(function ($query) {
                    $cashback = $query->getCashback();
                    return (strpos($cashback, '%') !== false);
                });
            })->whereHas('categories', function ($query) use ($slug) {
                $query->whereSlug($slug)->where('parent_id', 0)->whereStatus(1);
            })->with(['categories' => function ($query) use ($slug) {
                $query->whereSlug($slug)->where('parent_id', 0)->whereStatus(1);
            }])->whereStatus('active')->paginate(20)->appends(request()->input());

            $data = [
                'status' => 200,
                'message' => 'Success',
                'data' => [
                    'main_banner' => ($stores[0]->categories[0]->banner_type != 'link') ? getBannerImageUrl($stores[0]->categories[0]->banner_upload, 'upload', $stores[0]->categories[0]) : $stores[0]->categories[0]->banner_link,
                    'categories' => StoreResource::collection($stores),
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
        } catch (Exception $e) {
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }

    public function getCategoryStores($slug)
    {
        try {
            $category = Category::where('slug', $slug)->first();
            if (!$category) {
                $data = [
                    'status' => 200,
                    'message' => 'No Category found',
                    'data' => []
                ];
                return response()->json($data, 200);
            }
            $categoryStores = $category->stores()->paginate(20)->appends(request()->input());
            $data = [
                'status' => 200,
                'message' => 'Category details retrieved successfully',
                'data' => [
                    'category' => new SubCategoryResource($category),
                    'parent' => isset($category->parent) ? new SubCategoryResource($category->parent) : (object)[],
                    'child' => !empty($category->childs) ? SubCategoryResource::collection($category->childs) : [],
                    'stores' => !empty($category->stores) ? StoreResource::collection($categoryStores) : [],
                    'total' => count($category->stores),
                    'metaData' => [
                        "next" => $categoryStores->nextPageUrl(),
                        "previous" => $categoryStores->previousPageUrl(),
                        "per_page" => 20,
                        "total" => $categoryStores->total(),
                        "current_page" => $categoryStores->currentPage(),
                        "total_pages" => $categoryStores->lastPage(),
                        "first" => $categoryStores->firstItem(),
                        "last" => $categoryStores->lastItem()
                    ]
                ]
            ];
            return response()->json($data, 200);
        } catch (Exception $e) {
            $data = [
                'status' => 500,
                'message' =>  $e->getMessage(),
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }
    public function cuisineFilter(Request $request)
    {
        try {
            $page = Page::whereSlug('stores')->whereType('system')->whereStatus('active')->pluck('banner_image')->firstOrFail();
            $category = Category::where(function ($query) {
                $query->where('visibility', '!=', 'hidden')
                    ->orWhereNull('visibility');
            })->whereSlug('cashblack-to-your-door')->with('stores')->whereStatus('1')->first();
            $allStores =  $category->stores();
            $cuisineCategories = $request->input('cuisine');

            if (!empty($cuisineCategories)) {
                $allStores = $allStores->whereHas('categories', function ($query) use ($cuisineCategories) {
                    $query->whereIn('name', $cuisineCategories);
                });
            }
            $stores = $allStores->paginate(20)->appends(request()->input());
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
                    'stores' => StoreResource::collection($stores),
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
