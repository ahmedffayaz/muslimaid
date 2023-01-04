<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Network;
use App\Models\Category;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Models\ImportedCategory;
use App\Http\Controllers\Controller;
use App\Jobs\AwinCategoryImporter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NetworkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view view networks', ['only' => ['index', 'show']]);
        $this->middleware('permission:view network categories', ['only' => ['categories']]);
        $this->middleware('permission:delete languages', ['only' => ['destroy']]);
    }

    public function index()
    {
        $queue = \DB::table('jobs')->get();
        $networks = Network::all();
        $settings = SiteSetting::latest()->get()->pluck('value', 'type');
        return view('admin-dashboard.networks.index', compact('networks', 'queue', 'settings'));
    }

    public function create()
    {
        return view('admin-dashboard.networks.create');
    }

    public function store(Request $request)
    {
        try {
            $network = Network::create([
                'name' => $request->input('network_name'),
                'click_ref' => $request->input('click_ref'),
                'description' => $request->input('network_name'),
            ]);

            flash()->success('New network added');
            return redirect()->route('admin.networks.index');
        } catch (Exception $exception) {

            flash()->error('Error while adding new network');
            return redirect()->route('admin.networks.index');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit(Network $network)
    {

        return view('admin-dashboard.networks.edit', compact('network'));
    }

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

    public function destroy($id)
    {
        //
    }

    public function categories(Network $network)
    {
        $route = 'index';
        $categories = ImportedCategory::where('network_id', $network->id)->latest()->get();
        $network_categories = ImportedCategory::where('network_id', $network->id)->latest()->get();
        $store_categories = Category::latest()->get();
        return view('admin-dashboard.imported-categories.categories', compact('categories', 'network', 'network_categories', 'store_categories', 'route'));
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
            $table = ImportedCategory::where('network_id', $network->id)->latest()->get();
            $filename = "importedcategories.csv";
            $handle = fopen($filename, 'w+');
            fputcsv($handle, array('Name', 'Parent Category', 'Mapped To', 'Status'));

            foreach ($table as $row) {
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

    public function importCategories(Request $request, Network $network)
    {
        try {
            $validator = Validator::make($request->all(), [
                'csv' => 'required|mimes:csv,txt|max:5120', // 5120 KB = 5 MB
            ], [
                'csv.file' => 'CSV file is required.',
                'csv.mimes' => 'Please upload a valid CSV file.',
                'csv.max' => 'File is too large. Maximum allowed size is 5 MB.',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            if (!(strpos(strtolower($network->name), 'awin') !== false)) {
                throw new Exception('Unable to import categories for this network.');
            }

            $path = Storage::putFileAs('awin-categories-csvs', $request->file('csv'), time() . '.csv');

            dispatch(new AwinCategoryImporter($network, $path));
        } catch (Exception $e) {
            flash()->error($e->getMessage());
        }
    }
}
