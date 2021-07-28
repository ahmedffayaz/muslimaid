<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\PaymentInfo;
use App\Models\Cashout;
use App\Models\CashbackStatusChange;


class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stores = Store::paginate('10');
        $term = null;
        return view('client-dashboard.withdraw',compact('stores','term'));
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
    public function statement(){
        $user =\Auth::user();
        $cashouts = $user->cashouts();
        return view('client-dashboard.statement',compact('cashouts'));
    }
    public function paymentDetails(){
        return view('client-dashboard.payment_details');
    }
    public function paymentSave(Request $request){

        $payment = PaymentInfo::updateOrCreate([
            'user_id'   => \Auth::user()->id,
            'payment_method'   => $request->payment_method,
        ],$request->all());

        flash()->success('Payment method updated successfully');
        return redirect()->back();

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
