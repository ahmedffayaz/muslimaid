<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreCashback;
use App\Models\Language;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Support\Facades\App;
use App\Models\Slider;


class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stores = Store::where('feature_homepage',1)->latest()->get();
        $languages = Language::orderBy('id', 'desc')->get();
        $featured_categories = Category::where('feature_homepage',1)->orderBy('name', 'ASC')->latest()->get();
        $slider = Slider::where('name','Home')->first();
        
        return view('frontend.pages.home',compact('stores','languages','featured_categories','slider'));
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
    public function show($id)
    {
        //
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
    public function setLocale($locale)
    {
        // dd($locale);
        App::setLocale($locale);
        session()->put('locale', $locale );

        return response()->json([
            'status' => true,
            'message' => 'Language changed!'
        ]);
        }
}
