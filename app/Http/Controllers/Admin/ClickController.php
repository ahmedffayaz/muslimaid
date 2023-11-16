<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Response;
use Throwable;
use App\Models\User;
use App\Models\Store;
use App\Models\Network;
use App\Models\ExitClick;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ClickController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view clicks');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clickCount = ExitClick::count();
        $networks = Network::latest()->get();
        return view('admin-dashboard.clicks.index', compact('clickCount', 'networks'));
    }

    function fetchActiveStoresClicks(Request $request)
    {
        $clicks = ExitClick::whereHas('store', function($query){
            $query->whereNull('deleted_at');
        })
        ->when($request->click_id, function ($query) use ($request){
            $query->where('id', $request->click_id)->orWhere('user_id', $request->click_id)
            ->orWhere('store_id', $request->store_id);
        })
        ->when($request->user, function ($query) use ($request) {
            $query->whereHas('user', function ($query) use ($request){
                $query->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', "%{$request->user}%");
            })->orwhereHas('store', function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->user}%");
            });
        })
        ->when($request->network_id, function ($query) use ($request){
            $query->where('network_id', $request->network_id);
        })->latest()->paginate(20);
        $route = 'fetchActiveStoresClicks';
        return view('admin-dashboard.clicks.index_data', compact('clicks', 'route'))->render();

    }

    public function exportCsv(Request $request)
    {
        try {
            $table = ExitClick::latest()->get();
            $filename = "clicks.csv";
            $handle = fopen($filename, 'w+');

            fputcsv($handle, array('User', 'User Email', 'Store', 'Exit Url', 'Time', 'Status'));

            foreach ($table as $row) {
                fputcsv($handle, array(
                    $row->user->first_name . ' ' . $row->user->last_name,
                    $row->user->email,
                    optional($row->store)->name,
                    $row->exit_url,
                    $row->created_at,
                    $row->status ? 'active' : 'in-active'
                ));
            }

            fclose($handle);
            $headers = array('Content-Type' => 'text/csv',);

            return Response::download($filename, 'clicks.csv', $headers);
        } catch (Throwable $th) {
            flash()->error('Error while exporting exit clicks');

            return redirect()->route(getAdminPrefix() . '.clicks.index');
        }
    }

    public function deletedStoresClicks(Request $request){
        $exitClicks = ExitClick::whereHas('store', function ($query) {
            $query->onlyTrashed();
        })->when($request->click_id, function ($query) use ($request){
            $query->where('id', $request->click_id)->orWhere('user_id', $request->click_id)
            ->orWhere('store_id', $request->store_id);
        })->when($request->user, function ($query) use ($request) {
            $query->whereHas('user', function ($query) use ($request){
                $query->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', "%{$request->user}%");
            })->orwhereHas('store', function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->user}%");
            });
        })->when($request->network_id, function ($query) use ($request){
            $query->where('network_id', $request->network_id);
        })->latest()->paginate(20);
        $route = "deletedStoresClicks";

        return view('admin-dashboard.clicks.archive_data', compact('exitClicks', 'route'))->render();
    }
}
