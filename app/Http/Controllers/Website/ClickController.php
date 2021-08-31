<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExitClick;
use App\Models\Store;
use App\Models\SiteSetting;
use App\Models\RedeemedVoucher;

class ClickController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store = Store::findOrFail(74);
        $url = 'danishmemon.com';
        return view('frontend.pages.exit', compact('store','url'));

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
        $store = Store::where('id',$request->input('store_id'))->first();

        $custom_cashback_percentage = $store->custom_cashback_percentage;

            if($custom_cashback_percentage){
                $cashback_percent = $custom_cashback_percentage;
            }else{
                $cashback_percent = SiteSetting::where('type','cashback_percentage')->first()->value;
            }

            if(!$cashback_percent){
                $cashback_percent = 0;
            }
                
        $click = ExitClick::create([
            'store_id'=>$request->input('store_id'),
            'user_id'=>$request->input('user_id'),
            'status'=>'pending',
            'exit_url'=>'#',
            'current_cashback_percentage' => $cashback_percent

        ]);

        if($store->network->id == 1){
            $click->exit_url=$request->input('url').'?'.$store->network->click_ref.'='.$click->id;
        $click->update();
        }else if($store->network->id == 2){
            $click->exit_url=$request->input('url').'&'.$store->network->click_ref.'='.$click->id;
        $click->update();
        }
        
        $url = $click->exit_url;

        if($request->input('voucher_id')){
            $redeemed = RedeemedVoucher::create([
                'user_id'=>$request->input('user_id'),
                'voucher_id'=>$request->input('voucher_id'),
            ]);
        }
        return view('frontend.pages.exit', compact('store','url'));

        // return redirect($click->exit_url);
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
}
