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


    public function index($letter = null)
    {
        try {
            $page = Page::where('slug', 'categories')->whereType('system')->pluck('banner_image')->firstOrFail();

            if (!empty($letter)) {
                $categories = Category::where('name', 'like', $letter . '%')->with(['childs' => function ($query) {
                    $query->withCount('stores');
                }])->withCount('stores')->where('parent_id', 0)->orderBy('sort', 'desc')->orderBy('name', 'asc')->paginate(12);
            } else {
                $categories = Category::with(['childs' => function ($query) {
                    $query->withCount('stores');
                }])->withCount('stores')->where('parent_id', 0)->orderBy('sort', 'desc')->orderBy('name', 'asc')->paginate(12);
            }

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

    public function show($slug, $letter = null)
    {
        try {
            if (!empty($letter)) {
                $stores = Store::where('name', 'like', $letter . '%')
                    ->whereHas('categories', function ($query) use ($slug) {
                        $query->whereSlug($slug)->where('parent_id', '!=', 0)->whereStatus(1);
                    })->with(['categories' => function ($query) use ($slug) {
                        $query->whereSlug($slug)->whereStatus(1);
                    }])->whereStatus('active')->paginate(12);
            } else {
                $stores = Store::whereHas('categories', function ($query) use ($slug) {
                    $query->whereSlug($slug)->where('parent_id', '!=', 0)->whereStatus(1);
                })->with(['categories' => function ($query) use ($slug) {
                    $query->whereSlug($slug)->where('parent_id', '!=', 0)->whereStatus(1);
                }])->whereStatus('active')->paginate(12);
            }

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
}
