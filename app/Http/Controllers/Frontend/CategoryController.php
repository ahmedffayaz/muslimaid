<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('childs')->where('parent_id','0')->get();
        $subCategories = Category::with('stores')->where('parent_id','0')->get();
        return view ('frontend.categories.index',compact('categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $categorySlug = Category::whereSlug($slug)->first();
        if(!empty($categorySlug) && $categorySlug['parent_id'] == 0)
        {
            $parentSlug = $slug;
            $chlidSlug = '';

        }else{
            $parentSlug = Category::whereId($categorySlug['parent_id'])->first();
            $parentSlug = $parentSlug['slug'];
            $chlidSlug = $slug;
        }if($categorySlug)
        return view('frontend.categories.detail',compact('parentSlug','chlidSlug'));
    }
}
