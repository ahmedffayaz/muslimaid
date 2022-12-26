<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketCategory;
use Illuminate\Http\Request;

class TicketCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ticketCategory = TicketCategory::latest()->paginate(10);
        return view('admin-dashboard.ticketCategory.index',compact('ticketCategory'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-dashboard.ticketCategory.create');
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
            'name' => 'required',
            'description' => 'required',
        ],$messages = [
            'name.required' => 'Name is required.',
            'description.required' => 'Description is required.'
        ]);
        $ticketCategory = new TicketCategory();
        $ticketCategory->name = $request->name;
        $ticketCategory->description = $request->description;
        $ticketCategory->save();
        flash()->success('New Ticket Category created successfully');
        return redirect()->route('admin.ticketCategory.index');
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
    public function edit(TicketCategory $ticketCategory)
    {
        return view('admin-dashboard.ticketCategory.edit',compact('ticketCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TicketCategory $ticketCategory)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ],$messages = [
            'name.required' => 'Name is required.',
            'description.required' => 'Description is required.'
        ]);
        $ticketCategory->name = $request->name;
        $ticketCategory->description = $request->description;
        $ticketCategory->save();
        flash()->success('Ticket Catgory updated successfully');
        return redirect()->route('admin.ticketCategory.index');       

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TicketCategory $ticketCategory)
    {
        $ticketCategory->delete();
        flash()->success('Ticket Category deleted successfully');
        return redirect()->route('admin.ticketCategory.index');
    }
}