<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VoucherController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'vouchers')->first();
        if (empty($page)) abort(404);

        $stores = Store::has('vouchers')->latest()->paginate(10);
        
        return view('frontend.vouchers.index', compact('stores', 'page'));
    }
}
