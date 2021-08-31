<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Network;
use App\Models\SiteSetting;
use App\Models\ImportedCategory;
use App\Models\Category;
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
        $this->middleware('permission:view view networks', ['only' => ['index','show']]);
        $this->middleware('permission:view network categories', ['only' => ['categories']]);
        $this->middleware('permission:delete languages', ['only' => ['destroy']]);
    }


    public function index()
    {
        $queue = \DB::table('jobs')->get();
        $networks = Network::all();
        $settings = SiteSetting::latest()->get()->pluck('value','type');
        return view('admin-dashboard.networks.index', compact('networks','queue','settings'));
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
            flash()->success('New network added');
            return redirect()->route('admin.networks.index');
           
            
        } catch (Exception $exception) {

            flash()->error('Error while adding new network');
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
        // try {
        //     $network->update([
        //         // 'name'=>$request->input('network_name'),
        //         // 'click_ref'=>$request->input('click_ref'),
        //         // 'description'=>$request->input('network_name'),
        //         'token'=>$request->input('token'),
        //         'website_id'=>$request->input('website_id'),
        //         'requestor_cid'=>$request->input('requestor_cid'),
        //     ]);

        //     flash()->success('Network updated');
        //     return redirect()->route('admin.networks.index');
           
            
        // } catch (Exception $exception) {

        //     flash()->error('Error while updating the network');
        //     return redirect()->route('admin.networks.index');

            
        // }
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
    public function categories(Network $network)
    {
        $route = 'index';
        $categories = ImportedCategory::where('network_id',$network->id)->latest()->get();
        $network_categories = ImportedCategory::where('network_id',$network->id)->latest()->get();
        $store_categories = Category::latest()->get();
        return view('admin-dashboard.imported-categories.categories', compact('categories','network','network_categories','store_categories','route'));
    }


    function fetch(Request $request)
    {
        // if($request->ajax()){
        //     $categories = ImportedCategory::where('network_id',1)->latest()->paginate(30);
        //     return view('admin-dashboard.imported-categories.index_data', compact('categories'))->render();
        // }
    }

    public function exportCsv(Network $network)
    {
        try {
            $table = ImportedCategory::where('network_id',$network->id)->latest()->get();
            $filename = "importedcategories.csv";
            $handle = fopen($filename, 'w+');
            fputcsv($handle, array('Name', 'Parent Category', 'Mapped To', 'Status'));

            foreach($table as $row) {
                fputcsv($handle, array($row->name, $row->parent->name ?? '', $row->mappedTo->name ?? 'unmapped', $row->status ? 'active' : 'in-active'));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

            return \Response::download($filename, 'importedcategories.csv', $headers);

        } catch (\Throwable $th) {

            flash()->error('Error while exporting categories');
            return redirect()->route('admin.networks.index');

        }
        


    }
}
