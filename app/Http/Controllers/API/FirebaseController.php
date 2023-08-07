<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class FirebaseController extends Controller
{
    public function enableNotifications(Request $request){
        $validator = Validator::make($request->all(), [
            'fcmtoken' => 'required|max:255'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        try{
            DB::beginTransaction();
            auth()->user()->devices()->updateOrCreate([
                'fcm_token' => $request->fcmtoken,
                'type' => UserDevice::TYPE_API
            ]);
            DB::commit();
            return response()->json(['message' => 'Notifications enabled successfully'], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong, Please try again'], 500);
        }
    }

    public function disableNotifications(Request $request){
        try{
            DB::beginTransaction();
            auth()->user()->devices()->whereType('api')->delete();
            DB::commit();
            return response()->json([
                'message' => 'Notifications disabled successfully'
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong, Please try again'], 500);
        }
    }
}
