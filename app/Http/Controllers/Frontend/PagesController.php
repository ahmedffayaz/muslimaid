<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Category;
use App\Models\Page;
use App\Models\Blog;
use Harimayco\Menu\Models\Menus;
use Harimayco\Menu\Models\MenuItems;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->first();
        if($slug == 'offers'){
            return view('frontend.pages.offers',compact('page'));
        }
        if($slug == 'vouchers'){
            $stores = Store::has('vouchers')->latest()->paginate(10);
        $term = null;
        return view('frontend.pages.vouchers',compact('stores','term','page'));
        }

        return view('frontend.pages.single_page',compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function offers(){
        $stores = Store::latest()->get();
        return view('frontend.pages.offers',compact('stores'));
    }


    public function cashbackByCategory($slug){
        $category = Category::where('slug',$slug)->first();
        $stores = $category->stores()->paginate(20);
        return view('frontend.pages.cashback_by_category',compact('stores','category'));
    }
    public function topStores(){
        $stores = Store::withCount('clicks')
        ->orderBy('clicks', 'desc')->paginate(20);
        return view('frontend.pages.top_cashback',compact('stores'));
    }

    public function trending(){
       
        $stores =  Store::has('clicks')->with('clicks')->get()->sortByDesc(function($store)
        {
            return $store->clicks->count();
        });
        return view('frontend.pages.trending',compact('stores'));
    }
    public function about(){
        return view('frontend.pages.about');
    }

    public function contact(){
        return view('frontend.pages.contact');
    }

    public function blog(){
        $blogs = Blog::latest()->get();
        return view('frontend.pages.blog',compact('blogs'));
    }

    public function login(){
        return view('client-dashboard.login');
    }

    public function search(Request $request, Store $stores){
        $stores = $stores->newQuery();
        $term = null;
        if ($request->input('search')) {
            $stores->where('name','like', '%'.$request->input('search').'%');
            $term = $request->input('search');
           
        }
        $stores = $stores->latest()->paginate(20);
        return view('frontend.pages.search',compact('stores','term'));
    }

    public function searchSuggestions(Request $request, Store $stores){
        $stores = $stores->newQuery();
        $term = null;
        if ($request->input('term')) {
            $stores->where('name','like', '%'.$request->input('term').'%');
            $term = $request->input('term');
           
        }
        $stores = $stores->latest()->get();
        return view('frontend.components.search_suggestions',compact('stores','term'))->render();
    }

    public function vouchers(){
        $stores = Store::has('vouchers')->latest()->paginate(10);
        $term = null;
        return view('frontend.pages.vouchers',compact('stores','term'));
    }

    public function blogPost($slug){
        $blog = Blog::where('slug', $slug)->first();

        return view('frontend.pages.single_blog',compact('blog'));
    }
}
