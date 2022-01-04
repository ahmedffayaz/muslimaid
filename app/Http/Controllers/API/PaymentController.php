<?php

namespace App\Http\Controllers\APi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\PaymentInfo;
use App\Models\Cashout;
use App\Traits\ApiResponser;
use App\Models\CashbackStatusChange;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    use ApiResponser;

    public function  paymentMethods(Request $request){

        $input = $request->all();
        $rules = array(
            'payment_method' => 'required|in:bank,paypal',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 429, "message" => $validator->errors()->first(), "data" => array());
            return \Response::json($arr);
        }
        $user =\Auth::user();

        if($request->payment_method == 'bank'){
            if($user->bankInfo){
                $bank = $user->bankInfo->only('account_name','account_number','bank_title','bank_sort_code','bic');
                $arr = array("status" => 200, "message" =>"User bank account details", "data" => $bank);

            }else{
                $arr = array("status" => 404, "message" => "User bank details not available", "data" => array());
            }
        }

        if($request->payment_method == 'paypal'){
            if($user->paypalInfo){
                $paypal = $user->paypalInfo->only('paypal_email');
                $arr = array("status" => 200, "message" =>"User paypal account details", "data" => $paypal);

            }else{
                $arr = array("status" => 404, "message" => "User paypal details not available", "data" => array());
            }
        }
        
        return \Response::json($arr);
    }
    
    public function cashouts(){
        $user     = \Auth::user();
        $cashouts = $user->cashouts;
        return $cashouts;

    }
  
    public function paymentSave(Request $request){

        $payment = PaymentInfo::updateOrCreate([
            'user_id'   => \Auth::user()->id,
            'payment_method'   => $request->payment_method,
        ],$request->all());

        return $this->success([
            'message'=>'Payment method saved'
        ]);

    }

    public function withdraw(Request $request){

        $input = $request->all();
        $rules = array(
            'payment_method' => 'required|in:bank,paypal',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 429, "message" => $validator->errors()->first(), "data" => array());
            return \Response::json($arr);
        }
        if(array_key_exists('min_cashout_amount',SiteSetting()->toArray()))
            $min = SiteSetting()['min_cashout_amount'];
        else $min = 1; 
        $user = \Auth::user();
        $method = $user->paymentInfo()->where('payment_method',$request->payment_method)->first();
        if(!$method)
        {
            $arr = array("status" => 400, "message" => "Payment method not found, please add your payment method information", "data" => array());
            return \Response::json($arr);
        }
        $balance = $user->availableBalance();
        $cashbacks = $user->balance;
        if($balance < $min){
            $arr = array("status" => 400, "message" => "You have insufficient balance for withdrawl.", "data" => array());
            return \Response::json($arr);
        }
        $cashout = Cashout::create([
            'user_id'       => $user->id,
            'amount'        => $balance,
            'cashout_type'  => $method->payment_method,
            'paypal_email'  => $method->paypal_email,
            'address'       => $method->address,
            'city'          => $method->city,
            'postcode'      => $method->postcode,
            'country'       => $method->country,
            'account_name'  => $method->account_name,
            'bank_title'    => $method->bank_title,
            'account_number'=> $method->account_number,
            'bank_sort_code'=> $method->bank_sort_code,
            'new_cashout'   => '1',
            'bic'           => $method->bic,
            'payment_method'=> $method->payment_method, 
            'status'        =>'pending'
        ]);

        foreach($cashbacks as $cashback){

            $cashback->update(['status'=>5,'cashout_id'=>$cashout->id]);
            $change_status = CashbackStatusChange::create([
                'user_cashback_id'   => $cashback->id,
                'cashback_status_id' => $cashback->status
            ]);
        }

        if($user->bonus && $user->bonus->status == 'unpaid'){
            $user->bonus->update([
                'status'     => 'paid',
                'cashout_id' => $cashout->id
                ]);
        }
        $arr = array("status" => 400, "message" => "We're processing your withdrawal. Please allow 4 working days for ".$balance." to reach your ".$request->payment_method." account.", "data" => ['balance'=>$balance, 'Method'=>$request->payment_method]);
        return \Response::json($arr);
    }
}
