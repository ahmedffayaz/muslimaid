<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserCashback;
use App\Models\User;
use App\Models\ExitClick;
use App\Models\CashbackStatusChange;
use App\Models\Store;
use App\Models\Network;
use App\Models\SiteSetting;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route = 'index';
        $stores = Store::latest()->get();
        $networks = Network::latest()->get();
        $clicks = ExitClick::latest()->get();
        $statuses = \DB::table('cashback_statuses')->latest()->get();
        $users = User::role('user')->latest()->get();
        $earning = UserCashback::all()->sum('amount');
        $coms = UserCashback::latest()->paginate(20);
        
        return view('admin-dashboard.commissions.index', compact('coms','earning','stores','networks','clicks','statuses','users','route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clicks = ExitClick::latest()->get();
        $statuses = \DB::table('cashback_statuses')->latest()->get();
        return view('admin-dashboard.commissions.create', compact('clicks','statuses'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $cashback_percent = SiteSetting::where('type','cashback_percentage')->first()->value;
        
        try {

            $click = ExitClick::findOrFail($request->exit_click_id);
            $commission = UserCashback::create([
                'store_id' => $click->store_id,
                'user_id'  => $click->user_id ?? 0,
                'exit_click_id' => $click->id,
                'amount' => round(($request->network_commission/100) * $cashback_percent,3),
                'network_commission' => round($request->network_commission,3),
                'order_value' => round($request->order_value,3),
                'status' => $request->status,
                'event_date'=> $click->created_at,
                'click_date'=> $click->created_at,
                
            ]); 


            $change_status = CashbackStatusChange::create([
                'user_cashback_id'=>$commission->id,
                'cashback_status_id'=>$commission->status
    
            ]);
            
            flash()->success('New Cashback Added');
            return redirect()->route('admin.commissions.index');

        } catch (\Throwable $th) {

            flash()->error('Error While saving new cashback');
            return redirect()->route('admin.commissions.index');
        }
       
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
    public function edit(UserCashback $commission)
    {
        $clicks = ExitClick::latest()->get();
        $statuses = \DB::table('cashback_statuses')->latest()->get();
        return view('admin-dashboard.commissions.edit', compact('commission','clicks','statuses'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserCashback $commission)
    {
        try {
            // $click = ExitClick::findOrFail($request->exit_click_id);
            
            //track status change of the cashback
            if($commission->status!=$request->status){
                $change_status = CashbackStatusChange::create([
                    'user_cashback_id'=>$commission->id,
                    'cashback_status_id'=>$request->status

                ]);
            }

            $commission->update([
                'amount' => round($request->amount,3),
                'network_commission' => round($request->network_commission,3),
                'order_value' => round($request->order_value,3),
                'status' => $request->status,
                
            ]); 
            flash()->success('Cashback updated');
            return redirect()->route('admin.commissions.index');

        } catch (\Throwable $th) {
            flash()->error($th->getMessage());
            // flash()->error('Error while updating the cashback');
            return redirect()->route('admin.commissions.index');

        }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserCashback $commission)
    {
        $commission->delete();
        flash()->success('Cashback deleted successfully');
        return redirect()->back();
    }
    function fetch(Request $request)
    {
     if($request->ajax())
     {
         $route = 'index';
        $coms = UserCashback::latest()->paginate(20);
         return view('admin-dashboard.commissions.index_data', compact('coms','route'))->render();
     }
    }
    public function exportCsv(Request $request)
    {
        try {
            
            $table = UserCashback::latest()->get();
            $filename = "cashbacks.csv";
            $handle = fopen($filename, 'w+');
            fputcsv($handle, array('User', 'User email', 'Store','Amount', 'Exit Click Id', 'Event Time', 'Status'));

            foreach($table as $row) {

                $fname = $row->user->first_name ?? '';
                $lname = $row->user->last_name ?? '';
                fputcsv($handle, array($fname.' '.$lname, $row->user->email ?? '', $row->amount, $row->store->name,
                                        $row->exit_click_id,  $row->event_date, $row->status  ));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

            return \Response::download($filename, 'cashbacks.csv', $headers);
        }catch (\Throwable $th) {
            flash()->error('Error while exporting cashbacks');
            return redirect()->route('admin.commissions.index');
        }

    }
    public function createMultiple()
    {
        $clicks = ExitClick::latest()->get();
        $statuses = \DB::table('cashback_statuses')->latest()->get();
        return view('admin-dashboard.commissions.create_multiple', compact('clicks','statuses'));

    }

    public function storeMultiple(Request $request)
    {
        
        try {
            foreach ($request->exit_click_id as $key => $value) {
                $click = ExitClick::findOrFail($value);
            $commission = UserCashback::create([
                'store_id' => $click->store_id,
                'user_id'  => $click->user_id ?? 0,
                'exit_click_id' => $click->id,
                'amount' => round($request->amount[$key],3),
                'network_commission' => round($request->network_commission[$key],3),
                'order_value' => round($request->order_value[$key],3),
                'status' => $request->status[$key],
                'event_date'=> \Carbon\Carbon::parse($request->event_date[$key])->format('Y-m-d H:i:s'),
                'click_date'=> $click->created_at,
                
            ]); 

            $change_status = CashbackStatusChange::create([
                'user_cashback_id'=>$commission->id,
                'cashback_status_id'=>$commission->status
    
            ]);
            }
            
            flash()->success('New Cashback Added');
            return redirect()->route('admin.commissions.index');

        } catch (\Throwable $th) {

            flash()->error('Error While saving new cashbacks');
            return redirect()->route('admin.commissions.index');
        }
       
    }
    public function searchCommissions(Request $request, UserCashback $coms)
    {
        // dd($request->all());
        $coms = $coms->newQuery();

        // Search by network.
        // if ($request->input('network_id')) {
        //     $coms->where('network_id', $request->input('network_id'));
        // }

        // Search by store.
        if ($request->input('store_id')) {
            $coms->where('store_id',$request->input('store_id'));
           
        }
        // Search by user.
        if ($request->input('user_id')) {
            $coms->where('user_id',$request->input('user_id'));
           
        }
        // Search by cick.
        if ($request->input('click_id')) {
            $coms->where('exit_click_id',$request->input('click_id'));
           
        }

        // Search by status.
        if ($request->input('status_id')!=-1) {
            $coms->where('status', $request->input('status_id'));
        }
        
        $coms = $coms->latest()->paginate(20);
        $route='search';
        return view('admin-dashboard.commissions.index_data', compact('coms','route'))->render();
    }

    public function statusHistory(UserCashback $commission){

        $history = $commission->statusHistory;
        return view('admin-dashboard.commissions.history', compact('history'))->render();

            
    }
}
