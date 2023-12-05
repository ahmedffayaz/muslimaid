<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Slide;
use App\Models\Store;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::all();
        return view('admin-dashboard.sliders.index',compact('sliders'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $slider = Slider::create($request->all());
        flash()->success('slider created successfully');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $slider = Slider::with(['slides' => function ($slide) {
            $slide->with(['store' => function ($store) {
                $store->select('id', 'name', 'slug', 'created_at')->withCount('cashbacks')->get();
            }]);
        }])->findOrFail($id);
        return view('admin-dashboard.sliders.edit',compact('slider'));
    }

    public function sortSlides(Request $request)
    {
        try {
            foreach($request->input('slide') as $order=>$slide){

                $slidex = Slide::where('id',$slide)->first();
                $slidex->update(['order'=>$order]);
            }
            return array('message'=>'Slides order updated',
                'updated'=>'success');
        } catch (\Throwable $th) {
            return array('message'=>'Something went wrong!',
                        'updated'=>'error');
        }
    }
}
