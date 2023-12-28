<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Store;
use App\Models\Network;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class VouchersController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view vouchers', ['only' => ['index', 'show']]);
        $this->middleware('permission:edit vouchers', ['only' => ['edit', 'update']]);
        $this->middleware('permission:add vouchers', ['only' => ['create', 'Store']]);
        $this->middleware('permission:delete vouchers', ['only' => ['destroy']]);
    }

    public function index()
    {
        $route = 'index';
        $stores = Store::select('id', 'name', 'slug', 'status', 'created_at')->latest()->get();
        $networks = Network::latest()->get();
        $vouchers = Voucher::whereHas('store')->with(['store' => function ($query) {
            $query->select('id', 'name', 'slug', 'status', 'created_at')->with('network');
        }])->latest()->paginate(30);

        return view('admin-dashboard.vouchers.index', compact('vouchers', 'stores', 'route', 'networks'));
    }

    public function create()
    {
        $stores = Store::select('id', 'name', 'slug', 'status', 'created_at')->where('status', 'active')->latest()->get();
        return view('admin-dashboard.vouchers.edit-voucher', compact('stores'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'store_id' => 'required|integer',
            'tracking_url' => ['nullable', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            'deeplink_url' => ['nullable', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            'description' => 'nullable|max:255',
            'promotion_type' => 'required|string',
            'coupon_code' => [
                Rule::requiredIf(function () use ($request){
                    return $request->promotion_type === "Coupon";
            }),
            'nullable', 'alpha_num', 'min:3', 'max:20'
            ],
            'promotion_start_date' => 'required|date',
            'promotion_end_date' => 'required|after:promotion_start_date',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return array(
                    'message' => $validator->errors()->first(),
                    'success' => false
                );
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Voucher::create([
                'name' => $request->input('name'),
                'store_id' => $request->input('store_id'),
                'tracking_url' => $request->input('tracking_url'),
                'deeplink_url' => $request->input('deeplink_url'),
                'description' => $request->input('description'),
                'promotion_type' => $request->input('promotion_type'),
                'coupon_code' => $request->input('coupon_code'),
                'promotion_start_date' => \Carbon\Carbon::parse($request->input('promotion_start_date'))->format('Y-m-d'),
                'promotion_end_date' => \Carbon\Carbon::parse($request->input('promotion_end_date'))->format('Y-m-d'),
            ]);

            if ($request->ajax()) {
                return array(
                    'message' => 'Voucher added successfully.',
                    'success' => true
                );
            }

            flash()->success('Voucher added successfully.');
            return redirect()->route(getAdminPrefix() . '.vouchers.index');
        } catch (Exception $e) {
            $message = 'Something went wrong! Unable to add the voucher.';

            if ($request->ajax()) {
                return array(
                    'message' => $message,
                    'success' => false
                );
            }
            flash()->error($message);
            return redirect()->route(getAdminPrefix() . '.vouchers.index');
        }
    }

    public function edit(Request $request, Voucher $voucher)
    {
        $stores = Store::select('id', 'name', 'slug', 'status', 'created_at')->latest()->get();

        if ($request->input('store_editor')) {
            return view('admin-dashboard.vouchers.modal-edit', compact('voucher', 'stores'))->render();
        }

        return view('admin-dashboard.vouchers.edit-voucher', compact('voucher', 'stores'))->render();
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'store_id' => 'required|integer',
            'tracking_url' => ['nullable', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            'deeplink_url' => ['nullable', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            'description' => 'nullable|max:255',
            'promotion_type' => 'required|string',
            'coupon_code' => [
                Rule::requiredIf(function () use ($request){
                    return $request->promotion_type === "Coupon";
            }),
            'nullable', 'alpha_num', 'min:3', 'max:20'
            ],
            'promotion_start_date' => 'required',
            'promotion_end_date' => 'required|after:promotion_start_date',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return array(
                    'message' => $validator->errors()->first(),
                    'success' => false
                );
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $storeId = $request->input('store_id') ? $request->input('store_id') : $voucher->store_id;
            $voucher->update([
                'name' => $request->input('name'),
                'store_id' => $storeId,
                'tracking_url' => $request->input('tracking_url'),
                'deeplink_url' => $request->input('deeplink_url'),
                'description' => $request->input('description'),
                'promotion_type' => $request->input('promotion_type'),
                'coupon_code' => $request->input('coupon_code'),
                'promotion_start_date' => \Carbon\Carbon::parse($request->input('promotion_start_date'))->format('Y-m-d'),
                'promotion_end_date' => \Carbon\Carbon::parse($request->input('promotion_end_date'))->format('Y-m-d'),
            ]);

            if ($request->ajax()) {
                return array(
                    'message' => 'Voucher updated successfully.',
                    'success' => true
                );
            }
            flash()->success('Voucher updated successfully.');
            return redirect()->route(getAdminPrefix() . '.vouchers.index');
        } catch (Exception $e) {
            $message = 'Something went wrong! Unable to update the voucher.';

            if ($request->ajax()) {
                return array(
                    'message' => $message,
                    'success' => false
                );
            }

            flash()->error($message);
            return redirect()->route(getAdminPrefix() . '.vouchers.index');
        }
    }

    public function destroy(Voucher $voucher)
    {
        try{
            DB::beginTransaction();
            $voucher->delete();
            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Voucher deleted successfully'
            ], JsonResponse::HTTP_OK);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Voucher deleted successfully'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    function fetch(Request $request)
    {
        if ($request->ajax()) {
            $route = 'index';
            $vouchers = Voucher::whereHas('store')->with(['store' => function ($query) {
                $query->select('id', 'name', 'slug', 'status', 'created_at')->with('network');
            }])->latest()->paginate(30);

            return view('admin-dashboard.vouchers.index_data', compact('vouchers', 'route'))->render();
        }
    }

    public function exportCsv(Request $request)
    {
        try {
            $table = Voucher::latest()->get();
            $filename = "Vouchers.csv";
            $handle = fopen($filename, 'w+');

            fputcsv($handle, array(
                'Title',
                'store',
                'description',
                'sale commission',
                'click url',
                'destination',
                'link id',
                'link type',
                'coupon code',
                'promotion type',
                'promotion start date',
                'promotion end date'
            ));

            foreach ($table as $row) {
                fputcsv($handle, array(
                    $row->link_name,
                    $row->store->name,
                    $row->description,
                    $row->sale_commission ?? 'NA',
                    $row->click_url ?? "#",
                    $row->destination ?? "#",
                    $row->link_id ?? "",
                    $row->link_type ?? "",
                    $row->coupon_code ?? "",
                    $row->promotion_type ?? "",
                    $row->promotion_start_date ?? "",
                    $row->promotion_end_date ?? ""
                ));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

            return Response::download($filename, 'vouchers.csv', $headers);
        } catch (\Throwable $th) {
            flash()->error('Error while exporting the vouchers');
            return redirect()->route(getAdminPrefix() . '.stores.index');
        }
    }

    public function searchVouchers(Request $request, voucher $vouchers)
    {
        $vouchers = $vouchers->whereHas('store')->with(['store' => function ($query) {
            $query->select('id', 'name', 'slug', 'status', 'created_at')->with('network');
        }])->newQuery();

        // Search by store.
        if ($request->input('store_id')) {
            $vouchers->where('store_id', $request->input('store_id'));
        }

        $vouchers = $vouchers->latest()->paginate(30);
        $route = 'search';

        return view('admin-dashboard.vouchers.index_data', compact('vouchers', 'route'))->render();
    }
}
