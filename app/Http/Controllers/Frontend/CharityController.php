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
    function __construct()
    {
        $this->middleware('is_charity_module_access', ['only' => ['index', 'show', 'search']]);
    }

    public function index(Request $request)
    {
        $page = Page::where('slug', 'charities')->whereType('system')->first();
        if (empty($page)) abort(404);
        $charities = Charity::whereStatus(1)->orderBy('id', 'DESC')->paginate(12);
        $countries = Country::where('status','1')->whereHas('charities')->orderBy('name')->latest()->get();
        $charityTypes = CharityType::where('status', '1')->latest()->get();
        return view('frontend.pages.single-page', compact('page', 'charities', 'countries', 'charityTypes'));
    }

    public function show($slug)
    {
        $charity = Charity::whereSlug($slug)->with('charity_type')->first();
        if (empty($charity)) return null;

        return view('frontend.charities.show', compact('charity'));
    }


    public function search(Request $request)
    {
        $countryId = $request->query('country');
        $charityTypeId = $request->query('charity_types_id');
        $charities = Charity::whereHas('Country', function ($query) {
            $query->where('status', 1);
        })->when(request('country'), function ($query, $countryId) {
            $query->where('country', $countryId);
        })->when(request('charity_types_id'), function ($query, $charityTypeId) {
            $query->where('charity_types_id', $charityTypeId);
        })->orderBy('title')->latest()->paginate(12);
        return view('frontend.templates.charity-index', compact('charities'))->render();
    }
}
