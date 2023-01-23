<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Store;
use App\Models\Network;
use App\Models\ExitClick;
use App\Models\SiteSetting;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CashbackStatusChange;
use  Illuminate\Support\Facades\Response;

class CommissionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view cashback', ['only' => ['index']]);
        $this->middleware('permission:edit cashback', ['only' => ['edit', 'show', 'update']]);
        $this->middleware('permission:add cashback', ['only' => ['create', 'Store']]);
        $this->middleware('permission:delete cashback', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route = 'index';
        $networks = Network::latest()->get();
        $clicks = ExitClick::latest()->get();
        $statuses = DB::table('cashback_statuses')->latest()->get();
        $coms = UserCashback::latest()->paginate(20);

        return view('admin-dashboard.commissions.index', compact('coms', 'networks', 'clicks', 'statuses', 'route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clicks = ExitClick::latest()->get();
        $statuses = DB::table('cashback_statuses')->latest()->get();
        return view('admin-dashboard.commissions.create', compact('clicks', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $click = ExitClick::findOrFail($request->exit_click_id);
            $custom_cashback_percentage = $click->store->custom_cashback_percentage;

            if ($custom_cashback_percentage) {
                $cashback_percent = $custom_cashback_percentage;
            } else {
                $cashback_percent = SiteSetting::where('type', 'cashback_percentage')->first()->value;
            }

            $commission = UserCashback::create([
                'store_id' => $click->store_id,
                'user_id'  => $click->user_id ?? 0,
                'exit_click_id' => $click->id,
                'amount' => round(($request->network_commission / 100) * $cashback_percent, 3),
                'network_commission' => round($request->network_commission, 3),
                'order_value' => round($request->order_value, 3),
                'status' => $request->status,
                'event_date' => $click->created_at,
                'click_date' => $click->created_at,

            ]);

            $change_status = CashbackStatusChange::create([
                'user_cashback_id' => $commission->id,
                'cashback_status_id' => $commission->status

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
    public function show(UserCashback $commission)
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
        $statuses = DB::table('cashback_statuses')->latest()->get();
        return view('admin-dashboard.commissions.edit', compact('commission', 'clicks', 'statuses'))->render();
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
            //track status change of the cashback
            if ($commission->status != $request->status) {
                $change_status = CashbackStatusChange::create([
                    'user_cashback_id' => $commission->id,
                    'cashback_status_id' => $request->status

                ]);
            }

            $commission->update([
                'amount' => round($request->amount, 3),
                'network_commission' => round($request->network_commission, 3),
                'order_value' => round($request->order_value, 3),
                'status' => $request->status,

            ]);
            return array(
                'message' => 'Cashback updated',
                'updated' => 'success'
            );
        } catch (\Throwable $th) {
            return array(
                'message' => 'Something went wrong!',
                'updated' => 'error'
            );
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
        if ($request->ajax()) {
            $route = 'index';
            $coms = UserCashback::latest()->paginate(20);
            return view('admin-dashboard.commissions.index_data', compact('coms', 'route'))->render();
        }
    }
    public function exportCsv(Request $request)
    {
        try {

            $table = UserCashback::latest()->get();
            $filename = "cashbacks.csv";
            $handle = fopen($filename, 'w+');
            fputcsv($handle, array('User', 'User email', 'Store', 'Amount', 'Exit Click Id', 'Event Time', 'Status'));

            foreach ($table as $row) {

                $fname = $row->user->first_name ?? '';
                $lname = $row->user->last_name ?? '';
                fputcsv($handle, array(
                    $fname . ' ' . $lname, $row->user->email ?? '', $row->amount, $row->store->name,
                    $row->exit_click_id,  $row->event_date, $row->status
                ));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

            return Response::download($filename, 'cashbacks.csv', $headers);
        } catch (\Throwable $th) {
            flash()->error('Error while exporting cashbacks');
            return redirect()->route('admin.commissions.index');
        }
    }
    public function createMultiple()
    {
        $clicks = ExitClick::latest()->get();
        $statuses = DB::table('cashback_statuses')->latest()->get();
        return view('admin-dashboard.commissions.create_multiple', compact('clicks', 'statuses'));
    }

    public function storeMultiple(Request $request)
    {
        $request->validate([
            'exit_click_id' => 'required|integer',
            'network_commission' => 'required|integer',
            'event_date' => 'required|date'
        ]);

        try {
            $global_percentage = SiteSetting::where('type', 'cashback_percentage')->first()->value;
            foreach ($request->exit_click_id as $key => $value) {
                $click = ExitClick::findOrFail($value);
                $custom_cashback_percentage = $click->store->custom_cashback_percentage;

                if ($custom_cashback_percentage) {
                    $cashback_percent = $custom_cashback_percentage;
                } else {
                    $cashback_percent = $global_percentage;
                }
                $commission = UserCashback::create([
                    'store_id' => $click->store_id,
                    'user_id'  => $click->user_id ?? 0,
                    'exit_click_id' => $click->id,
                    'amount' => round($request->amount[$key], 3),
                    'network_commission' => round($request->network_commission[$key], 3),
                    'order_value' => round($request->order_value[$key], 3),
                    'status' => $request->status[$key],
                    'event_date' => \Carbon\Carbon::parse($request->event_date[$key])->format('Y-m-d H:i:s'),
                    'click_date' => $click->created_at,

                ]);

                $change_status = CashbackStatusChange::create([
                    'user_cashback_id' => $commission->id,
                    'cashback_status_id' => $commission->status

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
        $coms = $coms->newQuery();

        // Search by user.
        if ($request->input('user')) {
            $coms->whereHas('user', function ($query) use ($request) {
                $query->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', "%{$request->user}%");
            })
                ->orwhereHas('store', function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->user}%");
                });
        }

        // Search by cick.
        if ($request->input('click_id')) {
            $coms->where('exit_click_id', $request->click_id)
                ->orwhere('user_id', $request->click_id)
                ->orwhere('store_id', $request->click_id);
        }

        // Search by status.
        if ($request->input('status_id') != -1) {
            $coms->where('status', $request->input('status_id'));
        }

        $coms = $coms->latest()->paginate(20);
        $route = 'search';
        return view('admin-dashboard.commissions.index_data', compact('coms', 'route'))->render();
    }

    public function statusHistory(UserCashback $commission)
    {
        $history = $commission->statusHistory;
        return view('admin-dashboard.commissions.history', compact('history'))->render();
    }

    public function commissionsForm()
    {
        $clicks = ExitClick::latest()->get();
        $statuses = DB::table('cashback_statuses')->latest()->get();
        return view('admin-dashboard.commissions.form_multiple', compact('clicks', 'statuses'));
    }

    public function importCashBacksForm()
    {
        return view('admin-dashboard.commissions.import_csv_form');
    }

    public function importCashBacks(Request $request)
    {
        $validator = $request->validate([
            'import_cashback' => 'required|file|mimes:csv'
        ]);

        if (($open = fopen($request->import_cashback, "r")) !== FALSE) {
            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                $csvData[] = $data;
            }
            fclose($open);
        }
        $csvData = array_values(array_filter($csvData));
        unset($csvData[0]);

        $statuses = DB::table('cashback_statuses')->latest()->get();
        return view('admin-dashboard.commissions.form_multiple', compact('csvData', 'statuses'));
    }
}
