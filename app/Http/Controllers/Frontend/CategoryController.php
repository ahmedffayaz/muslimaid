<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
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
        })->with(['childs' => function ($query) {
            $query->where('status', '1')->orderBy('sort', 'asc')
            ->withCount(['stores' => function ($query) {
                $query->where('status', 'active');
            }]);
        }])->withCount(['stores' => function ($query) {
            $query->where('status', 'active');
        }])->where('status', '1')->where('parent_id', '0')->orderBy('sort', 'desc')->orderBy('name', 'asc')->get();

        return view('frontend.pages.single-page', compact('page', 'categories'));
    }

    public function show(Request $request, $slug)
    {
        $ip =  request()->ip(); //Dynamic IP address get
        $data = Location::get($ip);
        $category = Category::where(function ($query) {
            $query->where('visibility', '!=', 'hidden')
                ->orWhereNull('visibility');
        })->whereSlug($slug)->with('childs', function ($query) {
            $query->whereStatus(1);
        })->whereStatus('1')->first();

        if (empty($category)) abort(404);

        if ($request->ajax()) {
            $stores = Store::select('id', 'name', 'slug', 'status', 'latitude', 'longitude', 'created_at')->when($request->has('id'), function ($query) use ($request) {
                $query->whereHas('categories', function ($query) use ($request) {
                    $query->whereIn('category_id', $request->id);
                });
            })->when($request->has('cuisines'), function ($query) use ($category, $request) {
                if ($request->cuisines == 'all') {

                } else {
                    $query->whereHas('categories', function ($query) use ($category, $request) {
                        $query->whereIn('name', $request->cuisines);
                    });
                }
            })->when(optional($category)->id, function ($query) use ($category) {
                $query->whereHas('categories', function ($query) use ($category) {
                    $query->where('category_id', $category->id);
                });
            })->with('logo', 'storeAddress')->whereStatus('active')->get();

            $stores = $this->categoriesView($request, $slug);

            if ($request->cuisines) {
                $viewType = 'grid-view';
                $allStores = $stores['stores'];
                $storesCount = $stores['storesCount'];
                return response()->json([
                    'view' => view('frontend.categories.view', compact('allStores', 'slug', 'viewType'))->render(),
                    'stores' => $allStores,
                    'storesCount' => $storesCount
                ]);
            }

            $stores = sortByDistance($data, $stores['stores']);
            $stores = $stores->sortBy('distance')->values()->paginate(25);

            return view('frontend.stores.stores', compact('stores'));
        }

        $stores = $category->stores()->where('status', 'active')->with('logo', 'storeAddress')->get();
        $stores = sortByDistance($data, $stores);
        $stores = $stores->sortBy('distance')->values()->paginate(25);
        $stores->appends(['orderBy' => $request->orderBy]);
        $totalCount = $category->stores()->where('status', 'active')->count();
        $cuisine = isset($request->cuisine) ?  $request->cuisine : '';
        return view('frontend.categories.show', compact('category', 'stores', 'slug', 'cuisine','totalCount'));
    }

    public function categoriesView(Request $request, $slug)
    {
        $storesCount = NULL;
        $ip =  request()->ip(); //Dynamic IP address get
        $data = Location::get($ip);
        $category = Category::where(function ($query) {
            $query->where('visibility', '!=', 'hidden')
                ->orWhereNull('visibility');
        })->whereSlug($slug)->with(['stores' => function ($store) {
            $store->select('stores.id', 'stores.name', 'stores.slug', 'stores.latitude', 'stores.longitude', 'stores.status', 'stores.created_at');
        }])->whereStatus('1')->first();
        $allStores = $category->stores()->whereStatus('active')->withCount('cashbacks');
        $categoryCuisine = $request->input('cuisine');
        if (!empty($categoryCuisine)) {
            $allStores =  $allStores->whereHas('categories', function ($query) use ($categoryCuisine) {
                $query->where('name', $categoryCuisine);
            });
        }

        if (!empty($request->input('cuisines'))) {
            if($request->cuisines[0] != "all"){
                $allStores = $allStores->whereHas('categories', function ($query) use ($request) {
                    $query->whereIn('name', $request->input('cuisines'));
                });
            }
        }

        if (isset($request->orderBy)) {
            if ($request->orderBy == 'popularity') {
                $allStores = $allStores->with('clicks')->paginate($request->input('perPage'));
                $allStores = sortByDistance($data, $allStores);
            } else if ($request->orderBy == 'cashback-amount') {
                $cashbackAmountStores = $allStores->whereHas('cashback', function ($cashback) {
                    $cashback->where('type', 'fixed')->whereHas('currencyData', function ($query) {
                        $query->where('symbol', '£');
                    });
                })->get()->filter(function ($store) {
                    $cashback = $store->getCashback();
                    return (strpos($cashback, '£') !== false);
                })->sortByDesc(function ($store) {
                    return $store->getCashback();
                })->paginate($request->input('perPage'));
                $cashbackPercentageStores = $category->stores()->where('status', 'active')->get();
                $allStores = $cashbackAmountStores->concat($cashbackPercentageStores)->paginate($request->input('perPage'));
                $allStores = sortByDistance($data, $allStores);
            } else if ($request->orderBy == 'cashback-percentage') {
                $allStores = $allStores->whereHas('cashbacks', function ($query) {
                    $query->where('type', 'percentage');
                })->get()->filter(function ($store) {
                    $cashback = $store->getCashback();
                    $percentage = (int) filter_var($cashback, FILTER_SANITIZE_NUMBER_INT);
                    $store->cashbackPercentage = $percentage;
                    return $percentage;
                })->sortByDesc(function ($store) {
                    return $store->cashbackPercentage;
                })->paginate($request->input('perPage'));
                $allStores = sortByDistance($data, $allStores);
            } else if ($request->orderBy == 'cashback-amount-asc') {
                $cashbackAmountStoresAsc = $allStores->whereHas('cashback', function ($cashback) {
                    $cashback->where('type', 'fixed');
                })->get();
                $allStores = $cashbackAmountStoresAsc->sortBy('cashback_integer')->paginate($request->input('perPage'));
            } else if ($request->orderBy == 'cashback-amount-desc') {
                $cashbackAmountStoresDesc = $allStores->whereHas('cashback', function ($cashback) {
                    $cashback->where('type', 'fixed');
                })->get();
                $allStores = $cashbackAmountStoresDesc->sortByDesc('cashback_integer')->paginate($request->input('perPage'));
            } else if ($request->orderBy == 'cashback-percentage-asc') {
                $cashbackPercentageStoresAsc = $allStores->whereHas('cashback', function ($cashback) {
                    $cashback->where('type', 'percentage');
                })->get();
                $allStores = $cashbackPercentageStoresAsc->sortBy('cashback_integer')->paginate($request->input('perPage'));
            } else if ($request->orderBy == 'cashback-percentage-desc') {
                $cashbackPercentageStoresDesc = $allStores->whereHas('cashback', function ($cashback) {
                    $cashback->where('type', 'percentage');
                })->get();
                $allStores = $cashbackPercentageStoresDesc->sortByDesc('cashback_integer')->paginate($request->input('perPage'));
            } else if ($request->orderBy == 'name-asc') {
                $allStores = $allStores->orderBy('name', 'asc')->paginate($request->input('perPage'));
            } else if ($request->orderBy == 'name-desc') {
                $allStores = $allStores->orderBy('name', 'desc')->paginate($request->input('perPage'));
            } else if ($request->orderBy == 'id-desc') {
                $allStores = $allStores->orderBy('id', 'desc')->paginate($request->input('perPage'));
            } else {
                $orderByArr = explode("-", $request->orderBy);
                $allStores = $allStores->orderBy($orderByArr[0], $orderByArr[1])->paginate($request->input('perPage'));
                $allStores = sortByDistance($data, $allStores);
            }
        } else if ($request->input('cuisines')) {
            $allStores = $allStores->get();
            $storesCount = $allStores->count();
            $allStores = sortByDistance($data, $allStores, true);
            $allStores = !empty($request->input('perPage')) ? $allStores->paginate($request->input('perPage')) : $allStores->paginate(25);
        } else {
            $allStores = $allStores->latest()->get();
            $allStores = sortByDistance($data, $allStores, true);
            $allStores = $allStores->paginate($request->input('perPage'));
        }
        $viewType = isset($request->viewType) ? $request->viewType : 'grid-view';
        return [
            'view' => view('frontend.categories.view', compact('allStores', 'slug', 'viewType'))->render(),
            'stores' => $allStores,
            'storesCount' => $storesCount
        ];
    }

    public function  loadMoreButton(Request $request)
    {
        $ip =  request()->ip(); //Dynamic IP address get
        $data = Location::get($ip);

        $perPage = $request->input('perpage');
        $offset = $request->input('offset');
        $category = Category::where(function ($query) {
            $query->where('visibility', '!=', 'hidden')
                ->orWhereNull('visibility');
        })->whereSlug("cashblack-to-your-door")->whereStatus('1')->first();

        if(! $request->has('cuisines')){
            $allStores = $category->stores()->where('status', 'active')->distinct()->get();
            $allStores = sortByDistance($data, $allStores, true);
            $allStores = $allStores->skip($offset)->take($perPage);
            $html = view('frontend.categories.load-button-stores', ['allStores' => $allStores])->render();
            return response()->json([
                'html' => $html,
                'nextOffset' => $offset + $perPage,
                'stores' => $allStores
            ]);
        } else {
            $allStores = $category->stores()->where('status', 'active');
            if($request->cuisines[0] != "all"){
                $allStores = $allStores->whereHas('categories', function ($query) use ($request) {
                    $query->whereIn('name', $request->input('cuisines'));
                });
            }
            $allStores = $allStores->get();
            $storesCount = $allStores->count();
            $allStores = sortByDistance($data, $allStores, true);
            $allStores = $allStores->skip($offset)->take($perPage);
            $html = view('frontend.categories.load-button-stores', ['allStores' => $allStores])->render();
            return response()->json([
                'html' => $html,
                'nextOffset' => $offset + $perPage,
                'stores' => $allStores,
                'totalCount' => $storesCount
            ]);
        }
    }
}
