<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;

class EmailTemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = EmailTemplate::all();
        return view('frontend.emails.admin.index',compact('templates'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $template = EmailTemplate::find($id);
        return view('frontend.emails.admin.edit',compact('template'))->render();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $template = EmailTemplate::find($id);
            $template->update(['subject'=>$request->input('subject'), 'message'=>$request->input('message')]);

            return array('message'=>'Template updated successfully', 'status'=>'success');

        } catch (\Throwable $th) {
            return array('message'=>'Something Went Wrong!', 'status'=>'error');
        }
    }
}
