<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function index($letter = null)
    {
        $page = Page::where('slug', 'vouchers')->whereType('system')->firstOrFail();
        $stores = Store::select('id', 'name', 'slug', 'status', 'created_at')
                ->where('status', 'active')
                ->inRandomOrder()
                ->take(5)
                ->withCount('cashbacks')
                ->get();

        return view('frontend.pages.single-page', compact('stores', 'page', 'letter'));
    }

    public function view(Request $request)
    {
        $vouchers = Voucher::when($request->order_by == 'coupons', function ($query) {
            $query->where('promotion_type', 'Coupon');
        })
            ->when($request->order_by == 'offers', function ($query) {
                $query->where('promotion_type', 'Sale/Discount');
            })
            ->when($request->order_by == 'trending', function ($query) {
                $query->whereHas('exitClicks')->withCount('exitClicks')->orderBy('exit_clicks_count', 'desc');
            })
            ->when($request->order_by == 'latest', function ($query) {
                $query->latest();
            })
            ->when($request->order_by == 'expiring', function ($query) {
                $query->orderBy('promotion_end_date', 'asc');
            })->whereHas('store', function ($query) {
                $query->where('status', 'active');
            })->with(['store' => function ($query) {
                $query->select('id', 'name', 'slug', 'status')->where('status', 'active')->withCount('cashbacks');
            }])
            ->where('promotion_end_date', '>=', now())->paginate($request->input('perPage'));

        return view('frontend.vouchers.vouchers-view', compact('vouchers'));
    }
}
