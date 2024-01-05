<?php

namespace App\Http\Controllers\Client;

use App\Models\Ticket;
use App\Models\ExitClick;
use Illuminate\Support\Str;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use App\Jobs\SendNotification;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::paginate(20);
        return view('frontend.client-dashboard.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clicks = auth()->user()->clicks()->whereHas('store')->get();
        return view('frontend.client-dashboard.tickets.create', compact('clicks'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::where('ticket_id', $id)->firstOrFail();
        return view('frontend.client-dashboard.tickets.show', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'product' => 'required|max:255',
        ], [
            'amount.required' => 'Amount is required',
            'amount.numeric' => 'Amount should be numeric',
            'product.required' => 'Product description is required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'error' => $validator->errors()->first(),
                    'input' => $request->all()
                ]);
        }

        try {
            $ticket->update([
                'claim_amount' => $request->input('amount'),
                'message' => $request->input('product'),

            ]);
            sendEmailNotification($ticket);

            $title = 'Ticket Created';
            $message = 'A new ticket has been created';
            $url = url(getAdminPrefix() . '/tickets') . '/' . $ticket->id;
            $admin = getAdminUser();
            $deviceToken = $admin->devices()->where('type', 'web')->latest()->first();

            $deviceToken != null ? dispatch(new SendNotification($title, $message, $deviceToken->fcm_token, $url, $admin)) : '';

            flash()->success("We've received your claim.<br> Please allow up to six months to get a decision from the retailer.");
            return redirect()->route('account.tickets.index');
        } catch (Exception $e) {
            flash()->error("Something went wrong, try again later.");
            return redirect()->route('account.tickets.index');
        }
    }

    public function step2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_id' => 'required',
        ], [
            'store_id.required' => 'Store name is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        $store_id = $request->input('store_id');
        $claim = $request->input('claim_type');
        $user = Auth::user();
        $clicks = $user->clicks->where('store_id', $store_id);

        if ($claim  == 'missing cashback') {
            return view('frontend.client-dashboard.tickets.ticket_step2', compact('store_id', 'claim', 'clicks'));
        }

        if ($claim == 'declined cashback') {
            $cashback =  UserCashback::where([
                'store_id' => 5,
                'user_id' => 22,
            ])->where('status', 2)->get();

            if (count($cashback)) {
                return view('frontend.client-dashboard.tickets.ticket_step2', compact('store_id', 'claim', 'clicks', 'cashback'));
            } else {
                flash()->error('We have no record of a declined transaction with this retailer.');
                return redirect()->back();
            }
        }

        if ($claim == 'incorrect amount') {
            $cashback =  UserCashback::where([
                'store_id' => $store_id,
                'user_id' => $user->id,
            ])->whereIn('status', [1, 4, 3])->get();

            if (count($cashback)) {
                return view('frontend.client-dashboard.tickets.ticket_step2', compact('store_id', 'claim', 'clicks', 'cashback'));
            } else {
                flash()->error('We have no record of a pending, confirmed or paid transaction with this retailer.');
                return redirect()->back();
            }
        }
    }

    public function step3(Request $request)
    {
        try {
            $routeMethod = $request->method();
            if ($routeMethod == 'GET') {
                $amount = old('amount');
                $product = old('product');
                $click_id = old('click_id');
            } else {
                $validator = Validator::make($request->all(), [
                    'click_id' => 'required',
                ], [
                    'click_id.required' => 'This field is required',
                ]);
                if ($validator->fails()) {
                    return redirect()->route('account.tickets.step2')
                        ->withErrors($validator)
                        ->withInput()
                        ->with('error',  $validator->errors()->first());
                }
                $click_id = $request->input('click_id');
            }

            $click = ExitClick::where('id', $click_id)->first();
            $claim_type = $request->input('claim_type');
            $message = 'l';

            if ($routeMethod != 'GET') {
                $claim = new Ticket;
                $claim->store_id = $click->store_id;
                $claim->user_id = $click->user_id;
                $claim->click_id = $click->id;
                $claim->ticket_id = strtoupper(Str::random(12));
                $claim->cashback_id = $click->cashback->id ?? NULL;
                $claim->claim_amount = $click->cashback->order_value ?? NULL;
                $claim->claim_type = $claim_type;
                $claim->title = 'Claim: ' . $claim_type;
                if ($claim_type == 'missing cashback') {
                    $claim->category_id = '1';
                } else if ($claim_type == 'declined cashback') {
                    $claim->category_id = '2';
                } else if ($claim_type == 'incorrect amount') {
                    $claim->category_id = '3';
                }
                $claim->ticket_type = 'claim';
                $claim->status = 'open';
                $claim->save();
            } else {
                $claim = Ticket::where([
                    'store_id' => $click->store_id,
                    'user_id' => $click->user_id,
                    'click_id' => $click->id,
                ])->first();
            }

            if ($claim_type == 'incorrect amount' || $claim_type == 'declined cashback') {
                sendEmailNotification($claim);

                $title = 'Ticket Created';
                $message = 'A new ticket has been created';
                $url = url(getAdminPrefix() . '/tickets') . '/' . $claim->id;
                $admin = getAdminUser();
                $deviceToken = $admin->devices()->where('type', 'web')->latest()->first();

                $deviceToken != null ? dispatch(new SendNotification($title, $message, $deviceToken->fcm_token, $url, $admin)) : '';
                flash()->success("We've received your claim.<br> Please allow up to six months to get a decision from the retailer.");
                return redirect()->route('account.tickets.index');
            }

            return view('frontend.client-dashboard.tickets.ticket_step3', compact('claim', 'click_id'));
        } catch (Exception $e) {
            return redirect()->route('account.tickets.step2')
                ->withInput();
        }
    }
}
