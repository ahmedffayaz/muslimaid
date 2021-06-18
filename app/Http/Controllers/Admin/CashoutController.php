<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cashout;
use App\Models\User;
use App\Models\CashbackStatus;


class CashoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cashouts = Cashout::latest()->paginate(10);
        return view('admin-dashboard.cashouts.index', compact('cashouts'));
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
    public function show(Cashout $cashout)
    {
        $users  = User::latest()->get();
        $statuses = CashbackStatus::all();
        return view('admin-dashboard.cashouts.show',compact('cashout','users','statuses'));
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
    public function update(Request $request, Cashout $cashout)
    {
        if($request->input('status') == 'pending'){
            $cashout->update(['status'=>'pending']);

            foreach($cashout->cashbacks as $cashback){
                $cashback->update(['status'=>5]);
            }
        }
        elseif($request->input('status') == 'paid'){
            $cashout->update(['status'=>'paid']);
            foreach($cashout->cashbacks as $cashback){
                $cashback->update(['status'=>4]);
            }

        }

        flash()->success('cashout updated');
        return redirect()->back();
        
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
