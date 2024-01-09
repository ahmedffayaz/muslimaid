<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $page = Page::where('slug', 'search')->whereIn('type', ['special', 'general'])->first();
        if (empty($page)) abort(404);
        $stores = Store::select('id', 'name', 'slug', 'status', 'created_at')->where('status', 'active');
        $term = null;
        $search = $request->input('search');

        if ($search) {
            $stores->where('name', 'like', '%' . str_replace(' ', '%', $request->input('search')) . '%')
            ->orWhereHas('storeRuleData', function ($query) use ($search) {
                $query->where('key', 'meta:keywords')->where('value', 'like', '%' . $search . '%');
            });
            $term = $request->input('search');
        }

        $stores = $stores->withCount('cashbacks')->latest()->paginate(20);

        return view('frontend.pages.search', compact('stores', 'term', 'page'));
    }

    public function suggestions(Request $request)
    {
        if (empty($request->input('search'))) return null;

        $stores = Store::select('id', 'name', 'slug', 'status', 'created_at')
            ->where('name', 'like', '%' . str_replace(' ', '%', $request->input('search')) . '%')
            ->orWhereHas('storeRuleData', function ($query) use ($request) {
                $query->where('key', 'meta:keywords')->where('value', 'like', '%' . $request->input('search') . '%');
            })->whereStatus('active')->limit(20)->get();

        return view('frontend.layouts.includes.search-suggestions', compact('stores'));
    }
}
