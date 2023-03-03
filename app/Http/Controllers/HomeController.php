<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use App\Models\Ticket;
use App\Models\ExitClick;
use App\Models\StoreReview;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;

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
        return view('admin-dashboard.home');
    }

    public function setLocale($locale)
    {
        session()->put('locale', $locale);

        return response()->json([
            'status' => true,
            'message' => 'Language changed!'
        ]);
    }

    public function dataByPeriod(Request $request)
    {
        $period = $request->input('period');

        if ($period == 0) {
            if (Cookie::get('period')) {
                $period = Cookie::get('period');
            } else {
                $period = 1;
            }
        }
        if ($period == 1) {
            $timePeriod = Carbon::now()->subDay()->toDateTimeString();
            Cookie::queue(Cookie::make('period', 1, 120));
        } elseif ($period == 7) {
            $timePeriod = Carbon::now()->subDays(7)->toDateTimeString();

            Cookie::queue(Cookie::make('period', 7, 120));
        } elseif ($period == 30) {
            $timePeriod = Carbon::now()->subDays(30)->toDateTimeString();

            Cookie::queue(Cookie::make('period', 30, 120));
        }

        $paidTotalCommission = UserCashback::where('status', '4')->where('event_date', '>=', $timePeriod)->sum('network_commission');
        $paidTotalCashback = UserCashback::where('status', '4')->where('event_date', '>=', $timePeriod)->sum('amount');
        $total_revenue  = $paidTotalCommission - $paidTotalCashback;
        $penidng_total_commission = UserCashback::where('status', '!=', '4')->where('event_date', '>=', $timePeriod)->sum('network_commission');
        $penidng_total_cashback = UserCashback::where('status', '!=', '4')->where('event_date', '>=', $timePeriod)->sum('amount');

        $pending_total_revenue = $penidng_total_commission - $penidng_total_cashback;
        $coms = UserCashback::where('event_date', '>=', $timePeriod)->latest()->get();
        $total_coms = UserCashback::latest()->get();
        $stores = Store::latest()->get();
        $clicks = ExitClick::latest()->where('created_at', '>=', $timePeriod)->get();
        $total_clicks = ExitClick::latest()->get();
        $tickets = Ticket::where('new_ticket', 1)->latest()->get();
        $users = User::role('user')->where('created_at', '>=', $timePeriod)->latest()->get();
        $total_users = User::role('user')->latest()->get();
        $reviews = StoreReview::where('status', 'pending')->latest()->get();

        $notconverted = count($clicks) - count($coms);
        $converted = count($coms);

        return view('admin-dashboard.home_data', compact(
            'coms',
            'total_coms',
            'stores',
            'total_revenue',
            'pending_total_revenue',
            'clicks',
            'total_clicks',
            'tickets',
            'users',
            'total_users',
            'reviews',
            'converted',
            'notconverted',
            'period'
        ))->render();
    }
}
