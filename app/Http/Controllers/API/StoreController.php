<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\Store;
use App\Models\Slider;
use App\Http\Resources\StoreResource;
use App\Http\Resources\SliderResource;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $stores = Store::select('stores.*');
        if($request->get('search')){
            $stores = $stores->where('name','like','%'.$request->get('search').'%');
        }
        if($request->get('name_sort')){
            $order = $request->get('name_sort') == 'desc' ? 'desc' :'asc';
            $stores = $stores->orderBy('name',$order);
        }else{
            $stores = $stores->orderBy('id','DESC');
        }
        $limit = $request->has('per_page') ? $request->get('per_page') : 10;
        $stores = $stores->paginate($limit);
        $stores->appends(['search' => $request->get('search'), 'per_page'=>$limit,'name_sort' => $request->get('name_sort')]);

        return StoreResource::collection($stores);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try{
            $store = Store::where('slug',$slug)->firstOrFail();
            
            
        } catch (ModelNotFoundException $ex) { // Store not found

            $arr = array("status" => 404, "message" => 'Store not found', "data" => array());

            return \Response::json($arr);
        } catch (Exception $ex) { // Anything that went wrong
            $arr = array("status" => 500, "message" => 'Something went wrong!', "data" => array());

            return \Response::json($arr);
        }
        return new StoreResource($store);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
    public function featuredCashback(){
        return StoreResource::collection(Store::where('feature_homepage',1)->latest()->get());

    }
    public function slider(){
        return SliderResource::collection(Slider::where('name','Home')->first()->slides);
    }

    public function vouchers(Request $request)
    {
        $stores = Store::has('vouchers')->select('stores.*');
        if($request->get('search')){
            $stores = $stores->where('name','like','%'.$request->get('search').'%');
        }
        if($request->get('name_sort')){
            $order = $request->get('name_sort') == 'desc' ? 'desc' :'asc';
            $stores = $stores->orderBy('name',$order);
        }else{
            $stores = $stores->orderBy('id','DESC');
        }
        $limit = $request->has('per_page') ? $request->get('per_page') : 10;
        $stores = $stores->paginate($limit);
        $stores->appends(
                        ['search'   => $request->get('search'), 
                        'per_page'  => $limit, 
                        'name_sort' => $request->get('name_sort')
                        ]);
        return StoreResource::collection($stores);
    }

}
