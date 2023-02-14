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
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $stores = Store::where('feature_homepage', 1)->latest()->get();
        $languages = Language::orderBy('id', 'desc')->get();
        $featured_categories = Category::where('feature_homepage', 1)->orderBy('name', 'ASC')->latest()->get();
        $slider = Slider::where('name', 'Home')->first();
        $testimonials = Testimonial::where('status', 'active')->orderByDesc('id')->take(5)->get();

        return view('frontend.pages.home', compact('stores', 'languages', 'featured_categories', 'slider', 'testimonials'));
    }

    public function quickSearch(Request $request)
    {
        if (empty($request->input('search'))) return null;

        $stores = Store::where('name', 'like', '%' . $request->input('search') . '%')
            ->orWhereHas('storeRuleData', function ($query) use ($request) {
                $query->where('key', 'meta:keywords')->where('value', 'like', '%' . $request->input('search') . '%');
            })->whereStatus('active')->get();

        return view('frontend.components.search-suggestions', compact('stores'));
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
