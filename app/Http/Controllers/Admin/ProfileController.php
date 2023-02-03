<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profile = \Auth::user();
        return view('admin-dashboard.profile.edit',compact('profile'));
        
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
    public function edit($id)
    {
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $profile)
    {
        $validated = $request->validate([
            'first_name' => 'required|regex:/^[A-Za-z ]+$/',
            'last_name' => 'required|regex:/^[A-Za-z ]+$/',
            // 'phone' => 'min:10|numeric|max:15',
            // 'address' => 'min:10'
        ],$messages = [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.'
        ]);

        $profile->update($request->input());

        $avatar_image = $profile->avatar;
        if ($request->hasFile('avatar')) {
            $avatar_image = store_user_avatar($request->file('avatar') , $avatar_image);
            $profile->update([
                'avatar'=>$avatar_image
            ]);
        }

        flash()->success('Profle Updated');
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
    public function savePassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
           

            if(!$request->ajax())
            {
                flash()->error($validator->errors()->first());
                return redirect()->back();
            }else{
                return array('message' => $validator->errors()->first(),
                                'updated'=>'error');
            }

        }
        $user->update([
            'password' => Hash::make($request->password),

        ]);
        if(!$request->ajax())
        {
        flash()->success('Password changed successfully');
        return redirect()->route('admin.profile.index');
        }else{
            return array('message' => 'Password updated successfully',
                                'updated'=>'success');
        }

    }
}
