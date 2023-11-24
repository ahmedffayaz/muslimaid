<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Models\Store;
use App\Http\Controllers\Controller;
use App\Models\User;
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
        $store = Store::where('slug', $slug)->whereStatus('active')->first();
        if (empty($store)) abort(404);

        $userRefId = !empty(auth()->user()) ? auth()->user() : User::whereId(1)->first();

        $count = $store->cashbacks ? count($store->cashbacks) : 0;
        return view('frontend.stores.show', compact('store', 'count', 'userRefId'));
    }
    public function storesView(Request $request)
    {
        $allStores = Store::where('status', 'active');
        if (!empty($request->storesType)) {
            $allStores = $allStores->has('vouchers');
        }
        $letter = $request->input('letter');
        if (isset($request->orderBy)) {
            if ($request->orderBy == 'popularity') {
                $allStores = $allStores->withCount('clicks')->orderByDesc('clicks_count')->paginate($request->input('perPage'));
            } else if ($request->orderBy == 'cashback-amount') {
                $cashbackAmountStores = $allStores->whereHas('cashbacks', function ($query) {
                    $query->where('type', 'fixed')->whereHas('currencyData', function ($query) {
                        $query->where('symbol', '£');
                    });
                })->get()->filter(function ($store) {
                    $cashback = $store->getCashback();
                    return (strpos($cashback, '£') !== false);
                })->sortByDesc(function ($store) {
                    return $store->getCashback();
                });
                $cashbackPercentageStores = Store::where('status', 'active')->get();
                $allStores = $cashbackAmountStores->concat($cashbackPercentageStores)->paginate($request->input('perPage'));
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
            } else {
                $orderByArr = explode('-', $request->orderBy);
                $allStores = $allStores->orderBy($orderByArr[0], $orderByArr[1])->paginate($request->input('perPage'));
            }
        } elseif (isset($letter)) {
            if($letter != '0-9') {
                $allStores = $allStores->where('name', 'like', $letter . '%')->orderBy('name', 'asc')->paginate($request->input('perPage'));
            } else {
                $paramLetter = '0-9';
                $allStores = $allStores->where('name', 'REGEXP', "^[{$paramLetter}]")->orderBy('name', 'asc')->paginate($request->input('perPage'));;
            }
        } else {
            $allStores = $allStores->orderBy('name', 'asc')->paginate($request->input('perPage'));
        }
        $viewType = isset($request->viewType) ? $request->viewType : 'grid-view';
        return view('frontend.stores.stores-view', compact('allStores', 'letter', 'viewType'));
    }
}
