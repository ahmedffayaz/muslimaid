<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Store;
use App\Models\Slider;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $stores = Store::select('stores.*')->whereStatus('active');
        if($request->get('search')){
            $stores = $stores->where('name','like','%'.$request->get('search').'%');
        }
        if($request->get('name_sort')){
            $order = $request->get('name_sort') == 'descending' ? 'desc' :'asc';
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try{
            $store = Store::where('slug',$slug)->firstOrFail();
            return new StoreResource($store);
        } catch (ModelNotFoundException $ex) { // Store not found
            $arr = array("status" => 404, "message" => 'Store not found', "data" => array());
            return response()->json($arr);
        } catch (Exception $ex) { // Anything that went wrong
            $arr = array("status" => 500, "message" => 'Something went wrong!', "data" => array());
            return response()->json($arr);
        }
    }

    public function featuredCashback(){
        $stores = Store::whereHas('tags', function ($query) {
            $query->where('title', 'feature_homepage');
        })->latest()->get();
        return StoreResource::collection($stores);

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
            $order = $request->get('name_sort') == 'descending' ? 'desc' :'asc';
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
