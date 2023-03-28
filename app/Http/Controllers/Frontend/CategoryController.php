<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stevebauman\Location\Facades\Location;

class CategoryController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'categories')->whereType('system')->first();
        if (empty($page)) abort(404);

        $categories = Category::with('childs')->where('parent_id', '0')->orderBy('sort', 'desc')->orderBy('name', 'asc')->get();
        $subCategories = Category::with('stores')->where('parent_id', '0')->orderBy('sort', 'desc')->orderBy('name', 'asc')->get();

        return view('frontend.pages.single-page', compact('page', 'categories'));
    }

    public function show(Request $request, $slug)
    {
        $ip = request()->ip(); //Dynamic IP address get
        $data = Location::get($ip);

        $category = Category::whereSlug($slug)->with('childs')->first();

        if (empty($category)) abort(404);

        if ($request->ajax()) {
            if ($request->has('id')) {
                $stores = Store::when($request->has('id'), function ($query) use ($request) {
                    $query->whereHas('categories', function ($query) use ($request) {
                        $query->whereIn('category_id', $request->id);
                    });
                })->with('logo', 'storeAddress')->where('status', 'active')->get();
            } else {
                $stores = Store::when(optional($category)->id, function ($query) use ($category) {
                    $query->whereHas('categories', function ($query) use ($category) {
                        $query->where('category_id', $category->id);
                    });
                })->where('status', 'active')->with('logo', 'storeAddress')->get();
            }
            $stores = $this->categoriesView($request, $slug);
            $stores = $this->sortByDistance($data, $stores);
            $stores = $stores->sortBy('distance')->values()->paginate(25);
            return view('frontend.stores.stores', compact('stores'));
        }

        $stores = $category->stores()->where('status', 'active')->with('logo', 'storeAddress');


        $stores = $this->sortByDistance($data, $stores);
        $stores = $stores->paginate(25);
        $stores = $stores->sortBy('distance')->values()->paginate(25);
        $stores->appends(['orderBy' => $request->orderBy]);
        return view('frontend.categories.show', compact('category', 'stores', 'slug'));
    }

    private function sortByDistance($data, $stores)
    {
        // Calculate distance between user and each store
        foreach ($stores as $store) {
            $store->storeAddress = $store->storeAddress->first();

            if ($store->storeAddress) {
                $latitudeTo = $store->storeAddress->latitude;
                $longitudeTo = $store->storeAddress->longitude;

                $distance = $this->calculateDistance($data->latitude, $data->longitude, $latitudeTo, $longitudeTo);

                $store->distance = number_format((float)$distance, 2, '.', '');
            } else {
                $store->distance = 'Unknown';
            }
        }

        return $stores;
    }

    private function calculateDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
        $earthRadius = 6371; // km

        // Convert coordinates to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        // Calculate the differences
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        // Calculate the distance using the Haversine formula
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        $distance = $angle * $earthRadius;

        return $distance;
    }
    public function categoriesView(Request $request, $slug)
    {
        $category = Category::whereSlug($slug)->with('childs')->first();
        $perPage = $request->input('perPage');
        if (isset($request->orderBy)) {
            if ($request->orderBy == 'popularity') {
                $allStores = $category->stores()->with('clicks')->paginate($perPage);
            } else if ($request->orderBy == 'cashback-amount') {
                $allStores = $category->stores()->whereHas('cashbacks', function ($query) {
                    $query->where('type', 'fixed')->whereHas('currencyData', function ($query) {
                        $query->where('symbol', '£');
                    });
                })->get()->filter(function ($store) {
                    $cashback = $store->getCashback();
                    return (strpos($cashback, '£') !== false);
                })->sortByDesc(function ($store) {
                    return $store->getCashback();
                })->paginate($perPage);
            } else if ($request->orderBy == 'cashback-percentage') {
                $allStores = $category->stores()->whereHas('cashbacks', function ($query) {
                    $query->where('type', 'percentage');
                })->get()->filter(function ($store) {
                    $cashback = $store->getCashback();
                    return (strpos($cashback, '%') !== false);
                })->sortByDesc(function ($store) {
                    return $store->getCashback();
                })->paginate($perPage);
            } else {
                $orderByArr = explode("-", $request->orderBy);
                $allStores = $category->stores()->orderBy($orderByArr[0], $orderByArr[1])->paginate($perPage);
            }
        } else {
            $allStores = $category->stores()->latest()->paginate($perPage);
            $allStores->appends(['orderBy' => $request->orderBy]);
        }
        return view('frontend.categories.view', compact('allStores', 'category'))->render();
    }
}
