<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Store;
use App\Models\Network;


class VouchersController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:view vouchers', ['only' => ['index','show']]);
         $this->middleware('permission:edit vouchers', ['only' => ['edit','update']]);
         $this->middleware('permission:add vouchers', ['only' => ['create','Store']]);
         $this->middleware('permission:delete vouchers', ['only' => ['destroy']]);
         
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route='index';
        $stores = Store::latest()->get();
        $networks = Network::latest()->get();
        $vouchers = Voucher::latest()->paginate(30);
        return view('admin-dashboard.vouchers.index', compact('vouchers','stores','route','networks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stores = Store::latest()->get();
        return view('admin-dashboard.vouchers.create', compact('stores'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
        $inputs= $request->all();
        $inputs['promotion_start_date'] = \Carbon\Carbon::parse($request->input('promotion_start_date'))->format('Y-m-d H:i:s');
        $inputs['promotion_end_date'] = \Carbon\Carbon::parse($request->input('promotion_end_date'))->format('Y-m-d H:i:s');
        
        $voucher = Voucher::create($inputs);

        flash()->success('Voucher added successfully');
        return redirect()->route('admin.vouchers.index');
        }catch (\Throwable $th) {
            flash()->error('something went wrong! unable to add the voucher');
            return redirect()->route('admin.vouchers.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Voucher $voucher )
    {
        $stores = Store::latest()->get();

        if($request->input('store_editor')){
            return view('admin-dashboard.vouchers.modal-edit', compact('voucher','stores'))->render();

        }else{
            return view('admin-dashboard.vouchers.edit-voucher', compact('voucher','stores'))->render();


        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Voucher $voucher)
    {
        try{
            $inputs= $request->all();

            $inputs['promotion_start_date'] = \Carbon\Carbon::parse($request->input('promotion_start_date'))->format('Y-m-d H:i:s');
            $inputs['promotion_end_date'] = \Carbon\Carbon::parse($request->input('promotion_end_date'))->format('Y-m-d H:i:s');
            
            $voucher->update($inputs);
            if(!$request->ajax())
            { 
                 flash()->success('Voucher updated successfully');
                return redirect()->route('admin.vouchers.index');

            }

            return true;
    
          
        
        } catch (\Throwable $th) {
            flash()->error('Something went wrong! unable to update the voucher');
            return redirect()->route('admin.vouchers.index');
        }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        flash()->success('Voucher deleted successfully');
        return redirect()->back();
    }
    function fetch(Request $request)
    {
        if($request->ajax())
        {
            $route='index';
            $vouchers = Voucher::latest()->paginate(30);

            return view('admin-dashboard.vouchers.index_data', compact('vouchers','route'))->render();
        }
    }
    public function exportCsv(Request $request)
    {
        try {
            
            $table = Voucher::latest()->get();
            $filename = "Vouchers.csv";
            $handle = fopen($filename, 'w+');
            fputcsv($handle, array('Title','store','description','sale commission','click url','destination','link id','link type','coupon code','promotion type','promotion start date','promotion end date'));

            foreach($table as $row) {
                fputcsv($handle, array($row->link_name, $row->store->name, $row->description, $row->sale_commission ?? 'NA', $row->click_url ?? "#",$row->destination ?? "#", $row->link_id ?? "", $row->link_type ?? "",$row->coupon_code ?? "",$row->promotion_type ?? "",$row->promotion_start_date ?? "", $row->promotion_end_date ?? ""));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

            return \Response::download($filename, 'vouchers.csv', $headers);

        } catch (\Throwable $th) {

            flash()->error('Error while exporting the vouchers');
            return redirect()->route('admin.stores.index');

        }
    }
    public function searchVouchers(Request $request, voucher $vouchers)
    {
        // dd($request->all());
        $vouchers = $vouchers->newQuery();

        // Search by network.
        // if ($request->input('network_id')) {
        //     $vouchers->where('network_id', $request->input('network_id'));
        // }

        // Search by store.
        if ($request->input('store_id')) {
            $vouchers->where('store_id',$request->input('store_id'));
           
        }

        
        $vouchers = $vouchers->latest()->paginate(10);
        $route='search';
        return view('admin-dashboard.vouchers.index_data', compact('vouchers','route'))->render();
    }
}
