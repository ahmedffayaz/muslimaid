<?php

namespace App\Http\Controllers\Admin;

use App\Models\Charity;
use Illuminate\Support\Facades\File;
use App\Models\CharityType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CharityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $charities = Charity::latest()->paginate(30);
        $charitiestypes = CharityType::where('status', '1')->get();
        return view('admin-dashboard.charities.index', compact('charities', 'charitiestypes'));
    }

    public function searchCharities(Request $request)
    {
        $charitiestypes = CharityType::latest()->get();
        $charities = (new Charity())->newQuery();

        if ($request->input('charity_types_id')) {
            $charities->where('charity_types_id', $request->input('charity_types_id'));
        }
        if ($request->input('country')) {
            $charities->where('country', $request->input('country'));
        }
        if ($request->input('title')) {
            $charities->where('title', $request->input('title'));
        }
        if ($request->input('status') != -1) {
            $charities->where('status', $request->input('status'));
        }
        $charities = $charities->orderBy('title', 'DESC')->latest()->paginate(30);
        $route = 'search';
        return view('admin-dashboard.charities.index_data', compact('charities', 'route'))->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $charitiestypes = CharityType::where('status', '1')->get();
        return view('admin-dashboard.charities.create', compact('charitiestypes'));
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
            'title' => 'required|max:255',
            'charity_types_id' => 'required',
            'country' => 'required',
            'logo_type' => 'required|max:255',
            'banner_type' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.charities.create')
                ->withErrors($validator)
                ->withInput();
        }
        $charity = Charity::create([
            'title' => $request->input('title'),
            'charity_types_id' => $request->input('charity_types_id'),
            'description' => $request->input('description'),
            'logo_type' => $request->input('logo_type'),
            'logo_link' => $request->input('logo_link'),
            'banner_type' => $request->input('banner_type'),
            'banner_link' => $request->input('banner_link'),
            'country' => $request->input('country'),
            'status' => $request->input('status'),
        ]);
        if ($request->input('logo_type') == 'upload') {
            if ($request->has('logo_upload')) {
                $imageName = $request->input('name') . '_logo_' . time() . '.' . $request->logo_upload->extension();
                if (File::exists(public_path('storage/charities/images' . $imageName))) {
                    File::delete(public_path('storage/charities/images' . $imageName));
                }
                $request->logo_upload->storeAs('public/charities/images', $imageName);

                $charity->logo_upload = $imageName;
                $charity->update();
            } else {
                $charity->logo_upload = 'category_default_logo.png';
                $charity->update();
            }
        }

        if ($request->input('banner_type') == 'upload') {
            if ($request->has('banner_upload')) {
                $imageName = $request->input('name') . '_banner_' . time() . '.' . $request->banner_upload->extension();
                $request->banner_upload->storeAs('public/charities/images', $imageName);
                $charity->banner_upload = $imageName;
                $charity->update();
            } else {
                $charity->banner_upload = 'category_default_banner.png';
                $charity->update();
            }
        }

        flash()->success('New Charity added');
        return redirect()->route('admin.charities.index');
    }

    //  charity index function
    public function charityTypeView()
    {
        $CharityType = CharityType::latest()->paginate(15);
        return view('admin-dashboard.charities.charity_type_view', compact('CharityType'));
    }

    //  charity store function
    public function charityTypeStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'status' => 'required',
        ], $messages = [
            'title.required' => 'The Title field is required.',
            'status.required' => 'The Status is required.',
        ]);
        if (!$request->ajax()) {
            $charity = new CharityType();
            $charity->create([
                'title' =>  $request->title,
                'status' => $request->status,
            ]);
        }
        flash()->success('New Charity Type added');
        return redirect()->back();
    }

    //  charity edit function
    public function charityTypeEdit($id)
    {
        $charityType = CharityType::find($id);
        return view('admin-dashboard.charities.charity_type_edit', compact('charityType'));
    }

    public function charityTypeUpdate($id, Request $request)
    {
        CharityType::where('id', $id)->update([
            'title' => $request->input('title'),
            'status' => $request->input('status'),
        ]);
        flash()->success('Charities Types updated');

        return redirect()->route('admin.charities.charity_type_view');
    }

    //  charity destroy function
    public function charityTypeDestroy($id)
    {
        CharityType::where('id', $id)->delete();
        flash()->success('Charity Type deleted');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Charity $charity)
    {
        $CharityType = CharityType::latest()->get();
        return view('admin-dashboard.charities.edit', compact('charity', 'CharityType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Charity $charity)
    {
        $charity->update([
            'title' => $request->input('title'),
            'country' => $request->input('country'),
            'charity_types_id' => $request->input('charity_types_id'),
            'logo_type' => $request->input('logo_type'),
            'logo_link' => $request->input('logo_link'),
            'banner_type' => $request->input('banner_type'),
            'banner_link' => $request->input('banner_link'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ]);
        if ($request->input('logo_type') == 'upload') {
            if ($request->has('logo_upload')) {
                $imageName = Str::slug($request->input('name')) . '_logo_' . time() . '.' . $request->logo_upload->extension();
                $request->logo_upload->storeAs('public/charities/images', $imageName);
                $charity->logo_upload = $imageName;
                $charity->update();
            }
        }
        if ($request->input('banner_type') == 'upload') {
            if ($request->has('banner_upload')) {
                $imageName = Str::slug($request->input('name')) . '_banner_' . time() . '.' . $request->banner_upload->extension();
                $request->logo_upload->storeAs('public/charities/images', $imageName);
                $charity->banner_upload = $imageName;
                $charity->update();
            }
        }

        if (!$request->ajax()) {
            flash()->success('Charities updated');
            return redirect()->route('admin.charities.index');
        } else {
            return 1;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Charity $Charity)
    {
        $Charity->delete();
        flash()->success('Charity deleted');
        return redirect()->route('admin.charities.index');
    }
}
