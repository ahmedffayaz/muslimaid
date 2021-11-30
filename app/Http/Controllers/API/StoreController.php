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
    public function index()
    {
        return StoreResource::collection(Store::latest()->get());

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

    public function vouchers(){

        return StoreResource::collection(Store::has('vouchers')->latest()->get());
    }

}
