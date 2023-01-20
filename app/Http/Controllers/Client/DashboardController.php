<?php

namespace App\Http\Controllers\Client;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ExitClick;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $items = $user->cashbacks()->latest()->limit(5)->get();
        return view('frontend.client-dashboard.dashboard',compact('user', 'items'));
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
        $user = Auth::user();
        return view('frontend.client-dashboard.edit-profile',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // dd($request->all());

        $validated = $request->validate([
            'firstname' => 'required|regex:/^[A-Za-z ]+$/',
            'lastname' => 'required|regex:/^[A-Za-z ]+$/',
            // 'phone' => 'min:10|numeric|max:15',
            // 'address' => 'min:10'
        ],$messages = [
            'firstname.required' => 'First name is required.',
            'lastname.required' => 'Last name is required.'
        ]);

        $user->update([
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'phone' => $request->phone,
            'address' => $request->address,
            'intro' => $request->intro,
        ]);
        flash()->success('User updated successfully');
        return redirect()->back();
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

        $user = Auth::user();
        $cashbacks = UserCashback::where('user_id',$user->id)->latest()->get();
        return view('frontend.client-dashboard.cashback',compact('user','cashbacks'));
    }
    public function clicks(){

        $user = Auth::user();
        $clicks = ExitClick::where('user_id',$user->id)->latest()->get();
        return view('frontend.client-dashboard.clicks',compact('user','clicks'));
    }
    public function changePassword(){
        return view('frontend.client-dashboard.change-password');
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
}
