<?php

namespace App\Http\Controllers\Client;

use App\Models\Store;
use App\Models\Cashout;
use App\Models\Charity;
use App\Models\CharityType;
use App\Models\PaymentInfo;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CashbackStatusChange;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $charities=Charity::latest()->get();
        $stores = Store::paginate('10');
        $usercashback=UserCashback::where('status', '3')->get();
        $term = null;
        return view('client-dashboard.withdraw',compact('stores','term','charities','usercashback'));
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
    public function paymentDetails(){
        return view('client-dashboard.payment_details');
    }
    public function paymentSave(Request $request){

        $payment = PaymentInfo::updateOrCreate([
            'user_id'   => Auth::user()->id,
            'payment_method'   => $request->payment_method,
        ],$request->all());

        flash()->success('Payment method updated successfully');
        return redirect()->back();

    }

    public function cashout(Request $request){
        if(array_key_exists('min_cashout_amount',SiteSetting()->toArray()))
         $min = SiteSetting()['min_cashout_amount'];
        else $min = 1; 
        $user = Auth::user(); 
        $method = $user->paymentInfo()->where('payment_method',$request->payment_method)->first();
        if(!$method || !SiteSetting()['payment_method_charity'])
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
            'user_id' => $user->id,
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
    public function CharityCashout(Request $request,Cashout $cashout ){
        if(array_key_exists('min_cashout_amount',SiteSetting()->toArray()))
         $min = SiteSetting()['min_cashout_amount'];
        else $min = 1; 
        $user = Auth::user(); 
        $cashout_status = $user->cashouts()->where('status','=','pending')->first(); 
        $balance_old= $user->availableBalance(); 
        $balance=($balance_old - $request->amount);
        if($balance_old < $min){
            flash()->error('You have insufficient balance for withdrawl.');
            return redirect()->back();
        }
        if($cashout_status == 'pending'){
            flash()->error('You have already  withdraw request.');
            return redirect()->back();
        }
        $validator = Validator::make($request->all(), [
            'charity_types_id' =>'required',
            'amount' => 'required',
            
           
        ]);
        if ($validator->fails()) { 
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $cashout = Cashout::create([
            'user_id' => $user->id,
            'charity_types_id'=>$request->charity_types_id,
            'amount'=>$request->amount, 
            'new_cashout'=>'1',
            'payment_method'=>$request->payment_method, 
            'status'=>'processing donation']);
           
        $cashback = $user->cashbacks()->where('id', $request->id)->first();
        $cashback->update(['status' => 5,'cashout_id'=>$cashout->id]);
            $cashback->statusHistory()->create([
                'cashback_status_id'=>$cashback->status
            ]);
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
