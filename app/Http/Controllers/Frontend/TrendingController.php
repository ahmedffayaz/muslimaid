<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TrendingController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'trending')->first();
        if (empty($page)) abort(404);

        $stores =  Store::has('clicks')->with('clicks')->get()->sortByDesc(function ($store) {
            return $store->clicks->count();
        });

        return view('frontend.trending.index', compact('page', 'stores'));
    }
}
