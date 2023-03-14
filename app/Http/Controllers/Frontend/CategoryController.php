<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Store;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('childs')->where('parent_id', '0')->get();
        $subCategories = Category::with('stores')->where('parent_id', '0')->get();
        return view('frontend.categories.index', compact('categories'));
    }

    public function show(Request $request, $slug)
    {
        $mainCategory = Category::whereSlug($slug)->first();

        if ($request->ajax()) {
            if ($request->has('id')) {
                $locations = Store::when($request->has('id'), function ($query) use ($request) {
                    $query->whereHas('categories', function ($query) use ($request) {
                        $query->whereIn('category_id', $request->id);
                    });
                })->with('logo', 'storeAddress')->paginate(25);
            } else {
                $locations = Store::when(optional($mainCategory)->id, function ($query) use ($mainCategory) {
                    $query->whereHas('categories', function ($query) use ($mainCategory) {
                        $query->where('category_id', $mainCategory->id);
                    });
                })->where('status', 'active')->with('logo', 'storeAddress')->paginate(25);
            }
            return view('frontend.stores.stores', compact('locations'));
        }

        $locations = Store::when(optional($mainCategory)->id, function ($query) use ($mainCategory) {
            $query->whereHas('categories', function ($query) use ($mainCategory) {
                $query->where('category_id', $mainCategory->id);
            });
        })->where('status', 'active')->with('logo', 'storeAddress')->paginate(25);

        if (!isset($mainCategory) || is_null($mainCategory)) {
            return abort(404);
        }

        $categories = Category::with(['stores.storeAddress'])->whereParentId($mainCategory['id'])->orderBy('name', 'ASC')->get();
        $location_array = array();

        foreach ($categories as $category) {
            foreach ($category->stores as $store) {
                $location_array['des'][] = $store->description;
                foreach ($store->storeAddress as $address) {
                    $location_array['lat'][] = $address->latitude;
                    $location_array['long'][] = $address->longitude;
                }
            }
        }
        
        return view('frontend.categories.show', compact('locations', 'categories', 'location_array', 'mainCategory'));
    }
}
