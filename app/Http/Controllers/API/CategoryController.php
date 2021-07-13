<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Models\Store;
use App\Http\Resources\StoreResource;

class CategoryController extends Controller
{
    use ApiResponser;


    public function index(){
        return CategoryResource::collection(Category::latest()->get());
    }
    public function show($slug){

        $category = Category::with('stores')->where('slug',$slug)->first();
        $stores = $category->stores;

        return StoreResource::collection($stores);

    }
}
