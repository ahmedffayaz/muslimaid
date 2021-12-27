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
        return CategoryResource::collection(Category::where('parent_id',0)->latest()->get());
    }
    public function show($slug){
        $category = Category::with('stores')->where('slug',$slug)->first();
        $stores = $category->stores();
        $limit = request()->has('per_page') ? request()->get('per_page') : 10;
        
        if(request()->get('search')){
            $stores = $stores->where('name','like','%'.request()->get('search').'%');
        }
        if(request()->get('name_sort')){
            $order = request()->get('name_sort') == 'descending' ? 'desc' :'asc';
            $stores = $stores->orderBy('name',$order);
        }else{
            $stores = $stores->orderBy('id','DESC');
        }
        $stores = $stores->paginate($limit);
        $stores->appends(['search' => request()->get('search'), 'per_page'=>$limit,'name_sort' => request()->get('name_sort')]);
    
        return StoreResource::collection($stores);

    }
    public function childCategories($slug){

        $parent_category = Category::with('stores')->where('slug',$slug)->first();
        $categories = Category::select('*')->where('parent_id',$parent_category->id);
        if(request()->get('search')){
            $categories = $categories->where('name','like','%'.request()->get('search').'%');
        }
        if(request()->get('name_sort')){
            $order = request()->get('name_sort') == 'descending' ? 'desc' :'asc';
            $categories = $categories->orderBy('name',$order);
        }else{
            $categories = $categories->orderBy('id','DESC');
        }
        $categories = $categories->latest()->get();
        return CategoryResource::collection($categories);
    }
}
