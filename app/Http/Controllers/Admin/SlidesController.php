<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\Store;

class SlidesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $slide = Slide::create($request->all());
        if($request->has('logo')){
            $imageName = \Str::slug($request->input('name')).'_logo_'.time().'.'.$request->logo->extension();          
            $request->logo->storeAs('public/slider/slides/images',$imageName);
            
            $slide->logo = $imageName;
            $slide->update();
        }

        if($request->has('banner')){       
            $imageName = \Str::slug($request->input('name')).'_banner_'.time().'.'.$request->banner->extension();          
            $request->banner->storeAs('public/slider/slides/images',$imageName);
            
            $slide->banner = $imageName;
            $slide->update();
        }


        flash()->success('Slide created successfully');
        return redirect()->back();
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
    public function edit(Slide $slide)
    {   
        $stores = Store::latest()->get();
        return view('admin-dashboard.sliders.edit-slide',compact('slide','stores'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slide $slide)
    {
        $slide->update([
            'name'=>$request->input('name'),
            'description' => $request->input('description'),
            'store_id' => $request->input('store_id'),
        ]);
        if($request->has('logo')){
            $imageName = \Str::slug($request->input('name')).'_logo_'.time().'.'.$request->logo->extension();          
            $request->logo->storeAs('public/slider/slides/images',$imageName);
            
            $slide->logo = $imageName;
            $slide->update();
        }

        if($request->has('banner')){       
            $imageName = \Str::slug($request->input('name')).'_banner_'.time().'.'.$request->banner->extension();          
            $request->banner->storeAs('public/slider/slides/images',$imageName);
            
            $slide->banner = $imageName;
            $slide->update();
        }


        flash()->success('Slide updated successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slide $slide)
    {
        $slide->delete();
        flash()->success('Slide deleted successfully');
        return redirect()->back();
    }
}
