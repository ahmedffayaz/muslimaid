<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Jobs\SendEmail;
use App\Models\Network;
use App\Models\ExitClick;
use App\Models\SiteSetting;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Jobs\SendNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CashbackStatusChange;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommissionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view cashback', ['only' => ['index']]);
        $this->middleware('permission:edit cashback', ['only' => ['edit', 'show', 'update']]);
        $this->middleware('permission:add cashback', ['only' => ['create', 'Store']]);
        $this->middleware('permission:delete cashback', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route = 'index';
        $networks = Network::latest()->get();
        $clicks = ExitClick::latest()->get();
        $statuses = DB::table('cashback_statuses')->latest()->get();
        $coms = UserCashback::latest()->paginate(20);

        return view('admin-dashboard.commissions.index', compact('coms', 'networks', 'clicks', 'statuses', 'route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clicks = ExitClick::latest()->get();
        $statuses = DB::table('cashback_statuses')->latest()->get();
        return view('admin-dashboard.commissions.create', compact('clicks', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'exit_click_id' => 'required|integer|min:1',
            'order_value' => 'nullable|numeric|min:0.01',
            'network_commission' => 'required|numeric|min:0.01',
            'amount' => 'nullable|numeric',
            'status' => 'required|integer'
        ], [
            'exit_click_id.required' => 'Exit click is required',
            'exit_click_id.integer' => 'Exit click should be integer',
            'order_value.numeric' => 'Order values should be number',
            'amount.numeric' => 'Cashback amount should be number',
        ]);

        try {
            $click = ExitClick::findOrFail($request->exit_click_id);
            $customCashbackPercentage = $click->store->custom_cashback_percentage;

            if ($customCashbackPercentage) {
                $cashback_percent = $customCashbackPercentage;
            } else {
                $cashback_percent = SiteSetting::where('type', 'cashback_percentage')->first()->value;
            }

            $commission = UserCashback::create([
                'store_id' => $click->store_id,
                'user_id'  => $click->user_id ?? 0,
                'exit_click_id' => $click->id,
                'amount' => round(($request->network_commission / 100) * $cashback_percent, 3),
                'network_commission' => round($request->network_commission, 3),
                'order_value' => round($request->order_value, 3),
                'status' => $request->status,
                'event_date' => $click->created_at,
                'click_date' => $click->created_at,
                'is_api' => 'no',
            ]);

            CashbackStatusChange::create([
                'user_cashback_id' => $commission->id,
                'cashback_status_id' => $commission->status
            ]);

            if ($click->user_id != 0) {
                $emailTemplate = EmailTemplate::where('key', 'user_new_cashback_tracked')->first();

                $filteredMessage = str_replace(
                    ['{{SITE_TITLE}}', '{{SITE_URL}}', '{{NAME}}', '{{EMAIL}}', '{{STORE}}', '{{AMOUNT}}'],
                    [
                        SiteSetting()['website_title'], url('/'),
                        $commission->user->first_name . ' ' . $commission->user->last_name,
                        $commission->user->email, $commission->store->name, $commission->amount
                    ],
                    $emailTemplate->message
                );

                $data = array(
                    'subject' => $emailTemplate->subject,
                    'email_message' => $filteredMessage,
                    'email' => $commission->user->email
                );

                SendEmail::dispatch($data);
            }
            $title = 'Cashback request completion';
            $message = 'Your cashback is created with' . $click->store->name;
            $url = url('account/cashback');
            $deviceToken = auth()->user()->devices()->first()->fcm_token;

            dispatch(new SendNotification($title, $message, $deviceToken,$url));
            if ($request->ajax()) {
                return response()->json([
                    'status' => JsonResponse::HTTP_OK,
                    'message' => 'New Cashback Added.'
                ], JsonResponse::HTTP_OK);
            }
            flash()->success('New Cashback Added.');
            return redirect()->back();
        } catch (Exception $exception) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Exit Click not found'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(UserCashback $commission)
    {
        $clicks = ExitClick::latest()->get();
        $statuses = DB::table('cashback_statuses')->latest()->get();
        return view('admin-dashboard.commissions.create', compact('commission', 'clicks', 'statuses'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserCashback $commission)
    {
        $validator = Validator::make($request->all(), [
            'exit_click_id' => 'required|integer |min:1',
            'order_value' => 'nullable|numeric|min:0.01',
            'network_commission' => 'required|numeric|min:0.01',
            'amount' => 'nullable|numeric',
            'status' => 'required|integer'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'message' => $validator->errors()->first(),
                    'updated' => 'error'
                ]);
            } else {
                flash()->error($validator->errors()->first());
                return redirect()->back();
            }
        }

        try {
            //track status change of the cashback
            if ($commission->status != $request->status) {
                $change_status = CashbackStatusChange::create([
                    'user_cashback_id' => $commission->id,
                    'cashback_status_id' => $request->status
                ]);
            }
            $click = ExitClick::findOrFail($request->exit_click_id);
            $customCashbackPercentage = $click->store->custom_cashback_percentage;

            if ($customCashbackPercentage) {
                $cashback_percent = $customCashbackPercentage;
            } else {
                $cashback_percent = SiteSetting::where('type', 'cashback_percentage')->first()->value;
            }
            $commission->update([
                'amount' =>  round(($request->network_commission / 100) * $cashback_percent, 3),
                'network_commission' => round($request->network_commission, 3),
                'order_value' => round($request->order_value, 3),
                'status' => $request->status,
            ]);
            if ($request->ajax()) {
                return array(
                    'message' => 'Cashback Updated Successfully ',
                    'updated' => 'success'
                );
            }
            flash()->success('Cashback updated successfully');
            return redirect()->back();
        } catch (Exception $exception) {
            $message = 'Something went wrong! Unable to update the cashback.';
            if ($request->ajax()) {
                return response()->json([
                    'message' => $message,
                    'updated' => 'error'
                ]);
            }
            flash()->error($message);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserCashback $commission)
    {
        $commission->delete();
        flash()->success('Cashback deleted successfully');
        return redirect()->back();
    }
    function fetch(Request $request)
    {
        if ($request->ajax()) {
            $route = 'index';
            $coms = UserCashback::latest()->paginate(20);
            return view('admin-dashboard.commissions.index_data', compact('coms', 'route'))->render();
        }
    }

    public function exportCsv(Request $request)
    {
        try {
            $table = UserCashback::latest()->get();
            $filename = "cashbacks.csv";
            $handle = fopen($filename, 'w+');
            fputcsv($handle, array('User', 'User email', 'Store', 'Amount', 'Exit Click Id', 'Event Time', 'Status'));

            foreach ($table as $row) {
                $fname = $row->user->first_name ?? '';
                $lname = $row->user->last_name ?? '';
                fputcsv($handle, array(
                    $fname . ' ' . $lname, $row->user->email ?? '', $row->amount, $row->store->name ?? '',
                    $row->exit_click_id,  $row->event_date, $row->status
                ));
            }

            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download($filename, 'cashbacks.csv', $headers);
        } catch (Exception $exception) {
            flash()->error('Error while exporting cashbacks');
            return redirect()->route(getAdminPrefix() . '.commissions.index');
        }
    }

    public function createMultiple()
    {
        $clicks = ExitClick::latest()->get();
        $statuses = DB::table('cashback_statuses')->latest()->get();
        return view('admin-dashboard.commissions.create_multiple', compact('clicks', 'statuses'));
    }

    public function storeMultiple(Request $request)
    {
        $request->validate([
            'exit_click_id.*' => 'required|integer |min:1',
            'order_value.*' => 'nullable|numeric|min:0.01',
            'network_commission.*' => 'required|numeric|min:0.01',
            'event_date.*' => 'required|date_format:m/d/Y'
        ], [
            'exit_click_id.*.required' => 'All exit clicks are required',
            'exit_click_id.*.integer' => 'All exit clicks should be integer',
            'order_value.*.numeric' => 'All order values should be number',
            'event_date.*.required' => 'All cashbacks event dates are required',
            'event_date.*.date_format' => 'All cashbacks event dates should be match the format 01/25/2000'
        ]);

        try {
            foreach ($request->exit_click_id as $key => $value) {
                $click = ExitClick::findOrFail($value);
                $customCashbackPercentage = $click->store->custom_cashback_percentage;

                if ($customCashbackPercentage) {
                    $cashback_percent = $customCashbackPercentage;
                } else {
                    $cashback_percent = SiteSetting::where('type', 'cashback_percentage')->first()->value;
                }

                $commission = UserCashback::create([
                    'store_id' => $click->store_id,
                    'user_id'  => $click->user_id ?? 0,
                    'exit_click_id' => $click->id,
                    'amount' => round(($request->network_commission[$key] / 100) * $cashback_percent, 3),
                    'network_commission' => round($request->network_commission[$key], 3),
                    'order_value' => round($request->order_value[$key], 3),
                    'status' => $request->status[$key],
                    'event_date' => dbDate($request->event_date[$key]),
                    'click_date' => $click->created_at,
                    'is_api' => 'no',
                ]);

                CashbackStatusChange::create([
                    'user_cashback_id' => $commission->id,
                    'cashback_status_id' => $commission->status
                ]);

                if ($commission->user_id != 0) {
                    $emailTemplate = EmailTemplate::where('key', 'user_new_cashback_tracked')->first();

                    $filteredMessage = str_replace(
                        ['{{SITE_TITLE}}', '{{SITE_URL}}', '{{NAME}}', '{{EMAIL}}', '{{STORE}}', '{{AMOUNT}}'],
                        [
                            SiteSetting()['website_title'], url('/'),
                            $commission->user->first_name . ' ' . $commission->user->last_name,
                            $commission->user->email, $commission->store->name, $commission->amount
                        ],
                        $emailTemplate->message
                    );

                    $data = array(
                        'subject' => $emailTemplate->subject,
                        'email_message' => $filteredMessage,
                        'email' => $commission->user->email
                    );

                    SendEmail::dispatch($data);
                }
            }

            flash()->success('New cashbacks added');
            return route(getAdminPrefix() . '.commissions.index');
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'error' => 'Exit Click not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'errors' => $exception->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function searchCommissions(Request $request, UserCashback $coms)
    {
        $coms = $coms->newQuery();

        // Search by user.
        if ($request->input('user')) {
            $coms->whereHas('user', function ($query) use ($request) {
                $query->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', "%{$request->user}%");
            })->orWhereHas('store', function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->user}%");
            });
        }

        // Search by click.
        if ($request->input('click_id')) {
            $coms->where('exit_click_id', $request->click_id)
                ->orWhere('user_id', $request->click_id)
                ->orWhere('store_id', $request->click_id);
        }

        // Search by status.
        if ($request->input('status_id') != -1) {
            $coms->where('status', $request->input('status_id'));
        }

        $coms = $coms->latest()->paginate(20);
        $route = 'search';
        return view('admin-dashboard.commissions.index_data', compact('coms', 'route'))->render();
    }

    public function statusHistory(UserCashback $commission)
    {
        $history = $commission->statusHistory;
        return view('admin-dashboard.commissions.history', compact('history'))->render();
    }

    public function commissionsForm()
    {
        $clicks = ExitClick::latest()->get();
        $statuses = DB::table('cashback_statuses')->latest()->get();
        return view('admin-dashboard.commissions.form_multiple', compact('clicks', 'statuses'));
    }

    public function importCashBacksForm()
    {
        return view('admin-dashboard.commissions.import_csv_form');
    }

    public function importCashBacks(Request $request)
    {
        $validator = $request->validate([
            'import_cashback' => 'required|file|mimes:csv,txt'
        ], [
            'import_cashback.required' => 'Upload CSV file.'
        ]);

        try {
            if (($open = fopen($request->import_cashback, "r")) !== FALSE) {
                while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                    $csvData[] = $data;
                }
                fclose($open);
            }
            $csvData = array_values(array_filter($csvData));
            unset($csvData[0]);

            $statuses = DB::table('cashback_statuses')->latest()->get();
            return view('admin-dashboard.commissions.form_multiple', compact('csvData', 'statuses'));
        } catch (Exception $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fileDownload()
    {
        try {
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            $filename = 'multiple_cashbacks.csv';
            $file = public_path('admin-dashboard/sample-files/csv/' . $filename);
            return Response::download($file, $filename, $headers);
        } catch (Exception $exception) {
            flash()->error('File does not exist.');
            return redirect()->route(getAdminPrefix() . '.commissions.create_multiple');
        }
    }
}
