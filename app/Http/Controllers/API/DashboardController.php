<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\ExitClick;
use App\Models\UserCashback;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ClickResource;
use App\Http\Resources\DashboardResources;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserCashbackResource;

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

    public function cashback(){

        $user = auth()->user();
        $cashbacks = UserCashback::select('*'); 
        if(request()->get('status')){
           
            $status = DB::table('cashback_statuses')->where('status',request()->get('status'))->first();
            $cashbacks = $cashbacks->where('status',$status->id);
        } 
        
        return UserCashbackResource::collection($cashbacks->where('user_id',$user->id)->latest()->get()); 
    }
    public function clicks(){

        $user = Auth::user();
        return ClickResource::collection($clicks = ExitClick::where('user_id',$user->id)->latest()->get());
    }
    public function changePassword(){
        return view('client-dashboard.change-password');
    }
    public function savePassword(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            flash()->error($validator->errors()->first());
            return redirect()->back();

        }
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        flash()->success('Password changed successfully');
        return redirect()->back();
    }

    public function userBalance(){
        $user = Auth::user();
        $balance = number_format((float)Auth::user()->availableBalance(3), 2, '.', '');
        $arr = array("status" => 200, "message" =>"User Balance", "data" => ['available_balance' => $balance]);
        return Response::json($arr);
    }
}
