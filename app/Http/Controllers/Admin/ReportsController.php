<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Store;
use App\Models\Network;
use App\Models\ExitClick;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use App\Models\CashbackStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:view performance', ['only' => ['store_performance','search_performance','fetchPerformance']]);
        $this->middleware('permission:view earings', ['only' => ['earnings','search_earnings','fetchEarnings']]);
    }

    public function store_performance()
    {
        $paid_total_commission = UserCashback::where('status','4')->sum('network_commission');
        $paid_total_cashback = UserCashback::where('status','4')->sum('amount');
        $total_revenue  = $paid_total_commission - $paid_total_cashback;
        $penidng_total_commission = UserCashback::where('status','!=','4')->sum('network_commission');
        $penidng_total_cashback = UserCashback::where('status','!=','4')->sum('amount');
        $pending_total_revenue = $penidng_total_commission - $penidng_total_cashback;
        $coms = UserCashback::latest()->get();
        $total_clicks = ExitClick::latest()->get()->count();

        $stores = Store::latest()->get();
        $clicks = ExitClick::select(DB::raw('count(*) as count, store_id'))->groupBy('store_id')->orderBy('count','DESC')->paginate(20);
        $route = 'index';
        return view('admin-dashboard.reports.store_performance',compact('clicks','stores','total_revenue','pending_total_revenue','clicks','coms','total_clicks','route'));
    }

    public function search_performance(Request $request, ExitClick $clicks)
    {
        $clicks = $clicks->newQuery();

        // Search by store.
        if ($request->input('store_id')) {
            $clicks->where('store_id', $request->input('store_id'));
        }

        // Search by range start date.
        if ($request->input('start_date')) {

            $start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
            $clicks->whereDate('created_at', '>=' ,$start_date);
        }

        //search by range end date
        if ($request->input('end_date')) {

            $end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
            $clicks->whereDate('created_at', '<=' ,$end_date);
         }

        $stores = Store::latest()->get();
        $clicks = $clicks->select(DB::raw('count(*) as count, store_id'))->groupBy('store_id')->orderBy('count','DESC')->paginate(20);
        $route='search';
        return view('admin-dashboard.reports.store_performance_data', compact('clicks','stores','route'))->render();
    }

    function fetchPerformance(Request $request)
        {$route = 'index';
        if($request->ajax())
        {
            $clicks = ExitClick::select(DB::raw('count(*) as count, store_id'))->groupBy('store_id')->orderBy('count','DESC')->paginate(20);
            return view('admin-dashboard.reports.store_performance_data',compact('clicks','route'));
        }
    }

    public function earnings()
    {
        $paid_total_commission = UserCashback::where('status','4')->sum('network_commission');
        $paid_total_cashback = UserCashback::where('status','4')->sum('amount');
        $total_revenue  = $paid_total_commission - $paid_total_cashback;
        $penidng_total_commission = UserCashback::where('status','!=','4')->sum('network_commission');
        $penidng_total_cashback = UserCashback::where('status','!=','4')->sum('amount');
        $totalDonatedCommission = UserCashback::where('status', 7)->sum('network_commission');

         // Hide pending revenue, in case display 100% cashback and only display total donated cashback
        $pending_total_revenue = $penidng_total_commission - $penidng_total_cashback;
         // in case display 100% cashback
        $totalPendingRevenue = UserCashback::where('status', '!=', 7)->where('status', '!=', 4)->sum('network_commission');
        $coms = UserCashback::latest()->paginate(20);
        $networks = Network::latest()->get();
        $stores = Store::latest()->get();
        $statuses = CashbackStatus::latest()->get();
        $clicks = ExitClick::latest()->get();
        $route = 'index';
        return view('admin-dashboard.reports.earnings',compact('coms','networks','statuses','stores','total_revenue','pending_total_revenue','clicks','route', 'totalDonatedCommission', 'totalPendingRevenue'));
    }

    public function search_earnings(Request $request, UserCashback $coms)
    {
        $coms = $coms->newQuery();

        // Search by store.
        if ($request->input('store_id')) {
            $coms->where('store_id', $request->input('store_id'));
        }

        // Search by status.
        if ($request->status_id) {
            $coms->where('status', $request->status_id);
        }

        // Search by range start date.
        if ($request->start_date) {
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
            $coms->whereDate('event_date', '>=' ,$start_date);
        }

        //search by range end date
        if ($request->end_date) {
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
            $coms->whereDate('event_date', '<=' ,$end_date);
         }

        $stores = Store::latest()->get();
        $coms = $coms->latest()->paginate(20);
        $route = 'search';
        return view('admin-dashboard.reports.earnings_data', compact('coms','stores','route'))->render();
    }

    function fetchEarnings(Request $request)
    {
        $route='index';
        if($request->ajax())
        {
            $coms = UserCashback::latest()->paginate(20);
            return view('admin-dashboard.reports.earnings_data', compact('coms','route'))->render();
        }
    }
}
