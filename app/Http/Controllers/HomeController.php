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

        $paidTotalCommission = UserCashback::select('id', 'status', 'event_date', 'network_commission')->where('status', '4')->where('event_date', '>=', $timePeriod)->sum('network_commission');
        $paidTotalCashback = UserCashback::select('id', 'status', 'event_date', 'amount')->where('status', '4')->where('event_date', '>=', $timePeriod)->sum('amount');
        $totalRevenue  = $paidTotalCommission - $paidTotalCashback;
        $penidngTotalCommission = UserCashback::select('id', 'status', 'event_date', 'network_commission')->where('status', '!=', '4')->where('event_date', '>=', $timePeriod)->sum('network_commission');
        $penidngTotalCashback = UserCashback::where('status', '!=', '4')->where('event_date', '>=', $timePeriod)->sum('amount');

        $pendingTotalRevenue = $penidngTotalCommission - $penidngTotalCashback;
        $coms = UserCashback::where('event_date', '>=', $timePeriod)->latest()->get();
        $totalComs = UserCashback::count();
        $totalStores = Store::count();
        $totalClicks = ExitClick::all();
        $clicksAgainstTimePeriod = $totalClicks->where('created_at', '>=', $timePeriod)->count();
        $tickets = Ticket::where('new_ticket', 1)->latest()->get();
        $users = User::role('user')->where('created_at', '>=', $timePeriod)->latest()->get();
        $totalUsers = User::role('user')->count();
        $reviews = StoreReview::where('status', 'pending')->latest()->get();

        $notConverted = $clicksAgainstTimePeriod - count($coms);
        $converted = count($coms);

        return view('admin-dashboard.home_data', compact(
            'coms',
            'totalComs',
            'totalStores',
            'totalRevenue',
            'pendingTotalRevenue',
            'clicksAgainstTimePeriod',
            'totalClicks',
            'tickets',
            'users',
            'totalUsers',
            'reviews',
            'converted',
            'notConverted',
            'period'
        ))->render();
    }
}
