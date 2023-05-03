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
        return view('frontend.pages.single-page', compact('page', 'letter'));
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
        $allStores = Store::where('status', 'active');
        if (!empty($request->storesType)) {
            $allStores = $allStores->has('vouchers');
        }
        $letter = $request->input('letter');
        if (isset($request->orderBy) && !isset($letter)) {
            if ($request->orderBy == 'popularity') {
                $allStores = $allStores->withCount('clicks')->orderByDesc('clicks_count')->paginate($request->input('perPage'));
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
            } else if ($request->orderBy == 'cashback-percentage') {
                $allStores = $allStores->whereHas('cashbacks', function ($query) {
                    $query->where('type', 'percentage');
                })->get()->filter(function ($store) {
                    $cashback = $store->getCashback();
                    return (strpos($cashback, '%') !== false);
                })->sortByDesc(function ($store) {
                    return $store->getCashback();
                })->paginate($request->input('perPage'));
            } else {
                $orderByArr = explode('-', $request->orderBy);
                $allStores = $allStores->orderBy($orderByArr[0], $orderByArr[1])->paginate($request->input('perPage'));
            }
        } elseif (isset($letter)) {
            $allStores = $allStores->where('name', 'like', $letter . '%')->paginate($request->input('perPage'));
        } else {
            $allStores = $allStores->latest()->paginate($request->input('perPage'));
        }
        $viewType = isset($request->viewType) ? $request->viewType : 'grid-view';
        return view('frontend.stores.stores-view', compact('allStores', 'letter', 'viewType'))->render();
    }
}
