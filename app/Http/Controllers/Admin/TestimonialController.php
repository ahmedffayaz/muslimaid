<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\User;
use Response;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route='index';
        $testimonial = Testimonial::latest()->paginate(10);
      return view('admin-dashboard.testimonials.index',compact('testimonial','route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::role('user')->get();
        return view('admin-dashboard.testimonials.create',compact('users'));
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
            'title' => 'required|regex:/^[\w. ]+$/',
            'user_image' => 'required|file|mimes:jpg,png|max:' . 1 * 1024, // 1024 KB = 1 MB',
            'name' =>'required',
            'company_name'=>'required',
            'order_no'=>'required|integer',
            'position'=>'required'
        ],$messages = [
            'title.required' => 'The Title field is required.',
            'user_image.required' => 'The user Image is required.',
            'name.required' =>'The user name field is required.',
            'company_name.required' =>'The user company name field is required.',
            'order_no.required' =>'The order number field is required.',
            'order_no.integer' =>'The order number field must be integer.',
            'meta_title.required' =>'The meta title field is required.',
            'Position.required' =>'The user name field is required.',
        ]);

        if($request->has('user_image')){

            $imageName = 'testimonial'.time().'.'.$request->user_image->extension();          
            $request->user_image->storeAs('public/users/images/avatar',$imageName);
        }

            $testimonial = new Testimonial;
            $testimonial->user_id = $request->user;
            $testimonial->title = $request->title;
            $testimonial->description = $request->description;
            $testimonial->image = $imageName;
            $testimonial->name = $request->name;
            $testimonial->position = $request->position;
            $testimonial->company = $request->company_name;
            $testimonial->status = $request->status;
            $testimonial->order_no = $request->order_no;
            $testimonial->save();

            flash()->success('Testimonial added successfully.');
            return redirect()->route('admin.testimonials.index');;
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
    public function edit(Testimonial $testimonial)
    {
        $users = User::role('user')->get();
        return view('admin-dashboard.testimonials.edit',compact('testimonial','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'title' => 'required|regex:/^[\w. ]+$/',
            'name' =>'required',
            'company_name'=>'required',
            'order_no'=>'required|integer',
            'position'=>'required'
        ],$messages = [
            'title.required' => 'The Title field is required.',
            'user_image.required' => 'The user Image is required.',
            'name.required' =>'The user name field is required.',
            'company_name.required' =>'The user company name field is required.',
            'order_no.required' =>'The order number field is required.',
            'order_no.integer' =>'The order number field must be integer.',
            'meta_title.required' =>'The meta title field is required.',
            'Position.required' =>'The user name field is required.',
        ]);

        $imageName = $testimonial['image'];
        if($request->has('user_image')){

            $imageName = 'testimonial'.time().'.'.$request->user_image->extension();          
            $request->user_image->storeAs('public/users/images/avatar',$imageName);
        }
       
        $testimonial->title = $request->title;
        $testimonial->description = $request->description;
        $testimonial->image = $imageName;
        $testimonial->name = $request->name;
        $testimonial->position = $request->position;
        $testimonial->company = $request->company_name;
        $testimonial->status = $request->status;
        $testimonial->order_no = $request->order_no;
        $testimonial->save();
        flash()->success('Testimonial updated successfully');
        return redirect()->route('admin.testimonials.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        flash()->success('Testimonial deleted successfully');
        return redirect()->route('admin.testimonials.index');
    }

    public function userDetails($id)
    {
        $user = User::where('id',$id)->first();
        return Response::json(['data'=>$user]);
    }
}
