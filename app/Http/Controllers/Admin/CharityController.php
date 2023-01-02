<?php

namespace App\Http\Controllers\Admin;

use App\Models\Charity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\CharityType;

class CharityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $charities=Charity::latest()->get();
        $charitiestypes=CharityType::latest()->get();
        return view('admin-dashboard.charities.index',compact('charities','charitiestypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $charitiestypes=CharityType::latest()->get();
        return view('admin-dashboard.charities.create',compact('charitiestypes'));
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
            'country' => 'required',
            'logo_type' => 'required|max:255',
            'banner_type' => 'required',
            'charity_types_id' =>'required',
        ]);

        if ($validator->fails()) {
            flash()->success($validator->errors());
            return redirect()->route('admin.charities.create')
                        ->withErrors($validator)
                        ->withInput();
        }
        // try {
           $charity = Charity::create([
                'title' => $request->input('title'),
                'country' => $request->input('country'), 
                'charity_types_id' => $request->input('charity_types_id'), 
                'description' => $request->input('description'), 
                'logo_type' => $request->input('logo_type'), 
                'logo_link' => $request->input('logo_link'), 
                'banner_type' => $request->input('banner_type'), 
                'banner_link' => $request->input('banner_link'), 
                'status' => $request->input('status'), 
            ]);
        if($request->input('logo_type')=='upload'){
            if($request->has('logo_upload')){
                
                $imageName = $request->input('name').'_logo_'.time().'.'.$request->logo_upload->extension();          
                $request->logo_upload->storeAs('public/charities/images',$imageName);
                
               $charity->logo_upload = $imageName;
               $charity->update();

            }else{
               $charity->logo_upload = 'category_default_logo.png';
               $charity->update();
            }
        }

        if($request->input('banner_type')=='upload'){

            if($request->has('banner_upload')){
                
                $imageName =$request->input('name').'_banner_'.time().'.'.$request->banner_upload->extension();          
                $request->banner_upload->storeAs('public/charities/images',$imageName);
                
               $charity->banner_upload = $imageName;
               $charity->update();

            }else{
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
         $CharityType=CharityType::latest()->get();
         return view('admin-dashboard.charities.charity_type_view',compact('CharityType'));
     }
    //  charity create function
    public function charityTypeCreate()
    {   
        return view('admin-dashboard.charities.charity_type_create');
    }
    //  charity store function
    public function charityTypeStore(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required',
        ]);

        $charityType= $request->all()([
            'title' =>  $request->title,
            'status' => $request->status,
        ]);
        $charityType->create();
        flash()->success('New Charity Type added');
        return redirect()->route('admin.charities.charity_type_view');
    }
   
    //  charity edit function
    public function charityTypeEdit($id)
    {
        $charityType = CharityType::find($id);
        return view('admin-dashboard.charities.charity_type_edit',compact('charityType'));
    }
    public function charityTypeUpdate($id, Request $request)
    {
       CharityType::where('id',$id)->update([
            'title' => $request->input('title'),  
            'status' => $request->input('status'), 
        ]);  
            flash()->success('Charities Types updated');

            return redirect()->route('admin.charities.charity_type_view');
        }
    //  charity destroy function
    public function charityTypeDestroy(CharityType $charityType)
    {
        $charityType->delete();
        flash()->success('Charity Type deleted');
        return view('admin-dashboard.charities.charity_type_view');
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
    public function edit(Charity $charity)
    {
       $CharityType=CharityType::latest()->get();
        return view('admin-dashboard.charities.edit',compact('charity','CharityType'));
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
            if($request->input('logo_type')=='upload'){

                if($request->has('logo_upload')){

                    // Storage::delete(['public/categories/images/'. $category->logo_upload]);
                    
                    $imageName = Str::slug($request->input('name')).'_logo_'.time().'.'.$request->logo_upload->extension();          
                    $request->logo_upload->storeAs('public/charities/images',$imageName);
                    
                    $charity->logo_upload = $imageName;
                    $charity->update();

                }
            }
            if($request->input('banner_type')=='upload'){

            if($request->has('banner_upload')){

                // Storage::delete(['public/categories/images/'. $category->banner_upload]);

                $imageName = Str::slug($request->input('name')).'_banner_'.time().'.'.$request->banner_upload->extension();          
                $request->logo_upload->storeAs('public/charities/images',$imageName);
                
                $charity->banner_upload = $imageName;
                $charity->update();

            }}

            if(!$request->ajax()){
                flash()->success('Charities updated');

                return redirect()->route('admin.charities.index');
            }else{
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
