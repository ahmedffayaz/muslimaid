<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Page;
use App\Models\Store;
use App\Models\Category;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use App\Http\Resources\Category\CategoryResource;

class CategoryController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        try {
            $page = Page::where('slug', 'categories')->whereType('system')->pluck('banner_image')->firstOrFail();

            $categories = Category::when($request->has('letter'), function ($query) use ($request) {
                $query->where('name', 'like', $request->input('letter') . '%');
            })->with(['childs' => function ($query) {
                $query->withCount('stores');
            }])->withCount('stores')->where('parent_id', 0)->paginate(12);

            if ($categories->count() == 0) {
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
                    'main_banner' => getBannerImageUrl($page),
                    'categories' => CategoryResource::collection($categories)
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

    public function show(Request $request, $slug)
    {
        try {
            $stores = Store::when($request->has('letter'), function ($query) use ($request) {
                $query->where('name', 'like', $request->input('letter') . '%');
            })->whereHas('categories', function ($query) use ($slug) {
                $query->whereSlug($slug)->where('parent_id', '!=', 0)->whereStatus(1);
            })->with(['categories' => function ($query) use ($slug) {
                $query->whereSlug($slug)->where('parent_id', '!=', 0)->whereStatus(1);
            }])->whereStatus('active')->paginate(12);

            if ($stores->count() == 0) {
                $data = [
                    'status' => JsonResponse::HTTP_OK,
                    'message' => 'No store found'
                ];
                return response()->json($data, JsonResponse::HTTP_OK);
            }

            $data = [
                'status' => JsonResponse::HTTP_OK,
                'message' => 'success',
                'data' => [
                    'categories' => [
                        [
                            'main_banner' => getBannerImageUrl($stores[0]->categories->first()->banner_upload),
                            'title' => $stores[0]->categories->first()->name,
                            'stores' => StoreResource::collection($stores)
                        ]
                    ],
                ]
            ];

            return response()->json($data, JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, try again'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
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
            }])->whereStatus('active')->paginate(12);

            $data = [
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Success',
                'data' => [
                    'main_banner' => ($stores[0]->categories[0]->banner_type != 'link') ? getBannerImageUrl($stores[0]->categories[0]->banner_upload, 'upload', $stores[0]->categories[0]) : $stores[0]->categories[0]->banner_link,
                    'categories' => StoreResource::collection($stores)
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
