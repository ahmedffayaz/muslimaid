<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreCashback;

class StoreCashbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cashbacks = StoreCashback::latest()->get();
        return view('admin-dashboard.storecashbacks.index', compact('cashbacks'));
    }
}
