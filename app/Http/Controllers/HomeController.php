<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExitClick;
use App\Models\UserCashback;
use App\Models\Store;
use App\Models\Network;
use App\Models\CashbackStatus;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $paid_total_commission = UserCashback::where('status','4')->sum('network_commission');
        $paid_total_cashback = UserCashback::where('status','4')->sum('amount');
        $total_revenue  = $paid_total_commission - $paid_total_cashback;
        $penidng_total_commission = UserCashback::where('status','!=','4')->sum('network_commission');
        $penidng_total_cashback = UserCashback::where('status','!=','4')->sum('amount');

        $pending_total_revenue = $penidng_total_commission - $penidng_total_cashback;
        $coms = UserCashback::latest()->get();
        $networks = Network::latest()->get();
        $stores = Store::latest()->get();
        $statuses = CashbackStatus::latest()->get();
        $clicks = ExitClick::latest()->get();
        return view('admin-dashboard.home',compact('coms','networks','statuses','stores','total_revenue','pending_total_revenue','clicks'));
        return view('admin-dashboard.home');
    }
}
