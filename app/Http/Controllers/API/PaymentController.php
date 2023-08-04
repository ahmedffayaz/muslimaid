<?php

namespace App\Http\Controllers\APi;

use App\Models\Cashout;
use App\Models\CharityType;
use App\Models\UserCashback;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CashbackStatusChange;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CharityTypeResource;
use App\Http\Resources\UserCashbackResource;

class PaymentController extends Controller
{
    use ApiResponser;


    public function cashouts()
    {
        $user = Auth::user();
        $cashouts = $user->cashouts;
        return $cashouts;
    }

    public function accountWithdraw(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'payment_method' => 'required',
            ]);
            if ($validator->fails()) {
                $data = [
                    'status' => 406,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ];
                return response()->json($data, 406);
            }
            $user = Auth::user();
            if (!($user->first_name && $user->last_name && $user->email && $user->phone && $user->address && $user->date_of_birth && $user->street && $user->country_id && $user->postal_code)) {
                $data = [
                    'status' => 406,
                    'message' => 'Please first complete your profile to withdraw',
                    'data' => []
                ];
                return response()->json($data, 406);
            }
            $previousCashouts = $user->cashouts()->where('status', 'paid')->count();
            if (isset(SiteSetting()['min_cashout_amount']) && $previousCashouts == 0) {
                $min = SiteSetting()['min_cashout_amount'];
            } else if (isset(SiteSetting()['next_cashout_amount']) && $previousCashouts > 0) {
                $min = SiteSetting()['next_cashout_amount'];
            } else if ($previousCashouts == 0) {
                $min = 1;
            } else {
                $min = 2;
            }
            $method = $user->paymentInfo()->where('payment_method', $request->payment_method)->first();
            if (!$method && $request->payment_method != 'charity') {
                $data = [
                    'status' => 406,
                    'message' => 'Payment method not found, please add your payment method information',
                    'data' => []
                ];
            }
            $balance = $user->availableBalance(3);
            $cashbacks = $user->balance;
            if ($balance < $min) {
                $data = [
                    'status' => 406,
                    'message' => "You have insufficient balance for withdrawl. You need to have at least $min in your balance for withdrawal.",
                    'data' => []
                ];
            }
            $cashoutStatuses = $user->cashouts()->pluck('status')->all();
            $availableBalance = $user->availableBalance(3);
            $minimumCashoutAmount = getMinimumCashoutAmount();
            if ($availableBalance < $minimumCashoutAmount || (in_array('pending', $cashoutStatuses) || in_array('processing donation', $cashoutStatuses))) {
                $data = [
                    'status' => 406,
                    'message' => 'You are not eligible to withdraw at the moment.',
                    'data' => []
                ];
                return response()->json($data, 406);
            }
            $cashout = Cashout::create([
                'user_id' => $user->id,
                'amount' => $balance,
                'cashout_type' => $method->payment_method,
                'paypal_email' => $method->paypal_email,
                'address' => $method->address,
                'city' => $method->city,
                'postcode' => $method->postcode,
                'country' => $method->country,
                'account_name' => $method->account_name,
                'bank_title' => $method->bank_title,
                'account_number' => $method->account_number,
                'bank_sort_code' => $method->bank_sort_code,
                'new_cashout' => '1',
                'bic' => $method->bic,
                'payment_method' => $method->payment_method,
                'status' => 'pending'
            ]);
            foreach ($cashbacks as $cashback) {
                $cashback->update(['status' => 5, 'cashout_id' => $cashout->id]);
                $change_status = CashbackStatusChange::create([
                    'user_cashback_id' => $cashback->id,
                    'cashback_status_id' => $cashback->status
                ]);
            }

            if ($user->bonus && $user->bonus->status == 'unpaid') {
                $user->bonus->update([
                    'status' => 'paid',
                    'cashout_id' => $cashout->id
                ]);
            }
            $data = [
                'status' => 200,
                'message' => "We're processing your withdrawal. Please allow 4 working days for " . $balance . " to reach your " . $request->payment_method . " account.",
                'data' => '',
            ];
            return response()->json($data, 200);
        } catch (\Exception $e) {
            $data = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }

    public function CharityCashout(Request $request, Cashout $cashout)
    {
        try {
            $user = Auth::user();
            $previousCashouts = $user->cashouts()->where('status', 'paid')->count();
            if (isset(SiteSetting()['min_cashout_amount']) && $previousCashouts == 0) {
                $min = SiteSetting()['min_cashout_amount'];
            } else if (isset(SiteSetting()['next_cashout_amount']) && $previousCashouts > 0) {
                $min = SiteSetting()['next_cashout_amount'];
            } else if ($previousCashouts == 0) {
                $min = 1;
            } else {
                $min = 2;
            }
            $cashoutStatus = $user->cashouts()->pluck('status')->all();
            $balanceOld = $user->availableBalance(3);
            $balance = $balanceOld - $request->amount;
            $minimumCashoutAmount = getMinimumCashoutAmount();
            if ($balanceOld < $min) {
                $data = [
                    'status' => 406,
                    'message' => "You have insufficient balance for withdrawl. You need to have at least $min in your balance for withdrawal.",
                    'data' => []
                ];
            }
            $validator = Validator::make($request->all(), [
                'charity_types_id' => 'required',
                'payment_method' => 'required',
                'amount' => 'required',
                'id' => 'required'
            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 406,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ];
                return response()->json($data, 406);
            }

            if ($balanceOld < $minimumCashoutAmount || (in_array('pending', $cashoutStatus) || in_array('processing donation', $cashoutStatus))) {
                $data = [
                    'status' => 406,
                    'message' => 'You are not eligible to withdraw at the moment.',
                    'data' => []
                ];
                return response()->json($data, 406);
            }

            $cashout = Cashout::create([
                'user_id' => $user->id,
                'charity_types_id' => $request->charity_types_id,
                'cashout_type' => $request->payment_method,
                'amount' => $request->amount,
                'new_cashout' => '1',
                'payment_method' => $request->payment_method,
                'status' => 'processing donation'
            ]);

            foreach ($request->id as $requestId) {
                $cashback = $user->cashbacks()->where('id', $requestId)->first();
                $cashback->update(['status' => 5, 'cashout_id' => $cashout->id]);

                $cashback->statusHistory()->create([
                    'cashback_status_id' => $cashback->status,
                    'user_cashback_id' => $cashback->id
                ]);
            }

            if ($user->bonus && $user->bonus->status == 'unpaid') {
                $user->bonus->update([
                    'status' => 'paid',
                    'cashout_id' => $cashout->id
                ]);
            }
            $data = [
                'status' => 200,
                'message' => "We're processing your withdrawal. Please allow 4 working days for " . $request->amount  . " to reach your " . $request->payment_method . " account.",
                'data' => '',
            ];
            return response()->json($data, 200);
        } catch (\Exception $e) {
            $data = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }

    public function charityTypesCashouts()
    {
        try {

            $user_id = auth()->user()->id;

            $usercashbacks = UserCashback::where('status', '3')
                ->where('user_id', $user_id)
                ->get();

            $usercashbacks_id = $usercashbacks->pluck('id')->toArray();

            $charityTypeData = CharityTypeResource::collection(CharityType::where('status', '1')->get());

            $data = [
                'status' => 200,
                'message' => 'Success',
                'data' => [
                    'status' => 200,
                    'message' => 'Successful',
                    'usercashbacks' => UserCashbackResource::collection($usercashbacks),
                    'charity_types' => $charityTypeData,
                ],
            ];
            return response()->json($data, 200);
        } catch (\Exception $e) {
            $data = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }
}
