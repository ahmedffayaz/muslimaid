<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Models\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index($letter = null)
    {
        $page = Page::where('slug', 'stores')->whereType('system')->first();
        if (empty($page)) abort(404);

        if (!empty($letter)) {
            $stores = Store::where('name', 'like', $letter . '%')->get();
            $allStores = Store::whereStatus('active')->latest()->paginate(12);
            // return view('frontend.stores.show-by-letter', compact('stores', 'letter','allStores'));
            return view('frontend.pages.single-page', compact('page', 'stores', 'letter', 'allStores'));
        }

        $groups = Store::latest()->get()->sortBy('name')->groupBy(function ($store) {
            return strtoupper(substr($store->name, 0, 1));
        });

        return view('frontend.pages.single-page', compact('page', 'groups'));
    }

    public function show($slug)
    {
        $store = Store::where('slug', $slug)->first();
        if (empty($store)) abort(404);

        $count = $store->cashbacks ? count($store->cashbacks) : 0;
        return view('frontend.stores.show', compact('store', 'count'));
    }
    public function storesView(Request $request)
    {
        $perPage = $request->input('perPage');
        if (isset($request->orderBy)) {
            if ($request->orderBy == 'popularity') {
                $allStores = Store::whereStatus('active')->with('clicks')->paginate($perPage);
            } else if ($request->orderBy == 'cashback-amount') {
                $allStores = Store::whereStatus('active')->whereHas('cashbacks', function ($query) {
                    $query->where('type', 'fixed')
                        ->whereHas('currencyData', function ($query) {
                            $query->where('symbol', '£');
                        });
                })->get()->filter(function ($store) {
                    $cashback = $store->getCashback();
                    return (strpos($cashback, '£') !== false);
                })->sortByDesc(function ($store) {
                    $cashback = $store->getCashback();
                    return (float) substr($cashback, 1);
                })->paginate($perPage);
            } else if ($request->orderBy == 'cashback-percentage') {
                $allStores = Store::whereStatus('active')->whereHas('cashbacks', function ($query) {
                    $query->where('type', 'percentage');
                })->get()->filter(function ($store) {
                    $cashback = $store->getCashback();
                    return (strpos($cashback, '%') !== false);
                })->sortByDesc(function ($store) {
                    return $store->getCashback();
                })->paginate($perPage);
            } else {
                $orderByArr = explode("-", $request->orderBy);
                $allStores = Store::whereStatus('active')->orderBy($orderByArr[0], $orderByArr[1])->paginate($perPage);
                $allStores->appends(['orderBy' => $request->orderBy]);
            }
        } else {

            $allStores = Store::whereStatus('active')->latest()->paginate($perPage);
            $allStores->appends(['orderBy' => $request->orderBy]);
        }

        return view('frontend.stores.stores-view', compact('allStores', 'perPage'))->render();
    }
}
