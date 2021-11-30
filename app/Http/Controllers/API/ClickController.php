<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\ExitClick;
use App\Models\Store;
use App\Models\SiteSetting;
use App\Models\RedeemedVoucher;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\StoreResource;




class ClickController extends Controller
{
    use ApiResponser;

    public function track(Request $request){

        $validator = Validator::make($request->all(),[
            'store_slug' => ['required','exists:stores,slug'],
            'user_id' => ['required','exists:users,id'],
            
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }


        $store = Store::where('slug',$request->input('store_slug'))->first();

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
            'store_id'=>$store->id,
            'user_id'=>$request->input('user_id'),
            'status'=>'pending',
            'exit_url'=>'#',
            'current_cashback_percentage' => $cashback_percent

        ]);

        if($store->network->id == 1){
            $click->exit_url=$store->tracking_url.'?'.$store->network->click_ref.'='.$click->id;
        $click->update();
        }else if($store->network->id == 2){
            $click->exit_url=$store->tracking_url.'&'.$store->network->click_ref.'='.$click->id;
        $click->update();
        }
        
        $url = $click->exit_url;

        if($request->input('voucher_id')){
            $redeemed = RedeemedVoucher::create([
                'user_id'=>$request->input('user_id'),
                'voucher_id'=>$request->input('voucher_id'),
            ]);
        }
        return $this->success([
            'url'=>$url
        ], 'click tracked succesfully', 200);
    }
}
