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
       // $count = count($store->cashbacks);
        //return view('frontend.stores.show',compact('store','count'));
        return view('frontend.stores.show',compact('store'));
    }

    public function storeLocation()
    {
        $locations = Store::with('logo','storeAddress')->get();
        $categories = Category::with(['stores.storeAddress'])->where('parent_id', '=', 0)->orderBy('name', 'ASC')->get();
        foreach($categories as $category){
             foreach($category->stores as $store){
                foreach ($store->storeAddress as $address) {
                    $array['lat'][] =$address->latitude;
                    $array['long'][] =$address->longitude;
                }
             }
        }
        return view('frontend.stores.location', compact('locations','categories'));
    }
}