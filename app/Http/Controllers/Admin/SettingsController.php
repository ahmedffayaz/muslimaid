<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = SiteSetting::latest()->paginate(10);
        return view('admin-dashboard.settings.index',compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-dashboard.settings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $inputs['type'] = str_replace([' ','-','.'],'_',$request->input('type'));
        $setting = SiteSetting::create($inputs);
        flash()->success('setting saved successfully');
        return redirect()->route('admin.settings.index');
        
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
    public function edit(SiteSetting $setting)
    {
        return view('admin-dashboard.settings.edit',compact('setting'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SiteSetting $setting)
    {
        $inputs = $request->all();
        $inputs['type'] = str_replace([' ','-','.'],'_',$request->input('type'));
        $setting->update($inputs);
        flash()->success('setting updated successfully');
        return redirect()->route('admin.settings.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SiteSetting $setting)
    {
        $setting->delete();

        flash()->success('setting deleted successfully');
        return redirect()->route('admin.settings.index');
    }
}
