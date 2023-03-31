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
use App\Http\Resources\Category\CategoryResource;

class CategoryController extends Controller
{
    use ApiResponser;


    public function index()
    {
        try {
            $page = Page::where('slug', 'categories')->whereType('system')->pluck('banner_image')->first();

            if (!empty($letter)) {
                $categories = Category::where('name', 'like', $letter . '%')->with(['childs' => function ($query) {
                    $query->withCount('stores');
                }])->withCount('stores')->where('parent_id', 0)->orderBy('sort', 'desc')->orderBy('name', 'asc')->get();
            } else {
                $categories = Category::with(['childs' => function ($query) {
                    $query->withCount('stores');
                }])->withCount('stores')->where('parent_id', 0)->orderBy('sort', 'desc')->orderBy('name', 'asc')->get();
            }

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'data' => [
                    'main_banner' => getBannerImageUrl($page),
                    'categories' => CategoryResource::collection($categories)
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, try again'
            ]);
        }
    }
    public function show($slug)
    {
        try {

            $stores = Store::whereHas('categories', function ($query) use ($slug) {
                $query->whereSlug($slug)->whereStatus(1);
            })->with(['categories' => function ($query) use ($slug) {
                $query->whereSlug($slug)->whereStatus(1);
            }])->whereStatus('active')->paginate(2);

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'data' => [
                    'main_banner' => getBannerImageUrl($stores[0]->categories->first()->banner_upload),
                    'categories' => CategoryResource::collection($stores)
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage().'Something went wrong, try again'
            ]);
        }
    }
    public function childCategories($slug)
    {

        $parent_category = Category::with('stores')->where('slug', $slug)->first();
        $categories = Category::select('*')->where('parent_id', $parent_category->id);
        if (request()->get('search')) {
            $categories = $categories->where('name', 'like', '%' . request()->get('search') . '%');
        }
        if (request()->get('name_sort')) {
            $order = request()->get('name_sort') == 'descending' ? 'desc' : 'asc';
            $categories = $categories->orderBy('name', $order);
        } else {
            $categories = $categories->orderBy('id', 'DESC');
        }
        $categories = $categories->latest()->get();
        return CategoryResource::collection($categories);
    }
}
