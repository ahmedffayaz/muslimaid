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
        $user = \Auth::user();
        return view('client-dashboard.dashboard',compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit()
    {
        $user = \Auth::user();
        return [
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'email'      => $user->email,
            'phone'      => $user->phone,
            'intro'      => $user->intro,
            'profile_image' => $user->avatar ? url('storage/users/images/avatar/'.$user->avatar) : ''
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
         
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
        $balance = number_format((float)Auth::user()->availableBalance(), 2, '.', '');
        $arr = array("status" => 200, "message" =>"User Balance", "data" => ['available_balance' => $balance]);
        return Response::json($arr);
    }
}
