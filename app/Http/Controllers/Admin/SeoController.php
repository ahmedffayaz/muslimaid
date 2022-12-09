<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seo_rule;
use App\Models\Seo_rule_data;

class SeoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seo_rules = Seo_rule::with('ruleData')->get();
        
       return view('admin-dashboard.seo.index',compact('seo_rules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-dashboard.seo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $validated = $request->validate([
            'url' => 'required',
            'title' => 'required',

        ],$messages = [
            'url.required' => 'The url field is required.',
            'meta_description.required' =>'The description field is required.',
            'meta_keyword.required' =>'The user keyword field is required.',
        ]);

        $seo_rule = new Seo_rule;
        $seo_rule->url =url('/').$request->url;
        $seo_rule->title = $request->title;
        $seo_rule->save();

        foreach($request->type as $type)
        {
        $seo_rule_data = new Seo_rule_data;
        $seo_rule_data->seo_rule_id =$seo_rule->id;
        $seo_rule_data->type =  $type['rule_type'];
        $seo_rule_data->value = $type['value'];
        $seo_rule_data->save();
    }

        flash()->success('Seo rule added successfully.');
        return redirect()->route('admin.seo.index');
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
    public function edit(Seo_rule $seo)
    {
        $seo = $seo->with('ruleData')->first();
        return view('admin-dashboard.seo.edit',compact('seo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  Seo_rule $seo)
    {
      
        $validated = $request->validate([
            'url' => 'required|url',
            'title' => 'required',
        ],$messages = [
            'url.required' => 'The url field is required.',
        ]);
        Seo_rule_data::where('seo_rule_id',$seo['id'])->delete();
        $seo->delete();
        
        $seo_rule = new Seo_rule;
        $seo_rule->url = $request->url;
        $seo_rule->title = $request->title;
        $seo_rule->save();

        foreach($request->type as $type)
        {
        $seo_rule_data = new Seo_rule_data;
        $seo_rule_data->seo_rule_id =$seo_rule->id;
        $seo_rule_data->type =  $type['rule_type'];
        $seo_rule_data->value = $type['value'];
        $seo_rule_data->save();
    }

    

        flash()->success('Seo rule updated successfully.');
        return redirect()->route('admin.seo.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seo_rule $seo)
    {
        Seo_rule_data::where('seo_rule_id',$seo['id'])->delete();
        $seo->delete();
        flash()->success('Seo rule deleted successfully');
        return redirect()->route('admin.seo.index');
    }
}
