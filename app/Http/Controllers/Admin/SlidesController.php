<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slide;
use App\Models\Store;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class SlidesController extends Controller
{
    public $imagePath = 'storage/slider/slides/images/';
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $stores = Store::latest()->get();
        $slider = $request->slider_id;
        return view('admin-dashboard.sliders.edit-slide', compact('stores', 'slider'))->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'store_id' => 'nullable',
            'link' => 'nullable|active_url',
            'logo' => 'nullable|mimes:jpeg,jpg,png,gif|max:2048',
            'banner' => 'required|mimes:jpeg,jpg,png,gif|max:2048'
        ]);

        if ($validator->fails()) {
            if (!$request->ajax()) {
                flash()->error($validator->errors()->first());
                return redirect()->back();
            } else {
                return array(
                    'message' => $validator->errors()->first(),
                    'created' => 'error'
                );
            }
        }
        $slide = Slide::create([
            'slider_id' => $request->input('slide_id') ? $request->input('slide_id') : $request->input('slider_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'link' => $request->input('link'),
            'store_id' => $request->input('store_id'),
            'slider_type' => $request->input('slider_type'),
        ]);

        if ($request->has('logo')) {
            $imageName = Str::slug($request->input('name')) . '_logo_' . time() . '.' . $request->logo->extension();
            $request->logo->storeAs('public/slider/slides/images', $imageName);
            $slide->logo = $this->imagePath .  $imageName;
            $slide->update();
        }

        if ($request->has('banner')) {
            $imageName = Str::slug($request->input('name')) . '_banner_' . time() . '.' . $request->banner->extension();
            $request->banner->storeAs('public/slider/slides/images', $imageName);
            $slide->banner = $this->imagePath . $imageName;
            $slide->update();
        }

        $order = Slide::max('order');
        $slide->update(['order' => $order + 1]);

        flash()->success('Slide created successfully');
        return redirect()->back();
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
        return view('admin-dashboard.sliders.edit-slide', compact('slide', 'stores'))->render();
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'store_id' => 'nullable',
            'link' => 'nullable|active_url',
            'logo' => 'nullable|mimes:jpeg,jpg,png,gif|max:2048',
            'banner' => 'nullable|mimes:jpeg,jpg,png,gif|max:2048'
        ]);

        if ($validator->fails()) {
            if (!$request->ajax()) {
                flash()->error($validator->errors()->first());
                return redirect()->back();
            } else {
                return array(
                    'message' => $validator->errors()->first(),
                    'created' => 'error'
                );
            }
        }

        $slide->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'store_id' => $request->input('store_id'),
            'link' => $request->input('link'),
            'slider_type' => $request->input('slider_type'),
        ]);

        if ($request->has('logo')) {
            if (File::exists(public_path($slide->logo))) {
                File::delete(public_path($slide->logo));
            }
            $imageName = Str::slug($request->input('name')) . '_logo_' . time() . '.' . $request->logo->extension();
            $request->logo->storeAs('public/slider/slides/images', $imageName);
            $slide->logo = $this->imagePath . $imageName;
            $slide->update();
        }

        if ($request->has('banner')) {
            if (File::exists(public_path($slide->banner))) {
                File::delete(public_path($slide->banner));
            }
            $imageName = Str::slug($request->input('name')) . '_banner_' . time() . '.' . $request->banner->extension();
            $request->banner->storeAs('public/slider/slides/images', $imageName);
            $slide->banner = $this->imagePath .  $imageName;
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
