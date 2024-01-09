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
        $store = Store::where('slug', $slug)
        ->whereStatus('active')
        ->with(['cashbacks'])
        ->withCount('cashbacks')
        ->with(['vouchers' => function ($query) {
            $query->where('promotion_end_date', '>=', now())->where('status', 'active');
        }])->firstOrFail();

        $userRefId = !empty(auth()->user()) ? auth()->user() : User::whereId(1)->first();

        $count = $store->cashbacks_count;
        return view('frontend.stores.show', compact('store', 'count', 'userRefId'));
    }

    public function storesView(Request $request)
    {
        $allStores = Store::select('id', 'name', 'slug', 'status', 'latitude', 'longitude', 'created_at', 'deleted_at')->where('status', 'active')->with('cashback')->withCount('cashbacks');
        if (!empty($request->storesType)) {
            $allStores = $allStores->has('vouchers');
        }

        $letter = $request->input('letter');

        if (isset($request->orderBy)) {
            if ($request->orderBy == 'popularity') {
                $allStores = $allStores->withCount('clicks')->orderBy('clicks_count', 'desc');
            } else if ($request->orderBy == 'cashback-amount-asc') {
                $cashbackAmountStoreAsc = $allStores->whereHas('cashback', function ($cashback) {
                    $cashback->where('type', 'fixed');
                })->get();
                $allStores = $cashbackAmountStoreAsc->sortBy('cashback_integer');
            } else if ($request->orderBy == 'cashback-amount-desc') {
                $cashbackAmountStoreDesc = $allStores->whereHas('cashback', function ($cashback) {
                    $cashback->where('type', 'fixed');
                })->get();
                $allStores = $cashbackAmountStoreDesc->sortByDesc('cashback_integer');
            } else if ($request->orderBy == 'cashback-percentage-asc') {
                $cashbackPercentageStoreAsc = $allStores->whereHas('cashback', function ($cashback) {
                    $cashback->where('type', 'percentage');
                })->get();
                $allStores = $cashbackPercentageStoreAsc->sortBy('cashback_integer');
            } else if ($request->orderBy == 'cashback-percentage-desc') {
                $cashbackPercentageStoreDesc = $allStores->whereHas('cashback', function ($cashback) {
                    $cashback->where('type', 'percentage');
                })->get();
                $allStores = $cashbackPercentageStoreDesc->sortByDesc('cashback_integer');
            } else if ($request->orderBy == 'name-asc') {
                $allStores = $allStores->orderBy('name', 'asc');
            } else if ($request->orderBy == 'name-desc') {
                $allStores = $allStores->orderBy('name', 'desc');
            } else if ($request->orderBy == 'id-desc') {
                $allStores = $allStores->orderBy('id', 'desc');
            } else {
                $orderByArr = explode('-', $request->orderBy);
                $allStores = $allStores->orderBy($orderByArr[0], $orderByArr[1]);
            }
        } elseif (isset($letter)) {
            if($letter != '0-9') {
                $allStores = $allStores->where('name', 'like', $letter . '%')->orderBy('name', 'asc');
            } else {
                $paramLetter = '0-9';
                $allStores = $allStores->where('name', 'REGEXP', "^[{$paramLetter}]")->orderBy('name', 'asc');;
            }
        } else {
            $allStores = $allStores->orderBy('name', 'asc');
        }

        $allStores = $allStores->paginate($request->input('perPage'));
        $viewType = isset($request->viewType) ? $request->viewType : 'grid-view';
        return view('frontend.stores.stores-view', compact('allStores', 'letter', 'viewType'));
    }
}
