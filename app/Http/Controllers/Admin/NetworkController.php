<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Network;
use Exception;


class NetworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $networks = Network::all();
        return view('admin-dashboard.networks.index', compact('networks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-dashboard.networks.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        try {
            $network = Network::create([
                'name'=>$request->input('network_name'),
                'click_ref'=>$request->input('click_ref'),
                'description'=>$request->input('network_name'),
            ]);
            return redirect()->route('admin.networks.index');
           
            
        } catch (Exception $exception) {
            return redirect()->route('admin.networks.index');

            
        }
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
    public function edit(Network $network)
    {
    
        return view('admin-dashboard.networks.edit', compact('network'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Network $network)
    {
        try {
            $network->update([
                'name'=>$request->input('network_name'),
                'click_ref'=>$request->input('click_ref'),
                'description'=>$request->input('network_name'),
            ]);
            return redirect()->route('admin.networks.index');
           
            
        } catch (Exception $exception) {
            return redirect()->route('admin.networks.index');

            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
