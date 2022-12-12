<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Voucher;
use App\Models\Category;

class StoreController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $store = Store::where('slug', $slug)->first();
        
        // $vouchers = Voucher::where('store_id', $store->id)->latest()->paginate(5);
        $count = count($store->cashbacks);
        return view('frontend.stores.show',compact('store','count'));
    }

    public function storeLocation()
    {
        $locations = Store::with('logo','storeAddress')->get();
        return view('frontend.stores.location',compact('locations'));
    }
   

}
