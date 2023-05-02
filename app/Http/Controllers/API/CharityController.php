<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CharityResource;
use App\Http\Resources\CharityTypeResource;
use App\Http\Resources\CountryResource;
use App\Models\Charity;
use App\Models\CharityType;
use App\Models\Country;
use Illuminate\Http\Request;

class CharityController extends Controller
{
    public function getCharities(Request $request)
    {
        try {
            $countryData = CountryResource::collection(Country::where('status', '1')->get());
            $charityTypeData = CharityTypeResource::collection(CharityType::where('status', '1')->get());
            $charities = Charity::with('Country')->when($request->has('tag'), function ($query) use ($request) {
                $query->whereHas('tags', function ($query) use ($request) {
                    $query->where('title', $request->tag);
                });
            })->latest()->paginate(20)->appends(request()->input());
            $charityData = CharityResource::collection($charities);

            $metaData = [
                "next" => $charities->nextPageUrl(),
                "previous" => $charities->previousPageUrl(),
                "per_page" => 20,
                "total" => $charities->total(),
                "current_page" => $charities->currentPage(),
                "total_pages" => $charities->lastPage(),
                "first" => $charities->firstItem(),
                "last" => $charities->lastItem()
            ];
            $response = [
                'status' => 200,
                'message' => 'Successful',
                'data' => [
                    'countries' => $countryData,
                    'charity_types' => $charityTypeData,
                    'charities' => $charityData,
                    'metaData' => $metaData
                ]
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }
}
