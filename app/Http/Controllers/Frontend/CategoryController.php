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

        $categories = Category::where(function ($query) {
            $query->where('visibility', '!=', 'hidden')
                ->orWhereNull('visibility');
        })->with('childs')->where('parent_id', '0')->orderBy('sort', 'desc')->orderBy('name', 'asc')->get();
        $subCategories = Category::where(function ($query) {
            $query->where('visibility', '!=', 'hidden')
                ->orWhereNull('visibility');
        })->with('stores')->where('parent_id', '0')->orderBy('sort', 'desc')->orderBy('name', 'asc')->get();

        return view('frontend.pages.single-page', compact('page', 'categories'));
    }

    public function show(Request $request, $slug)
    {
        $ip =  request()->ip(); //Dynamic IP address get
        $data = Location::get($ip);
        $cuisineCategory = Category::where('slug', 'cuisine')->first();
        $childCuisines = $cuisineCategory->childs->pluck('name')->all();
        $category = Category::where(function ($query) {
            $query->where('visibility', '!=', 'hidden')
                ->orWhereNull('visibility');
        })->whereSlug($slug)->with('childs')->whereStatus('1')->first();

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
            $stores = sortByDistance($data, $stores);
            $stores = $stores->sortBy('distance')->values()->paginate(25);
            return view('frontend.stores.stores', compact('stores'));
        }

        $stores = $category->stores()->where('status', 'active')->with('logo', 'storeAddress');
        $stores = $stores->paginate(25);
        $stores = sortByDistance($data, $stores);
        $stores = $stores->sortBy('distance')->values()->paginate(25);
        $stores->appends(['orderBy' => $request->orderBy]);
        $cuisine = isset( $request->cuisine) ?  $request->cuisine : '';
        return view('frontend.categories.show', compact('category', 'stores', 'slug','cuisine','childCuisines'));
    }



    public function categoriesView(Request $request, $slug)
    {
        $ip =  request()->ip(); //Dynamic IP address get
        $data = Location::get($ip);
        $category = Category::where(function ($query) {
            $query->where('visibility', '!=', 'hidden')
                ->orWhereNull('visibility');
        })->whereSlug($slug)->with('stores')->whereStatus('1')->first();
        $allStores = $category->stores();
        $categoryCuisine = $request->cuisine;
        if(isset($request->cuisine)){
            $allStores->whereHas('categories', function ($query) use ($categoryCuisine){
                $query->where('name', $categoryCuisine);
            });
        }
        if (isset($request->orderBy)) {
            if ($request->orderBy == 'popularity') {
                $allStores = $allStores->with('clicks')->paginate($request->input('perPage'));
                $allStores = sortByDistance($data, $allStores);
            } else if ($request->orderBy == 'cashback-amount') {
                $allStores = $allStores->whereHas('cashbacks', function ($query) {
                    $query->where('type', 'fixed')->whereHas('currencyData', function ($query) {
                        $query->where('symbol', '£');
                    });
                })->get()->filter(function ($store) {
                    $cashback = $store->getCashback();
                    return (strpos($cashback, '£') !== false);
                })->sortByDesc(function ($store) {
                    return $store->getCashback();
                })->paginate($request->input('perPage'));
                $allStores = sortByDistance($data, $allStores);
            } else if ($request->orderBy == 'cashback-percentage') {
                $allStores = $allStores->whereHas('cashbacks', function ($query) {
                    $query->where('type', 'percentage');
                })->get()->filter(function ($store) {
                    $cashback = $store->getCashback();
                    return (strpos($cashback, '%') !== false);
                })->sortByDesc(function ($store) {
                    return $store->getCashback();
                })->paginate($request->input('perPage'));
                $allStores = sortByDistance($data, $allStores);
            } else {
                $orderByArr = explode("-", $request->orderBy);
                $allStores = $allStores->orderBy($orderByArr[0], $orderByArr[1])->paginate($request->input('perPage'));
                $allStores = sortByDistance($data, $allStores);
            }
        } else {
            $allStores = $allStores->latest()->get();
            $allStores = sortByDistance($data, $allStores, true);
            $allStores = $allStores->paginate($request->input('perPage'));
        }
        $viewType = isset($request->viewType) ? $request->viewType : 'grid-view';
        return [
            'view' => view('frontend.categories.view', compact('allStores', 'slug', 'viewType'))->render(),
            'stores' => $allStores
        ];
    }
}
