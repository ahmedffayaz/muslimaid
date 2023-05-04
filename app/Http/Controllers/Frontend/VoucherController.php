<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VoucherController extends Controller
{
    public function index($letter = null)
    {
        $page = Page::where('slug', 'vouchers')->whereType('system')->first();
        $storesType = "Vouchers";
        if (empty($page)) abort(404);

        $stores = Store::has('vouchers')->latest()->paginate(10);

        return view('frontend.pages.single-page', compact('stores', 'page', 'letter', 'storesType'));
    }
}
