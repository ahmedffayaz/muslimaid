<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Network;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stores = Store::latest()->get();
        return view('admin-dashboard.stores.index', compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $networks = Network::all();
        $categories = Category::all();
        return view('admin-dashboard.stores.create', compact('networks','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
   
        $validator = Validator::make($request->all(), [
            'store_name' => 'required|max:255',
            'network_id' => 'required',
            'category_id' => 'required',
            'tracking_url' => 'required',
            'store_url' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.stores.index')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $store = Store::create([
                'name'         => $request->input('store_name'),
                'network_id'   => $request->input('network_id'),
                'tracking_url' => $request->input('tracking_url'),
                'store_url'    => $request->input('store_url'),
            ]);

            foreach ($request->input('category_id') as $category) {
                DB::table('category_store')->insert([
                    'store_id' => $store->id,
                    'category_id' => $category
                ]);
            }

            

            return redirect()->route('admin.stores.index');
           
            
        } catch (Exception $exception) {
            return redirect()->route('admin.stores.index');

            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        $networks = Network::all();
        $categories = Category::all();
        return view('admin-dashboard.stores.edit', compact('store', 'networks', 'categories'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        $store->update([
            'name'         => $request->input('store_name'),
            'network_id'   => $request->input('network_id'),
            'tracking_url' => $request->input('tracking_url'),
            'store_url'    => $request->input('store_url'),
        ]);

        DB::table('category_store')->where('store_id', $store->id)->delete();
        
        foreach ($request->input('category_id') as $category) {
            DB::table('category_store')->insert([
                'store_id' => $store->id,
                'category_id' => $category
            ]);
        }
        return redirect()->route('admin.stores.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
