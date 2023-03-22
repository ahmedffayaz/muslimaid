<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Models\Charity;
use App\Models\Country;
use App\Models\CharityType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CharityController extends Controller
{
    public function index(Request $request)
    {
        $page = Page::where('slug', 'charities')->whereType('system')->first();
        if (empty($page)) abort(404);
        $charities = Charity::whereStatus(1)->orderBy('id', 'DESC')->paginate(12);
        $countries = Country::where('status', '1')->latest()->get();
        $charityTypes = CharityType::where('status', '1')->latest()->get();
        return view('frontend.pages.single-page', compact('page', 'charities', 'countries', 'charityTypes'));
    }

    public function show($id)
    {
        $charity = Charity::whereId($id)->with('charity_type')->first();
        if (empty($charity)) return null;

        return view('frontend.charities.show', compact('charity'));
    }


    public function search(Request $request)
    {
        $countryId = $request->query('country');
        $charityTypeId = $request->query('charity_types_id');
        $charities = Charity::when(request('country'), function ($query, $countryId) {
            $query->where('country', $countryId);
        })->when(request('charity_types_id'), function ($query, $charityTypeId) {
            $query->where('charity_types_id', $charityTypeId);
        })->paginate(12);
        return view('frontend.templates.charity-index', compact('charities'))->render();
    }
}
