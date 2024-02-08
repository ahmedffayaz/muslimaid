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
        })->whereStatus('1')
        ->with(['stores' => function ($query) {
            $query->select('stores.id', 'stores.name', 'stores.slug', 'stores.status', 'stores.created_at')
            ->where('stores.status', 'active');
        }])->first();

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

        $stores = $category->stores;
        $stores = sortByDistance($data, $stores);
        $stores = $stores->sortBy('distance')->values()->paginate(25);
        $stores->appends(['orderBy' => $request->orderBy]);
        $totalCount = count($category->stores);
        $cuisine = isset($request->cuisine) ?  $request->cuisine : '';
        return view('frontend.categories.show', compact('category', 'stores', 'slug', 'cuisine','totalCount'));
    }

    public function categoriesView(Request $request, $slug)
    {
        $storesCount = NULL;
        $ip =  request()->ip(); //Dynamic IP address get
        $data = Location::get($ip);
        $allStores = Store::select('id', 'name', 'slug', 'status', 'latitude', 'longitude', 'created_at')
        ->when($request->has('cuisine') && $request->cuisine != null, function ($query) use ($request) {
            $query->whereHas('categories', function ($query) use ($request) {
                $query->where('name', $request->cuisine);
            });
        })->when($request->has('cuisines') && $request->cuisines != null, function ($query) use ($request) {
            if ($request->cuisines[0] != 'all') {
                $query->whereHas('categories', function ($query) use ($request) {
                    $query->whereIn('name', $request->cuisines);
                });
            }
        })->whereHas('categories', function ($query) use ($slug) {
            $query->where('slug', $slug)->where('status', 1);
        })->with('categories', function ($query) use ($slug) {
            $query->where('slug', $slug)->where('status', 1);
        });

        if (isset($request->orderBy)) {
            if ($request->orderBy == 'popularity' || $request->orderBy == 'cashback-amount'
                || $request->orderBy == 'cashback-amount-asc' || $request->orderBy == 'cashback-amount-desc'
                || $request->orderBy == 'cashback-percentage-asc' || $request->orderBy == 'cashback-percentage-desc'
                || $request->orderBy == 'cashback-percentage' || $request->orderBy == 'name-asc'
                || $request->orderBy == 'name-desc' || $request->orderBy == 'id-desc')
            {
                $allStores = $allStores->when($request->orderBy == 'popularity', function ($query) {
                    $query->with('clicks');
                })->when($request->orderBy == 'cashback-amount', function ($query) {
                    $query->whereHas('cashback', function ($query) {
                        $query->where('type', 'fixed')->whereHas('currencyData', function ($query) {
                            $query->where('symbol', '£');
                        });
                    });
                })->when($request->orderBy == 'cashback-amount-asc' || $request->orderBy == 'cashback-amount-desc', function ($query) {
                    $query->whereHas('cashback', function ($query) {
                        $query->where('type', 'fixed');
                    });
                })->when($request->orderBy == 'cashback-percentage-asc' || $request->orderBy == 'cashback-percentage-desc' || $request->orderBy == 'cashback-percentage', function ($query) {
                    $query->whereHas('cashback', function ($query) {
                        $query->where('type', 'percentage');
                    });
                })->when($request->orderBy == 'name-asc', function ($query) {
                    $query->orderBy('name', 'asc');
                })->when($request->orderBy == 'name-desc', function ($query) {
                    $query->orderBy('name', 'desc');
                })->when($request->orderBy == 'id-desc', function ($query) {
                    $query->orderBy('id', 'desc');
                });
            } else {
                $orderByArr = explode("-", $request->orderBy);
                $allStores = $allStores->orderBy($orderByArr[0], $orderByArr[1]);
                $allStores = sortByDistance($data, $allStores);
            }
        }

        $allStores = $allStores->orderBy('created_at', 'desc')->where('status', 'active')->withCount('cashbacks')->get();

        if ($request->orderBy == 'popularity' || $request->orderBy == 'cashback-amount' || $request->orderBy == 'cashback-percentage') {
            $allStores = sortByDistance($data, $allStores);
        } else if ($request->orderBy == 'cashback-amount-asc' || $request->orderBy == 'cashback-percentage-asc') {
            $allStores = $allStores->sortBy('cashback_integer');
        } else if ($request->orderBy == 'cashback-amount-desc' || $request->orderBy == 'cashback-percentage-desc') {
            $allStores = $allStores->sortByDesc('cashback_integer');
        } else if ($request->has('cuisines')) {
            $allStores = sortByDistance($data, $allStores, true);
        } else {
            $allStores = sortByDistance($data, $allStores, true);
        }

        $allStores = $allStores->paginate($request->input('perPage'));

        $storesCount = count($allStores);

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
