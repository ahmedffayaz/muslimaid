<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExitClick;
use App\Models\UserCashback;
use App\Models\User;
use App\Models\CashbackStatusChange;
use App\Models\Store;
use App\Models\Network;

class ClickController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:view clicks');
        
    }
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
        $users = User::role('user')->latest()->get();
        $clicks = ExitClick::latest()->paginate(20);
        return view('admin-dashboard.clicks.index', compact('clicks', 'stores', 'networks', 'users', 'route'));
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
    function fetch(Request $request)
    {
        if ($request->ajax()) {
            $route = 'index';
            $clicks = ExitClick::latest()->paginate(20);

            return view('admin-dashboard.clicks.index_data', compact('clicks', 'route'))->render();
        }
    }
    public function exportCsv(Request $request)
    {
        try {

            $table = ExitClick::latest()->get();
            $filename = "clicks.csv";
            $handle = fopen($filename, 'w+');
            fputcsv($handle, array('User', 'User Email', 'Store', 'Exit Url', 'Time', 'Status'));

            foreach ($table as $row) {
                fputcsv($handle, array(
                    $row->user->first_name . ' ' . $row->user->last_name,
                    $row->user->email,
                    $row->store->name,
                    $row->exit_url,
                    $row->created_at,
                    $row->status ? 'active' : 'in-active'
                ));
            }

            fclose($handle);
            $headers = array('Content-Type' => 'text/csv',);

            return \Response::download($filename, 'clicks.csv', $headers);
        } catch (\Throwable $th) {

            flash()->error('Error while exporting exit clics');

            return redirect()->route('admin.clicks.index');
        }
    }
    public function searchClicks(Request $request, ExitClick $clicks)
    {
        // dd($request->all());
        $clicks = $clicks->newQuery();

        // Search by network.
        // if ($request->input('network_id')) {
        //     $clicks->where('network_id', $request->input('network_id'));
        // }

        // Search by store.
        if ($request->input('store_id')) {
            $clicks->where('store_id', $request->input('store_id'));
        }
        // Search by user.
        if ($request->input('user_id')) {
            $clicks->where('user_id', $request->input('user_id'));
        }


        $clicks = $clicks->latest()->paginate(20);
        $route = 'search';
        return view('admin-dashboard.clicks.index_data', compact('clicks', 'route'))->render();
    }
}
