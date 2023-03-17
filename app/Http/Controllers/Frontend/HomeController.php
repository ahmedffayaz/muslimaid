<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Tag;
use App\Models\Page;
use App\Models\Store;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Language;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Models\StoreCashback;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $page = Page::whereSlug('/')->whereType('system')->first();
        if (empty($page)) abort(404);

        $featureTag = Tag::where('title', 'feature1_homepage')->pluck('id')->first();

        $stores = Store::whereHas('tags', function ($query) use ($featureTag) {
            isset($featureTag)
                ? $query->where('title', 'feature1_homepage')
                : $query->where('title', 'feature_homepage');
        })->latest()->get();

        $languages = Language::orderBy('id', 'desc')->get();
        $featuredCategories = Category::where('feature_homepage', 1)->orderBy('name', 'ASC')->latest()->get();
        $slider = Slider::where('name', 'Home')->first();
        $testimonials = Testimonial::where('status', 'active')->orderBy('order_no')->take(5)->get();

        return view('frontend.home', compact('page', 'stores', 'languages', 'featuredCategories', 'slider', 'testimonials'));
    }

    public function quickSearch(Request $request)
    {
        if (empty($request->input('search'))) return null;

        $stores = Store::where('name', 'like', '%' . str_replace(' ', '%', $request->input('search')) . '%')
            ->orWhereHas('storeRuleData', function ($query) use ($request) {
                $query->where('key', 'meta:keywords')->where('value', 'like', '%' . $request->input('search') . '%');
            })->whereStatus('active')->limit(20)->get();

        return view('frontend.layouts.includes.search-suggestions', compact('stores'));
    }

    public function setLocale($locale)
    {
        App::setLocale($locale);
        session()->put('locale', $locale);

        return response()->json([
            'status' => true,
            'message' => 'Language changed!'
        ]);
    }
}
