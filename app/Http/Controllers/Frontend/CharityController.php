<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Models\Charity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CharityController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'charities')->first();
        if (empty($page)) abort(404);

        $charities = Charity::whereStatus(1)->orderBy('id', 'DESC')->paginate(12);

        return view('frontend.charities.index', compact('page', 'charities'));
    }

    public function show($id)
    {
        $charity = Charity::whereId($id)->with('charity_type')->first();
        if (empty($charity)) return null;

        return view('frontend.charities.show', compact('charity'));
    }
}
