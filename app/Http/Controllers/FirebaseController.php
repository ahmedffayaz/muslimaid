<?php

namespace App\Http\Controllers;

use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FirebaseController extends Controller
{
    public function store(Request $request)
    {
        $token = $request->input('token');
    
    try {
        DB::beginTransaction();
        
        auth()->user()->devices()->updateOrCreate([
            'fcm_token' => $token
        ], [
            'type' => UserDevice::TYPE_WEB
        ]);
        
        DB::commit();
        
        return response()->json(['message' => 'Registration token saved successfully']);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], $e->getCode());
    }
    }
}
