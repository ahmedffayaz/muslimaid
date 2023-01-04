<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Region;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::all();
        $regions = Region::all();
        $currencies = Currency::all();
        return view('admin-dashboard.countries.index', compact('countries', 'regions', 'currencies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = Region::all();
        $currencies = Currency::all();
        return view('admin-dashboard.countries.form', compact('regions', 'currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'iso_code' => 'required',
            'region_id' => 'required|integer',
            'currency_id' => 'required|integer',
            'status' => 'required|integer',
        ], [
            'title.required' => 'Country name is required.',
            'iso_code.required' => 'ISO Code is required.',
            'region_id.required' => 'Region is required.',
            'region_id.integer' => 'Region value must be integer.',
            'currency_id.required' => 'Currency is required.',
            'currency_id.integer' => 'Currency value must be integer.',
            'status.required' => 'Status is required.',
            'status.integer' => 'Status value must be integer.',
        ]);

        try {
            DB::beginTransaction();

            Country::create([
                'name' => $request->title,
                'iso_code' => $request->iso_code,
                'region_id' => $request->region_id,
                'currency_id' => $request->currency_id,
                'upload_type' => $request->upload_type,
                'type_value' => $request->type_value,
                'status' => $request->status
            ]);

            DB::commit();

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'Country added successfully.'
            ], JsonResponse::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'error' => 'Some thing went wrong'
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $exception->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $regions = Region::all();
        $currencies = Currency::all();
        $country = Country::find($id);
        return view('admin-dashboard.countries.form', compact('regions', 'currencies', 'country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'iso_code' => 'required',
            'region_id' => 'required|integer',
            'currency_id' => 'required|integer',
            'status' => 'required|integer',
        ], [
            'title.required' => 'Country name is required.',
            'iso_code.required' => 'ISO Code is required.',
            'region_id.required' => 'Region is required.',
            'region_id.integer' => 'Region value must be integer.',
            'currency_id.required' => 'Currency is required.',
            'currency_id.integer' => 'Currency value must be integer.',
            'status.required' => 'Status is required.',
            'status.integer' => 'Status value must be integer.',
        ]);

        try {
            DB::beginTransaction();

            Country::find($id)->update([
                'name' => $request->title,
                'iso_code' => $request->iso_code,
                'region_id' => $request->region_id,
                'currency_id' => $request->currency_id,
                'upload_type' => $request->upload_type,
                'type_value' => $request->type_value,
                'status' => $request->status
            ]);

            DB::commit();

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'Country updated successfully.'
            ], JsonResponse::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'error' => 'Some thing went wrong'
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $exception->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
