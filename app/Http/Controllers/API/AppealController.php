<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appeal;
use App\Http\Resources\AppealResource;
use Exception;

class AppealController extends Controller
{
    public function index()
    {
        try {
            $appeals = Appeal::select('id','title')->get();
            $response = [
                'status' => 200,
                'message' => "Success",
                'data' => AppealResource::collection($appeals),
            ];
            return response()->json($response, 200);
        } catch (Exception $e) {
            $response = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($response, 500);
        }
    }
}
