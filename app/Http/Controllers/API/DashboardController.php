<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\ExitClick;
use App\Models\UserCashback;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ClickResource;
use App\Http\Resources\DashboardResources;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserCashbackResource;

class DashboardController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = auth()->user();
            $response = new DashboardResources($user);
            return response($response, 200);
        } catch (\Exception $e) {
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }

    public function cashback(Request $request)
    {
        try {
            $user = Auth::user();
            $cashbacks = UserCashback::where('user_id', $user->id);
            if (isset($request->status)) {
                $status = cashbackStatus($request->status);
                $cashbacks->where('status', $status);
            }
            if (isset($request->store_id)) {
                $cashbacks->whereHas('store', function ($query) use ($request) {
                    $query->where('id', $request->store_id);
                });
            }
            if (isset($request->date_from) && isset($request->date_to)) {
                $cashbacks->whereBetween('event_date', [$request->date_from, $request->date_to]);
            }
            $cashbacks = $cashbacks->latest()->paginate(20);
            $stores = UserCashbackResource::collection($cashbacks);
            $meta_data = [
                "next" => $cashbacks->nextPageUrl(),
                "previous" => $cashbacks->previousPageUrl(),
                "per_page" => 20,
                "total" => $cashbacks->total(),
                "current_page" => $cashbacks->currentPage(),
                "total_pages" => $cashbacks->lastPage(),
                "first" => $cashbacks->firstItem(),
                "last" => $cashbacks->lastItem()
            ];
            $response = [
                'status' => 500,
                'message' => 'Successful',
                'data' => [
                    'stores' => $stores,
                    'options'=> [
                        "Select Status",
                        "Pending",
                        "Confirmed",
                        "Failed",
                        "Paid",
                        "Processing",
                        "Donated",
                        "Processing Donation"
                    ],
                    'meta_data' => $meta_data
                ]
            ];
            return response($response, 200);
        } catch (\Exception $e) {
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }
    public function clicks(Request $request)
    {
        try {
            $user = Auth::user();
            $clicks = ExitClick::where('user_id', $user->id);

            if (isset($request->store_id)) {
                $clicks->whereHas('store', function ($query) use ($request) {
                    $query->where('id', $request->store_id);
                });
            }
            if (isset($request->date_from) && isset($request->date_to)) {
                $from = date('Y-m-d', strtotime($request->date_from));
                $to = date('Y-m-d', strtotime($request->date_to));
                $clicks->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
            }
            if (isset($request->conversion) && $request->conversion != '0') {
                if ($request->conversion == 'yes') {
                    $clicks->whereHas('cashback');
                } else if ($request->conversion == 'no') {
                    $clicks->whereDoesntHave('cashback');
                }
            }
            $clicks = $clicks->paginate(20);
            $stores = ClickResource::collection($clicks);
            $meta_data = [
                "next" => $clicks->nextPageUrl(),
                "previous" => $clicks->previousPageUrl(),
                "per_page" => 20,
                "total" => $clicks->total(),
                "current_page" => $clicks->currentPage(),
                "total_pages" => $clicks->lastPage(),
                "first" => $clicks->firstItem(),
                "last" => $clicks->lastItem()
            ];
            $response = [
                'status' => 500,
                'message' => 'Successful',
                'data' => [
                    'stores' => $stores,
                    'options'=> [
                        "All Conversion",
                        "yes",
                        "no"
                    ],
                    'meta_data' => $meta_data
                ]
            ];
            return response($response, 200);
        } catch (\Exception $e) {
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }
    public function changePassword()
    {
        return view('client-dashboard.change-password');
    }
    public function savePassword(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            flash()->error($validator->errors()->first());
            return redirect()->back();
        }
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        flash()->success('Password changed successfully');
        return redirect()->back();
    }

    public function userBalance()
    {
        $user = Auth::user();
        $balance = number_format((float)Auth::user()->availableBalance(3), 2, '.', '');
        $arr = array("status" => 200, "message" => "User Balance", "data" => ['available_balance' => $balance]);
        return Response::json($arr);
    }
}
