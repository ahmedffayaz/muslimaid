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

        // if ($request->ajax()) {
        //     if ($request->has('id')) {
        //         $locations = Store::when($request->has('id'), function ($query) use ($request) {
        //             $query->whereHas('categories', function ($query) use ($request) {
        //                 $query->whereIn('category_id', $request->id);
        //             });
        //         })->with('logo', 'storeAddress','slug');
        //     } else {
        //         $locations = Store::when(optional($mainCategory)->id, function ($query) use ($mainCategory) {
        //             $query->whereHas('categories', function ($query) use ($mainCategory) {
        //                 $query->where('category_id', $mainCategory->id);
        //             });
        //         })->where('status', 'active')->with('logo', 'storeAddress');
        //     }
          
        //     if(isset($request->orderBy)){
        //         $orderByArr = explode("-",$request->orderBy);
        //         $locations->orderBy($orderByArr[0], $orderByArr[1]);
        //     } 
        //     $locations = $locations->paginate(25);
        //     $locations->appends(['orderBy' => $request->orderBy]);
        //     return view('frontend.stores.stores', compact('locations', 'slug'));
        // }

        // $locations = Store::when(optional($mainCategory)->id, function ($query) use ($mainCategory) {
        //     $query->whereHas('categories', function ($query) use ($mainCategory) {
        //         $query->where('category_id', $mainCategory->id);
        //     });
        // })->where('status', 'active')->with('logo', 'storeAddress');
        // if(isset($request->orderBy)){
        //     $orderByArr = explode("-",$request->orderBy);
        //     $locations->orderBy($orderByArr[0], $orderByArr[1]);
        // }
        
        // $locations = $locations->paginate(25);
        // $locations->appends(['orderBy' => $request->orderBy]);
        // if (!isset($mainCategory) || is_null($mainCategory)) {
        //     return abort(404);
        // }
        // $categories = Category::with(['stores.storeAddress'])->whereParentId($mainCategory['id'])->orderBy('name', 'ASC')->get();
        // $location_array = array();
        // foreach ($categories as $category) {
        //     foreach ($category->stores as $store) {
        //         $location_array['des'][] = $store->description;
        //         foreach ($store->storeAddress as $address) {
        //             $location_array['lat'][] = $address->latitude;
        //             $location_array['long'][] = $address->longitude;
        //         }
        //     }
        // }
        // return view('frontend.stores.location', compact('locations', 'categories', 'location_array', 'mainCategory', 'slug'));
    }

    public function show($slug)
    {
        $store = Store::where('slug', $slug)->first();
        if (empty($store)) abort(404);

        $count = $store->cashbacks ? count($store->cashbacks) : 0;
        return view('frontend.stores.show', compact('store', 'count'));
    }
}
