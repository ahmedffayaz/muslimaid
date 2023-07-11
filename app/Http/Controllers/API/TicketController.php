<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Store;
use App\Models\Ticket;
use App\Models\ExitClick;
use Illuminate\Support\Str;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function createTicket(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'store_id' => 'required',
                'claim_type' => 'required|in:missing cashback,declined cashback,incorrect amount',
                'amount' => 'required_if:claim_type,missing cashback|integer',
                'product' => 'required_if:claim_type,missing cashback|max:255',
                'click_id' => 'required',
            ], [
                'amount.required' => 'Amount is required',
                'amount.integer' => 'Amount should be an integer',
                'product.required' => 'Product description is required',
                'store_id.required' => 'Store name is required',
                'claim_type.required' => 'Claim type is required',
                'claim_type.in' => 'Invalid claim type',
                'click_id' => 'click_id field is required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => $validator->errors()->first(),
                    'data' => null,
                ], 400);
            }

            $claimType = $request->input('claim_type');
            $user = Auth::user();

            if ($claimType == 'missing cashback') {
                $store_id = $request->input('store_id');

                $clicks = $user->clicks->where('store_id', $store_id);
            } elseif ($claimType == 'declined cashback' || $claimType == 'incorrect amount') {
                $store_id = $request->input('store_id');
                $cashbackQuery = UserCashback::where('store_id', $store_id)
                    ->where('user_id', $user->id);

                if ($claimType == 'declined cashback') {
                    $click_id = $request->input('click_id');
                    $click = ExitClick::where('id', $click_id)->first();
                    $cashbackQuery->where('status', 2);
                } elseif ($claimType == 'incorrect amount') {
                    $click_id = $request->input('click_id');
                    $click = ExitClick::where('id', $click_id)->first();
                    $cashbackQuery->whereIn('status', [1, 3, 4]);
                }
                $cashback = $cashbackQuery->get();
                if (empty($cashback)) {
                    return response()->json([
                        'status' => 400,
                        'message' => $claimType == 'declined cashback' ? 'We have no record of a declined transaction with this retailer.' : 'We have no record of a pending, confirmed, or paid transaction with this retailer.',
                        'data' => null,
                    ], 400);
                }
            }
            $click = ExitClick::where('id', $click_id)->first();
            $ticket = new Ticket;
            $ticket->store_id = $request->input('store_id');
            $ticket->user_id = $user->id;
            $ticket->click_id = $request->input('click_id');
            $ticket->ticket_id = strtoupper(Str::random(12));
            $ticket->cashback_id = $click->cashback->id ?? null;
            $ticket->cashback_id = null;
            $ticket->claim_amount = $request->input('amount');
            $ticket->claim_type = $claimType;
            $ticket->title = 'Claim: ' . $claimType;
            $ticket->category_id = $claimType == 'missing cashback' ? 1 : ($claimType == 'declined cashback' ? 2 : 3);
            $ticket->ticket_type = 'claim';
            $ticket->status = 'open';
            $ticket->save();
            if ($claimType == 'incorrect amount' || $claimType == 'declined cashback') {
                return response()->json([
                    'status' => 200,
                    'message' => "We've received your claim. Please allow up to six months to get a decision from the retailer.",
                    'data' => null,
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => "We've received your claim. Please allow up to six months to get a decision from the retailer.",
                'data' => null,
            ]);
        } catch (\Exception $e) {
            $data = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }

    //  on first page of tickets, getting exit clicks of stores  and get unique stores,  this function works that 
    public function TicketStores()
    {
        try {
            $clicks = auth()->user()->clicks()->whereHas('store')->pluck('store_id')->unique();
            $clicksWithNames = [];
            
            foreach ($clicks as $click) {
                $store = Store::find($click);
                if ($store) {
                    $clicksWithNames[] = [
                        'click_store_id' => $click,
                        'store_name' => $store->name
                    ];
                }
            }
            
            return response()->json([
                'status' => 200,
                'message' => "User click Stores retrieved successfully.",
                'data' => $clicksWithNames,
            ]);
        } catch (\Exception $e) {
            $data = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }

    //on second page of ticket, clicks display with basis of claim_type , this function works with these conditions

    public function TicketClicks(Request $request)
    {
        try {
            $store_id = $request->input('store_id');
            $user = Auth::user();
            $clicks = $user->clicks()->where('store_id', $store_id)->get();
            $claim = $request->input('claim_type');
            if ($claim  == 'missing cashback') {
                if ($clicks->count() <= 0) {
                    $response = [
                        'status' => 404,
                        'message' => 'Cashbacks not found',
                        'data' => []
                    ];
                    return response()->json($response, 404);
                }
                $data = [];
                foreach ($clicks as $click) {
                    $data[] = [
                        'click_id' => $click->id,
                        'created_at' => Carbon::parse($click->created_at)->isoFormat('Do MMMM YYYY hh:mm:ss')
                    ];
                }
                return response()->json([
                    'status' => 200,
                    'message' => "User click  retrieved successfully.",
                    'data' => $data,
                ]);
            }
            if ($claim == 'declined cashback') {
                $cashbacks =  UserCashback::where([
                    'store_id' => $store_id,
                    'user_id' => $user->id,
                ])->where('status', 2)->get();
                if ($cashbacks->count() <= 0) {
                    $response = [
                        'status' => 404,
                        'message' => 'Cashbacks not found',
                        'data' => []
                    ];
                    return response()->json($response, 404);
                }
                $data = [];
                foreach ($cashbacks as $cashback) {
                    $data[] = [
                        'click_id' => $cashback->exit_click_id,
                        'created_at' => Carbon::parse($cashback->event_date)->isoFormat('Do MMMM YYYY'),
                        'order_value' => currency($cashback->order_value),
                        'amount' => currency($cashback->amount),
                    ];
                }
                return response()->json([
                    'status' => 200,
                    'message' => "User click  retrieved successfully.",
                    'data' => $data,
                ]);
            }
            if ($claim == 'incorrect amount') {
                $cashbacks =  UserCashback::where([
                    'store_id' => $store_id,
                    'user_id' => $user->id,
                ])->whereIn('status', [1, 4, 3])->get();
                if ($cashbacks->count() <= 0) {
                    $response = [
                        'status' => 404,
                        'message' => 'Cashbacks not found',
                        'data' => []
                    ];
                    return response()->json($response, 404);
                }
                $data = [];
                foreach ($cashbacks as $cashback) {
                    $data[] = [
                        'click_id' => $cashback->exit_click_id,
                        'created_at' => Carbon::parse($cashback->event_date)->isoFormat('Do MMMM YYYY'),
                        'order_value' => currency($cashback->order_value),
                        'amount' => currency($cashback->amount),
                    ];
                }
                return response()->json([
                    'status' => 200,
                    'message' => "User click  retrieved successfully.",
                    'data' => $data,
                ]);
            }
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
