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
        $route = 'index';
        $stores = Store::latest()->get();
        $networks = Network::latest()->get();
        $users = User::role('user')->latest()->get();
        $clicks = ExitClick::latest()->paginate(20);
        return view('admin-dashboard.clicks.index', compact('clicks', 'stores', 'networks', 'users', 'route'));
    }

    function fetch(Request $request)
    {
        if ($request->ajax()) {
            $route = 'index';
            $clicks = ExitClick::latest()->paginate(20);

            return view('admin-dashboard.clicks.index_data', compact('clicks', 'route'))->render();
        }
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

    public function searchClicks(Request $request, ExitClick $clicks)
    {
        $clicks = $clicks->newQuery();

        // Search by click id.
        if ($request->input('click_id')) {
            $clicks->where('id', $request->click_id)
                ->orWhere('user_id', $request->click_id)
                ->orWhere('store_id', $request->click_id);
        }

        // Search by user.
        if ($request->input('user')) {
            $clicks->whereHas('user', function ($query) use ($request) {
                $query->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', "%{$request->user}%");
            })->orwhereHas('store', function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->user}%");
            });
        }

        // Search by network.
        if ($request->input('network_id')) {
            $clicks->where('network_id', $request->input('network_id'));
        }

        $clicks = $clicks->latest()->paginate(20);
        $route = 'search';

        return view('admin-dashboard.clicks.index_data', compact('clicks', 'route'))->render();
    }
}
