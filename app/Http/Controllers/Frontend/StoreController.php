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
        $count = count($store->cashbacks);
        return view('frontend.stores.show',compact('store','count'));
        // return view('frontend.stores.show',compact('store'));
    }

    public function storeLocation(Request $request)
    {   
        if ($request->ajax()) {
            if($request->has('id'))
            {
            $locations = Store::when($request->has('id'), function($query) use ($request) {
                $query->whereHas('categories', function ($query) use ($request) {
                    $query->whereIn('category_id', $request->id);
                });
            })->with('logo', 'storeAddress')->paginate(10);
        }else{
            $locations = Store::with('logo', 'storeAddress')->paginate(10);
            
        }
            return view('frontend.stores.stores',compact('locations'));
        }

       // $locations = Store::with('logo','storeAddress')->paginate(10);
        //$categories = Category::with(['stores.storeAddress'])->where('parent_id', '=', 0)->orderBy('name', 'ASC')->get();
        $mainCategory = Category::whereName('Cashback to door')->first();

        // dd($mainCategory->id);
        $locations = Store::when($mainCategory->id, function($query) use ($mainCategory) {
            $query->whereHas('categories', function ($query) use ($mainCategory) {
                $query->where('category_id', $mainCategory->id);
            });
        })->with('logo', 'storeAddress')->paginate(10);
        
        $categories = Category::with(['stores.storeAddress'])->whereParentId($mainCategory['id'])->orderBy('name', 'ASC')->get();
       
        $array = array();
        foreach($categories as $category){
             foreach($category->stores as $store){
                $location_array['des'][] =$store->description;
                foreach ($store->storeAddress as $address) {
                    $location_array['lat'][] =$address->latitude;
                    $location_array['long'][] =$address->longitude;
                }
             }
        }
        return view('frontend.stores.location', compact('locations','categories', 'array'));
    }
}