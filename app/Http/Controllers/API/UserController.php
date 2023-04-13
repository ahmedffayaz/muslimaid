<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClickResource;
use App\Http\Resources\Home\UserResource;
use App\Http\Resources\TicketResource;
use App\Http\Resources\UserCashbackResource;
use App\Models\ExitClick;
use App\Models\Ticket;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function userData()
    {
        try {
            $user = auth()->user();
            $user = new UserResource($user);
            $response = [
                'status' => 200,
                'data' => $user,
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

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 406,
                'message' => $validator->errors()->first(),
                'data' => []
            ];
            return response()->json($data, 406);
        } else {
            try {
                $avatarImage = $user->avatar;
                if ($request->hasFile('avatar')) {
                    $avatarImage = storeUserAvatar($request->file('avatar'), $avatarImage);
                }
                $user->update([
                    'first_name' => $request->firstname,
                    'last_name' => $request->lastname,
                    'email' => $request->input('email'),
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'date_of_birth' => $request->dob,
                    'avatar' => $avatarImage,
                ]);

                $user = new UserResource($user);
                $response = [
                    'status' => 200,
                    'message' => "Successfully Updated.",
                    'data' => $user,
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
    }

    public function updateAvatar(Request $request)
    {
        try {
            $user = auth()->user();
            $validator = Validator::make($request->all(), [
                'file' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 406,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ];
                return response()->json($data, 406);
            } else {
                $avatarImage = $user->avatar;
                if ($request->hasFile('file')) {
                    $avatarImage = storeUserAvatar($request->file('file'), $avatarImage);
                }
                $user->update([
                    'avatar' => $avatarImage,
                ]);

                $user = new UserResource($user);
                $response = [
                    'status' => 200,
                    'message' => "Successfully Updated.",
                    'data' => [
                        'image_url' => url('/') . '/' . ($user->avatar == 'default.png' || !Storage::exists('public/users/images/avatar/' . $user->avatar) ? 'admin-dashboard/images/avatar.png' : 'storage/users/images/avatar/' . $user->avatar),
                    ],
                ];
                return response($response, 200);
            }
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
            $cashbacksData = UserCashbackResource::collection($cashbacks);
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
                'status' => 200,
                'message' => 'Successful',
                'data' => [
                    'cashbacks' => $cashbacksData,
                    'options' => [
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
            $clicksData = ClickResource::collection($clicks);
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
                'status' => 200,
                'message' => 'Successful',
                'data' => [
                    'clicks' => $clicksData,
                    'options' => [
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


    public function tickets(Request $request)
    {
        try {
            $user = Auth::user();
            $tickets = Ticket::orderBy('id', 'desc')->where('user_id', $user->id);

            if (isset($request->ticket_type)) {
                $tickets->where('claim_type', $request->ticket_type);
            }
            if (isset($request->date_from) && isset($request->date_to)) {
                $from = date('Y-m-d', strtotime($request->date_from));
                $to = date('Y-m-d', strtotime($request->date_to));
                $tickets->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
            }
            if (isset($request->status)) {
                $tickets->where('status', $request->status);
            }
            $tickets = $tickets->paginate(20);
            $ticketsData = TicketResource::collection($tickets);
            $meta_data = [
                "next" => $tickets->nextPageUrl(),
                "previous" => $tickets->previousPageUrl(),
                "per_page" => 20,
                "total" => $tickets->total(),
                "current_page" => $tickets->currentPage(),
                "total_pages" => $tickets->lastPage(),
                "first" => $tickets->firstItem(),
                "last" => $tickets->lastItem()
            ];
            $response = [
                'status' => 200,
                'message' => 'Successful',
                'data' => [
                    'tickets' => $ticketsData,
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

    public function userBalance()
    {
        $user = Auth::user();
        $balance = number_format((float)Auth::user()->availableBalance(3), 2, '.', '');
        $arr = array("status" => 200, "message" => "User Balance", "data" => ['available_balance' => $balance]);
        return response()->json($arr, 500);;
    }
}
