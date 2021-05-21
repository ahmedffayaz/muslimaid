<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\Currency;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route='index';
        $settings = SiteSetting::latest()->get()->pluck('value','type');
        $currencies = Currency::all();
        $sc = Currency::where('id',$settings['currency'])->pluck('symbol')->first();
        return view('admin-dashboard.settings.settings',compact('settings','route','currencies','sc'));
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
        if($setting->default){
            flash()->error('default settings can not be deleted');
            return redirect()->route('admin.settings.index');
        }
        $setting->delete();

        flash()->success('setting deleted successfully');
        return redirect()->route('admin.settings.index');
    }
    function fetch(Request $request)
    {
     if($request->ajax())
     {
         $route='index';
       
        $settings = SiteSetting::latest()->paginate(20);
         return view('admin-dashboard.settings.index_data', compact('settings','route'))->render();
     }
    }
    public function searchSettings(Request $request, SiteSetting $settings)
    {
        
        $settings = $settings->newQuery();

        // Search by title.
        if ($request->input('title')) {
            $settings->where('title','like', '%'.$request->input('title').'%');
           
        }
         // Search by key.
         if ($request->input('key')) {
            $settings->where('type','like', '%'.$request->input('key').'%');
           
        }
        $settings = $settings->latest()->paginate(20);
        $route='search';
        return view('admin-dashboard.settings.index_data', compact('settings','route'))->render();
    }

    public function mailerSettings(){

        $settings = SiteSetting::latest()->get()->pluck('value','type');
        // dd($settings);
        return view('admin-dashboard.settings.mailer_settings',compact('settings'));

    }
    public function saveSettings(Request $request){
        
        try {

            $request->offsetUnset('_method');
            $request->offsetUnset('_token');        
            foreach ($request->input() as $key => $value) {

                $settings = SiteSetting::updateOrCreate([
                    'type'   => $key,
                    'title'  => ucwords(str_replace('_',' ',$key)
                    )
                ],[
                    'value'     => $value    
                ]);
           
            } 
            if($request->has('dashboard_logo')){

                $imageName = 'dashboard_logo_'.time().'.'.$request->dashboard_logo->extension();          
                $request->dashboard_logo->storeAs('public/dashboard/images/logo',$imageName);

                $settings = SiteSetting::updateOrCreate([
                    'type'   => 'dashboard_logo',
                    'title'  => 'Dashboare Logo',
                    
                ],[
                    'value'     =>  $imageName  
                ]);
    
            } 
            if($request->has('dashboard_small_logo')){

                $imageName = 'dashboard_small_logo_'.time().'.'.$request->dashboard_small_logo->extension();          
                $request->dashboard_small_logo->storeAs('public/dashboard/images/logo',$imageName);

                $settings = SiteSetting::updateOrCreate([
                    'type'   => 'dashboard_small_logo',
                    'title'  => 'Small Dashboare Logo',
                    
                ],[
                    'value'     =>  $imageName  
                ]);
    
            } 
            return array('message'=>'Settings saved',
                    'response'=>'success');

        } catch (\Throwable $th) {
            return array('message'=>$th->getMessage(),
                        'response'=>'error');
        }

        

    }
}
