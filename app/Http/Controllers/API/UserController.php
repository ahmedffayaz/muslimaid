<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CashoutResource;
use App\Http\Resources\ClickResource;
use App\Http\Resources\Home\UserResource;
use App\Http\Resources\ReferralResource;
use App\Http\Resources\TicketResource;
use App\Http\Resources\UserCashbackResource;
use App\Models\Cashout;
use App\Models\ExitClick;
use App\Models\Ticket;
use App\Models\User;
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
            $user = new UserResource(auth()->user());
            $response = [
                'status' => 200,
                'message' => 'Successful',
                'data' => $user,
            ];
            return response()->json($response, 200);
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
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
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
                $avatarImage = Auth::user()->avatar;
                if ($request->hasFile('avatar')) {
                    $avatarImage = storeUserAvatar($request->file('avatar'), $avatarImage);
                }
                Auth::user()->update([
                    'first_name' => $request->firstname,
                    'last_name' => $request->lastname,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'date_of_birth' => $request->dob,
                    'avatar' => $avatarImage,
                ]);

                $user = new UserResource(auth()->user());
                $response = [
                    'status' => 200,
                    'message' => "Successfully Updated.",
                    'data' => $user,
                ];
                return response()->json($response, 200);
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
                $avatarImage = Auth::user()->avatar;
                if ($request->hasFile('file')) {
                    $avatarImage = storeUserAvatar($request->file('file'), $avatarImage);
                }
                Auth::user()->update([
                    'avatar' => $avatarImage,
                ]);

                $response = [
                    'status' => 200,
                    'message' => "Successfully Updated.",
                    'data' => [
                        'image_url' => url('/') . '/' . (Auth::user()->avatar == 'default.png' || !Storage::exists('public/users/images/avatar/' . Auth::user()->avatar) ? 'admin-dashboard/images/avatar.png' : 'storage/users/images/avatar/' . Auth::user()->avatar),
                    ],
                ];
                return response()->json($response, 200);
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
            $cashbacks = UserCashback::where('user_id', Auth::user()->id);
            if (isset($request->status)) {
                $status = convertCashbackStatusToDbFormat($request->status);
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
            $cashbacks = $cashbacks->latest()->paginate(20)->appends(request()->input());
            $cashbackData  = UserCashbackResource::collection($cashbacks);
            $metaData = [
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
                    'cashbacks' => $cashbackData,
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
                    'metaData' => $metaData
                ]
            ];
            return response()->json($response, 200);
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
            $clicks = ExitClick::where('user_id', Auth::user()->id);

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
            $clicks = $clicks->paginate(20)->appends(request()->input());
            $clickData = ClickResource::collection($clicks);
            $metaData = [
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
                    'clicks' => $clickData,
                    'options' => [
                        "All Conversion",
                        "yes",
                        "no"
                    ],
                    'metaData' => $metaData
                ]
            ];
            return response()->json($response, 200);
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
            $tickets = Ticket::orderBy('id', 'desc')->where('user_id', Auth::user()->id);

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
            $tickets = $tickets->paginate(20)->appends(request()->input());
            $ticketData = TicketResource::collection($tickets);
            $metaData = [
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
                    'tickets' => $ticketData,
                    'metaData' => $metaData
                ]
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }

    public function userReferrals(Request $request)
    {
        try {
            $referrals = User::whereNotNull('referred_by')->where('referred_by',  Auth::user()->id);
            if (isset($request->date_from) && isset($request->date_to)) {
                $from = date('Y-m-d', strtotime($request->date_from));
                $to = date('Y-m-d', strtotime($request->date_to));
                $referrals->whereDate('referred_at', '>=', $from)->whereDate('referred_at', '<=', $to);
            }
            $referrals = $referrals->latest()->paginate(20)->appends(request()->input());
            $referralData = ReferralResource::collection($referrals);
            $metaData = [
                "next" => $referrals->nextPageUrl(),
                "previous" => $referrals->previousPageUrl(),
                "per_page" => 20,
                "total" => $referrals->total(),
                "current_page" => $referrals->currentPage(),
                "total_pages" => $referrals->lastPage(),
                "first" => $referrals->firstItem(),
                "last" => $referrals->lastItem()
            ];
            $response = [
                'status' => 200,
                'message' => 'Successful',
                'data' => [
                    'referrals' => $referralData,
                    'total' => $referrals->total(),
                    'metaData' => $metaData
                ]
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }

    public function cashouts(Request $request)
    {
        try {
            $cashouts = Cashout::where('user_id',  Auth::user()->id);
            if (isset($request->status)) {
                $cashouts->where('status', $request->status);
            }
            if (isset($request->payment_method)) {
                $cashouts->where('payment_method', $request->payment_method);
            }
            if (isset($request->date_from) && isset($request->date_to)) {
                $from = date('Y-m-d', strtotime($request->date_from));
                $to = date('Y-m-d', strtotime($request->date_to));
                $cashouts->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
            }
            $cashouts = $cashouts->latest()->paginate(20)->appends(request()->input());
            $cashoutData = CashoutResource::collection($cashouts);
            $metaData = [
                "next" => $cashouts->nextPageUrl(),
                "previous" => $cashouts->previousPageUrl(),
                "per_page" => 20,
                "total" => $cashouts->total(),
                "current_page" => $cashouts->currentPage(),
                "total_pages" => $cashouts->lastPage(),
                "first" => $cashouts->firstItem(),
                "last" => $cashouts->lastItem()
            ];
            $response = [
                'status' => 200,
                'message' => 'Cashout data retrieved successfully',
                'data' => [
                    'records' => $cashoutData,
                    'total' => $cashouts->total(),
                    'metaData' => $metaData
                ]
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }

    public function referralLink()
    {
        try {
            $response = [
                'status' => 200,
                'message' => 'Successful',
                'data' => [
                    "ref_link" => url('/register-form?referby=' . encrypt(auth()->user()->id)),
                    "main_banner_image" => url('/cashblack/img/favicon.png'),
                    "email_placeholder" => "Enter single or multiple emails (separate with comma)"
                ],
            ];
            return response()->json($response, 200);
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
        try {
            return response()->json([
                'status' => 200,
                'message' => 'User balance retrieved successfully',
                'data' => [
                    'available_balance' => currency(auth()->user()->availableBalance(3), false)
                ]
            ], 500);
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
