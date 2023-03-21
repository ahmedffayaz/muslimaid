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
        $charities = Charity::whereStatus(1)->orderBy('id', 'DESC');
        $countryId = null;
        $charity = null;
        if (isset($request->country)) {
            $countryId = $request->country;
            $charities->where('country', $request->country);
        }

        if (isset($request->charity_types_id)) {
            $charity = $request->charity_types_id;
            $charities->where('charity_types_id', $request->charity_types_id);
        }

        $charities = $charities->paginate(12);
        $charities->appends([
            'country' => $countryId,
            'charity_types_id' => $charity,
        ]);
        $countries = Country::where('status', '1')->latest()->get();
        $charityTypes = CharityType::where('status', '1')->latest()->get();
        return view('frontend.pages.single-page', compact('page', 'charities', 'countries', 'charityTypes', 'countryId', 'charity'));
    }

    public function show($id)
    {
        $charity = Charity::whereId($id)->with('charity_type')->first();
        if (empty($charity)) return null;

        return view('frontend.charities.show', compact('charity'));
    }
}
