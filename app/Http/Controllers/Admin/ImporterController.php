<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Store;
use App\Jobs\CjImporter;
use App\Models\Network;
use App\Models\Voucher;
use App\Models\ExitClick;
use App\Jobs\AwinImporter;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use App\Jobs\ImpactImporter;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use App\Models\StoreCashback;
use App\Jobs\WebgainsImporter;
use App\Models\ImporterSetting;
use App\Jobs\PartnerizeImporter;
use App\Models\ImportedCategory;
use App\Jobs\AfrofiliateImporter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\RevGlueImporter;
use App\Models\CashbackStatusChange;

class ImporterController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:run importer', ['only' => ['import']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin-dashboard.importer.index');
    }

    public function import(Request $request)
    {
        $settings = ImporterSetting::updateOrCreate([
            'network_id'   => $request->network_id,
        ], [
            'import_stores'     => $request->has('stores') ? 1 : 0,
            'import_vouchers'   => $request->has('vouchers') ? 1 : 0,
            'import_cashbacks'   => $request->has('cashback') ? 1 : 0,
            'last_import_at'   => Carbon::now()->toDateTimeString()
        ]);

        $importerOptions = [
            'stores' => isset($request->stores) ? 1 : 0,
            'vouchers' => isset($request->vouchers) ? 1 : 0,
            'cashback' => isset($request->cashback) ? 1 : 0
        ];
        if ($request->network_name == "CJ") {
            $importer = new CjImporter();
        } else if ($request->network_name == "Webgains") {
            $importer = new WebgainsImporter();
        } else if ($request->network_name == "Awin") {
            $importer = new AwinImporter();
        } else if ($request->network_name == 'Impact') {
            $importer = new ImpactImporter();
        } else if ($request->network_name == 'Partnerize') {
            $importer = new PartnerizeImporter();
        } else if ($request->network_name == 'Afrofiliate') {
            $importer = new AfrofiliateImporter();
        } else if ($request->network_name == 'RevGlue') {
            $importer = new RevGlueImporter();
        } else {
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'error' => 'Network not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        dispatch($importer);
    }

    public function show($id)
    {
        $importer = new CjImporter();
        dispatch($importer);
    }

    public function saveSettings(Request $request)
    {
        try {
            ImporterSetting::updateOrCreate([
                'network_id'   => $request->network_id,
            ], [
                'import_stores'    => $request->has('stores') ? 1 : 0,
                'import_vouchers'  => $request->has('vouchers') ? 1 : 0,
                'import_cashbacks' => $request->has('cashback') ? 1 : 0,
            ]);

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Settings saved'
            ], JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong, try again'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function importerSettingForm($id)
    {
        $network = Network::where('id', $id)->first();
        $settings = SiteSetting::latest()->get()->pluck('value', 'type');
        return view('admin-dashboard.networks.importer_setting_form', compact('network', 'settings'));
    }
}
