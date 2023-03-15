<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Store;
use App\Http\Controllers\Controller;

class StoreController extends Controller
{
    public function index($letter = null)
    {
        if (!empty($letter)) {
            $stores = Store::where('name', 'like', $letter . '%')->get();
            return view('frontend.stores.show-by-letter', compact('stores', 'letter'));
        }

        $groups = Store::latest()->get()->sortBy('name')->groupBy(function ($store) {
            return strtoupper(substr($store->name, 0, 1));
        });

        return view('frontend.stores.index', compact('groups'));
    }

    public function show($slug)
    {
        $store = Store::where('slug', $slug)->first();
        if (empty($store)) abort(404);

        $count = $store->cashbacks ? count($store->cashbacks) : 0;
        return view('frontend.stores.show', compact('store', 'count'));
    }
}
