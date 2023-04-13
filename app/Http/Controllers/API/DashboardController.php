<?php

namespace App\Http\Controllers\API;

use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardResources;

class DashboardController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = auth()->user();
            $response = new DashboardResources($user);
            return response($response, 200);
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
