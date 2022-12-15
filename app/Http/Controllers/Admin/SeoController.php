<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seo_rule;
use App\Models\Seo_rule_data;
use Illuminate\Http\JsonResponse;

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
        return view('admin-dashboard.seo.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'url' => 'required|url',
            'type.*.key' => 'required',

        ],$messages = [
            'url.required' => 'The url field is required.',
        ]);

        $seo_rule = Seo_rule::create([
            'url' => $request->url
        ]);

        foreach($request->type as $type)
        {
            $type['type'] = "meta";
            $seo_rule->ruleData()->create($type);
        }

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'success' => 'SEO rule added successfully.'
        ]);
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
        $seoData = Seo_rule::withCount('ruleData')->with('ruleData')->where('id',$seo['id'])->first();

        return view('admin-dashboard.seo.form',compact('seoData'));
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
        ],$messages = [
            'url.required' => 'The url field is required.',
        ]);

        $seo->ruleData()->delete();

        $seo->update([
            'url' => $request->url
        ]);

        if($request->type)
        {
            foreach($request->type as $val)
            {
                $val['type'] = "meta";
                $seo->ruleData()->create($val);
            }
        }

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'success' => 'SEO rule updated successfully.'
        ]);
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
