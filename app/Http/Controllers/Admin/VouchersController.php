<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Store;
use App\Models\Network;
use App\Models\Voucher;
use Illuminate\Http\Request;
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
        $stores = Store::latest()->get();
        $networks = Network::latest()->get();
        $vouchers = Voucher::latest()->paginate(30);

        return view('admin-dashboard.vouchers.index', compact('vouchers', 'stores', 'route', 'networks'));
    }

    public function create()
    {
        $stores = Store::latest()->get();
        return view('admin-dashboard.vouchers.edit-voucher', compact('stores'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'link_name' => 'required|max:255',
            'description' => 'nullable|max:255',
            'click_url' => 'required|url',
            'coupon_code' => $request->input('promotion_type') === 'Coupon' ? 'required' : '',
            'sale_commission' => 'required|numeric|min:0.1',
            'destination' => 'required|url',
            'promotion_type' => 'required',
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
            Voucher::create([
                'link_name' => $request->input('link_name'),
                'store_id' => $request->input('store_id'),
                'description' => $request->input('description'),
                'click_url' => $request->input('click_url'),
                'sale_commission' => $request->input('sale_commission'),
                'coupon_code' => $request->input('coupon_code'),
                'destination' => $request->input('destination'),
                'promotion_type' => $request->input('promotion_type'),
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
        $stores = Store::latest()->get();

        if ($request->input('store_editor')) {
            return view('admin-dashboard.vouchers.modal-edit', compact('voucher', 'stores'))->render();
        }

        return view('admin-dashboard.vouchers.edit-voucher', compact('voucher', 'stores'))->render();
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validator = Validator::make($request->all(), [
            'link_name' => 'required|max:255',
            'description' => 'nullable|max:255',
            'click_url' => 'required|url',
            'sale_commission' => 'required|numeric|min:0.1',
            'coupon_code' => $request->input('promotion_type') === 'Coupon' ? 'required' : '',
            'destination' => 'required|url',
            'promotion_type' => 'required',
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
                'link_name' => $request->input('link_name'),
                'store_id' => $storeId,
                'description' => $request->input('description'),
                'click_url' => $request->input('click_url'),
                'sale_commission' => $request->input('sale_commission'),
                'coupon_code' => $request->input('coupon_code'),
                'destination' => $request->input('destination'),
                'promotion_type' => $request->input('promotion_type'),
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
        $voucher->delete();
        flash()->success('Voucher deleted successfully');

        return redirect()->back();
    }

    function fetch(Request $request)
    {
        if ($request->ajax()) {
            $route = 'index';
            $vouchers = Voucher::latest()->paginate(30);

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
        $vouchers = $vouchers->newQuery();

        // Search by store.
        if ($request->input('store_id')) {
            $vouchers->where('store_id', $request->input('store_id'));
        }

        $vouchers = $vouchers->latest()->paginate(10);
        $route = 'search';

        return view('admin-dashboard.vouchers.index_data', compact('vouchers', 'route'))->render();
    }
}
