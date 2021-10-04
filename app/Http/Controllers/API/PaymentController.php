<?php

namespace App\Http\Controllers\APi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\PaymentInfo;
use App\Models\Cashout;
use App\Traits\ApiResponser;
use App\Models\CashbackStatusChange;


class PaymentController extends Controller
{
    use ApiResponser;

    public function  paymentMethods(){
        $user =\Auth::user();
        $payment_meyhods=[];
        if($user->bankInfo || $user->paypalInfo){
            if($user->bankInfo){
                $payment_meyhods['bank']=$user->bankInfo->only('account_name','account_number','bank_title','bank_sort_code','bic');
            }
            if($user->paypalInfo){
                $payment_meyhods['paypal']=$user->paypalInfo->only('paypal_email');
            }
            return  $payment_meyhods;
        
        }
        return $this->error('no payment methods available',404);
    }
    
    public function cashouts(){
        $user =\Auth::user();
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

    public function cashout(Request $request){

        if(array_key_exists('min_cashout_amount',SiteSetting()->toArray()))
         $min = SiteSetting()['min_cashout_amount'];
        else $min = 1; 
        $user = \Auth::user();
        $method = $user->paymentInfo()->where('payment_method',$request->payment_method)->first();
        if(!$method)
        {
            flash()->error('Payment method not found, please add your payment method information');
            return redirect()->back();
        }
        $balance = $user->availableBalance();
        $cashbacks = $user->balance;
        if($balance <$min){
            flash()->error('You have insufficient balance for withdrawl.');
            return redirect()->back();
        }
        $cashout = Cashout::create([
            'user_id'=>$user->id,
            'amount'=>$balance,
            'cashout_type' =>$method->payment_method,
            'paypal_email'=>$method->paypal_email,
            'address'=>$method->address,
            'city'=>$method->city,
            'postcode'=>$method->postcode,
            'country'=>$method->country,
            'account_name'=>$method->account_name,
            'bank_title'=>$method->bank_title,
            'account_number'=>$method->account_number,
            'bank_sort_code'=>$method->bank_sort_code,
            'new_cashout'=>'1',
            'bic'=>$method->bic,
            'payment_method'=>$method->payment_method, 
            'status'=>'pending']);

            foreach($cashbacks as $cashback){

                $cashback->update(['status'=>5,'cashout_id'=>$cashout->id]);
                $change_status = CashbackStatusChange::create([
                    'user_cashback_id'=>$cashback->id,
                    'cashback_status_id'=>$cashback->status
                ]);
            }

            if($user->bonus && $user->bonus->status == 'unpaid'){
                $user->bonus->update([
                    'status'=>'paid',
                    'cashout_id'=> $cashout->id
                    ]);
            }

            flash()->success("We're processing your withdrawal. Please allow 4 working days for ".$balance." to reach your ".$request->payment_method." account.");
            return redirect()->back();

           
         
    }
}
