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
            $categories = Category::when($request->has('parent_id'), function ($query) use ($request) {
                $query->where('id', $request->input('parent_id'));
            })
            ->with(['childs' => function ($query) {
                $query->where('status', '1')->orderBy('sort', 'asc')->withCount('stores');
            }])
            ->withCount('stores')
            ->where('parent_id', 0)
            ->where('name', '!=', 'more') // Exclude the category with the name 'more'
            ->where('visibility', 'visible')
            ->where('status', '1')
            ->orderBy('sort', 'desc')
            ->orderBy('name', 'asc')
            ->paginate(20)
            ->appends(request()->input());
            
            // Get the 'more' category separately
            $moreCategories = Category::where('name', 'more')
            ->orWhere('name', '!=', 'more')
            ->where('visibility', 'more')
            ->where('parent_id', 0)
            ->with(['childs' => function ($query) {
                $query->orderBy('name', 'asc')->withCount('stores');
            }])
            ->withCount('stores')
            ->orderBy('sort', 'desc')
            ->orderBy('name', 'asc')
            ->get();
            
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
                    'more_categories' => CategoryResource::collection($moreCategories),
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

    public function getCategoryStores(Request $request, $slug)
    {
        try {
            $slug = isset($request->child) ? $request->child : $slug;
            $category = Category::where('slug', $slug)->first();
            if (!$category) {
                $data = [
                    'status' => 200,
                    'message' => 'No Category found',
                    'data' => []
                ];
                return response()->json($data, 200);
            }
            $categoryCuisine = $request->input('cuisine');
            $categoryStores = $category->stores()->when($request->has('letter'), function ($query) use ($request) {
                    $query->where('name', 'like', $request->input('letter') . '%');
                })->when($request->orderBy == 'latest', function ($query) use ($request) {
                    $query->latest();
                })->when($request->orderBy == 'popularity', function ($query) use ($request) {
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
                })
                ->whereHas('categories', function ($query) use ($slug) {
                    $query->whereSlug($slug)->whereStatus(1);
                })->with(['categories' => function ($query) use ($slug) {
                    $query->whereSlug($slug)->where('parent_id', 0)->whereStatus(1);
                }])->whereStatus('active')->when($categoryCuisine, function ($query) use ($categoryCuisine) {
                    $query->whereHas('categories', function ($query) use ($categoryCuisine) {
                        $query->whereIn('name', $categoryCuisine);
                    });
                })->orderBy('name', 'asc')->paginate(20)->appends(request()->input());


            $cuisine = Category::where('id', '158')->with(['childs' => function ($query) {
                $query->orderBy('name', 'asc')->withCount('stores');
            }])->withCount('stores')->where('parent_id', 0)->orderBy('name', 'asc')->get();
            $data = [
                'status' => 200,
                'message' => 'Category details retrieved successfully',
                'data' => [
                    'category' => new SubCategoryResource($category),
                    'parent' => isset($category->parent) ? new SubCategoryResource($category->parent) : (object)[],
                    'child' => !empty($category->childs) ? SubCategoryResource::collection($category->childs) : [],
                    'stores' => !empty($category->stores) ? StoreResource::collection($categoryStores) : [],
                    'cuisine' => count($cuisine) ? CategoryResource::collection($cuisine) : [],
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
                'message' =>  'Something went wrong, try again',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }
   
}
