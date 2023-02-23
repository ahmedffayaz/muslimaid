<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Store;
use App\Models\Slider;
use App\Models\Network;
use App\Models\Category;
use App\Models\Currency;
use App\Models\EditorPick;
use App\Models\StoreImage;
use App\Models\StoreReview;
use Illuminate\Support\Str;
use App\Models\StoreAddress;
use App\Models\StoreSeoData;
use Illuminate\Http\Request;
use App\Models\StoreCashback;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    private $iconPath = 'stores/cashbacks/';
    public function __construct()
    {
        $this->middleware('permission:view stores', ['only' => ['index']]);
        $this->middleware('permission:edit stores', ['only' => ['edit', 'show', 'update']]);
        $this->middleware('permission:add stores', ['only' => ['create', 'Store']]);
        $this->middleware('permission:delete stores', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route = 'index';
        $networks = Network::all();
        $stores = Store::orderBy('id', 'DESC')->paginate(48);
        $slider = Slider::where('name', 'Home')->first();
        return view('admin-dashboard.stores.index', compact('stores', 'route', 'networks', 'slider'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $networks = Network::all();
        $categories = Category::all();
        return view('admin-dashboard.stores.create', compact('networks', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_name' => 'required|max:255',
            'network_id' => 'required',
            'tracking_url' => 'required|url',
            'deeplink_url' => 'nullable|url',
            'store_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $store = Store::create([
                'name' => $request->input('store_name'),
                'network_id' => $request->input('network_id'),
                'tracking_url' => $request->input('tracking_url'),
                'deeplink_url' => $request->input('deeplink_url'),
                'store_url' => $request->input('store_url'),
                'description' => $request->input('description'),
                'status' => 'active',
                'override_cashback' => 1,
                'slug' => Str::slug($request->input('store_name')),
                'is_api' => 'no',
            ]);
            DB::commit();

            flash()->success('New store added');
            return redirect()->route('admin.stores.show_store', 'slug=' . $store->slug);
        } catch (Exception $e) {
            DB::rollBack();
            flash()->error('Error while adding new store');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        $networks = Network::all();
        $categories = Category::where('parent_id', 0)->get();
        $stores = Store::latest()->get();
        return view('admin-dashboard.stores.show', compact('networks', 'categories', 'store', 'stores'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        $logo = StoreImage::where(['store_id' => $store->id, 'title' => 'logo'])->first();
        if (!$logo) {
            $logoUrl = asset('admin-dashboard/images/avatar/default-logo.png');
        } else {
            $logoUrl = asset('storage/stores/images/' . $logo->image);
        }
        $networks = Network::all();
        $categories = Category::all();
        return view('admin-dashboard.stores.edit', compact('store', 'networks', 'categories', 'logoUrl'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        $request->validate([
            'store_name' => 'required|max:255',
            'network_id' => 'required',
            'tracking_url' => 'required|url',
            'deeplink_url' => 'nullable|url',
            'store_url' => 'required|url',
        ]);

        try {
            DB::beginTransaction();
            $store->update([
                'name' => $request->input('store_name'),
                'override_network' => $request->has('store_override_network') ? 1 : 0,
                'network_id' => $request->input('network_id'),
                'tracking_url' => $request->input('tracking_url'),
                'deeplink_url' => $request->input('deeplink_url'),
                'store_url' => $request->input('store_url'),
                'description' => $request->input('description'),
                'extra_info' => $request->input('extra_info'),
                'terms_conditions' => $request->input('terms_conditions'),
                'custom_cashback_percentage' => $request->input('custom_cashback_percentage'),
                'status' => $request->input('status'),
                'slug' => Str::slug($request->input('store_name')),
                'override_categories' => $request->has('override_categories') ? 1 : 0,
                'override_cashback' => $request->has('override_cashback') ? 1 : 0,
                'feature_homepage' => 0,
                'feature_sidebar' => 0,
                'editor_pick' => 0,
            ]);

            if ($request->has('tags')) {
                foreach ($request->input('tags') as $tag) {
                    $store->update([
                        $tag => 1,
                    ]);
                }
            }
            DB::commit();

            if (!$request->ajax()) {
                flash()->success('Store info updated successfully');
                return redirect()->back();
            }
            return true;
        } catch (ModelNotFoundException $e) {
            if (!$request->ajax()) {
                flash()->error('Error while updating store');
                return redirect()->back();
            }
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'error' => 'Error while updating store'
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            DB::rollBack();
            if (!$request->ajax()) {
                flash()->error('Error while updating store');
                return redirect()->back();
            }
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $e->getMessage() . 'Error while updating store'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        $store->reviews()->delete();
        $store->vouchers()->delete();
        $store->cashbacks()->delete();
        $store->delete();
        flash()->success('store delete successfully');
        return redirect()->back();
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            $route = 'index';
            $stores = Store::orderBy('id', 'DESC')->paginate(48);
            return view('admin-dashboard.stores.index_data', compact('stores', 'route'))->render();
        }
    }

    public function exportCsv(Request $request)
    {
        try {
            $table = Store::latest()->get();
            $filename = "stores.csv";
            $handle = fopen($filename, 'w+');
            fputcsv($handle, array('Name', 'slug', 'Network', 'Cashback', 'Tracking Url', 'Store Url', 'Description', 'Terms & Conditions', 'Extra Info', 'Status'));
            foreach ($table as $row) {
                fputcsv($handle, array($row->name, $row->network->name, $row->network->slug, $row->cashback->sale_commission ?? 'NA', $row->cashback->click_url ?? "#", $row['store_url'], strip_tags($row['description']), $row['terms_conditions'], $row['extra_info'], $row['status']));
            }
            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download($filename, 'stores.csv', $headers);
        } catch (\Throwable $th) {
            flash()->error('Error while exporting the stores');
            return redirect()->route('admin.stores.index');
        }
    }

    public function storeImages(Store $store)
    {
        return view('admin-dashboard.stores.images', compact('store'));
    }

    public function uploadImage(Request $request, Store $store)
    {
        $validation = $request->validate([
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:2048',
        ]);
        if ($request->has('image')) {
            $img_exist = StoreImage::where(['store_id' => $store->id, 'title' => $request->title])->first();
            $imageName = Str::slug($store->name) . '_' . $request->title . '_' . time() . '.' . $request->image->extension();
            $request->image->storeAs('public/stores/images', $imageName);

            if ($img_exist) {
                $img_exist->update([

                    'title' => $request->title,
                    'image' => $imageName,
                    'is_uploaded' => 1,
                    'is_fake' => 0,

                ]);

                return array(
                    'message' => 'Image uploaded successfully',
                    'updated' => 'success'
                );
            }
            $imageName = Str::slug($store->name) . '_' . $request->title . '_' . time() . '.' . $request->image->extension();
            $request->image->storeAs('public/stores/images', $imageName);

            $logo = StoreImage::create([
                'store_id' => $store->id,
                'title' => $request->title,
                'image' => $imageName,
                'image_type' => 'store_logo',
                'is_uploaded' => 1,

            ]);

            return array(
                'message' => 'Image uploaded successfully',
                'updated' => 'success'
            );
        } else {

            return array(
                'message' => 'Image is required',
                'updated' => 'error'
            );
        }
    }

    public function deleteImage(StoreImage $storeimage)
    {
        $store = Store::where('id', $storeimage->store_id)->first();
        Storage::delete(['public/stores/images/' . $storeimage->image]);
        $storeimage->delete();
        flash()->success('Image deleted');
        return redirect()->back();
    }

    public function searchStores(Request $request, Store $stores)
    {
        $stores = $stores->newQuery();

        // Search by network.
        if ($request->input('network_id')) {
            $stores->where('network_id', $request->input('network_id'));
        }
        // Search by id.
        if ($request->input('store')) {
            $stores->where('id', $request->input('store'))->orWhere('name', 'like', '%' . $request->input('store') . '%');
        }

        // Search by store name.
        if ($request->input('store_name')) {
            $stores->where('name', 'like', '%' . $request->input('store_name') . '%');
        }
        // Search by store name.
        if ($request->input('overridden')) {
            $stores->where('override_cashback', 1)->orWhere('override_categories', 1);
        }

        // Search by status.
        if ($request->input('status') != -1) {
            $stores->where('status', $request->input('status'));
        }

        $stores = $stores->orderBy('id', 'DESC')->paginate(48);
        $route = 'search';
        return view('admin-dashboard.stores.index_data', compact('stores', 'route'))->render();
    }

    public function fetchVouchers(Request $request)
    {
        if ($request->ajax()) {
            $store = Store::where('id', $request->store)->first();
            return view('admin-dashboard.stores.vouchers', compact('store'))->render();
        }
    }

    public function fetchCashbacks(Request $request)
    {
        if ($request->ajax()) {
            $store = Store::where('id', $request->store)->first();
            return view('admin-dashboard.stores.cashbacks', compact('store'))->render();
        }
    }

    public function fetchReviews(Request $request)
    {
        if ($request->ajax()) {
            $store = Store::where('id', $request->store)->first();
            return view('admin-dashboard.stores.reviews', compact('store'))->render();
        }
    }

    public function editReview(Request $request, StoreReview $review)
    {
        return view('admin-dashboard.stores.review-form', compact('review'))->render();
    }

    public function editCashback(Request $request, StoreCashback $cashback)
    {
        $networks = Network::all();
        $currencies = Currency::all();
        return view('admin-dashboard.stores.cashback-edit', compact('cashback', 'currencies', 'networks'))->render();
    }

    public function updateCashback(Request $request, StoreCashback $cashback)
    {
        $request->validate([
            'type' => 'required',
            'sale_commission' => 'required|numeric|min:0',
            'network_id' => 'nullable|integer',
            'tracking_url' => 'nullable|url',
            'deeplink_url' => 'nullable|url',
            'cashback_icon' => 'nullable|mimes:png,jpg,jpeg|max:2048'
        ]);

        try {
            DB::beginTransaction();
            $cashback->update($request->all());

            if ($request->hasFile('cashback_icon')) {
                $cashbackIcon = saveResizeImage($request->file('cashback_icon'), $this->iconPath, 200);
                $cashback->image = $cashbackIcon;
                $cashback->update();
            }

            DB::commit();
            return true;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            if (!$request->ajax()) {
                flash()->error('Error while updating cashback.');
                return redirect()->back();
            }
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'error' => 'Error while updating cashback.'
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            DB::rollBack();
            if (!$request->ajax()) {
                flash()->error('Error while updating cashback.');
                return redirect()->back();
            }
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Error while updating cashback.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createCashback(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'sale_commission' => 'required|numeric|min:0',
            'deeplink_url' => 'nullable|url',
            'cashback_icon' => 'nullable|mimes:png,jpg,jpeg|max:2048'
        ]);

        try {
            DB::beginTransaction();
            $request->merge(['is_api' => 'no']);
            $cashback = StoreCashback::create($request->all());

            if ($request->hasFile('cashback_icon')) {
                $cashbackIcon = saveResizeImage($request->file('cashback_icon'), $this->iconPath, 200);
                $cashback->image = $cashbackIcon;
                $cashback->update();
            }

            $existing_cashbacks = StoreCashback::where('store_id', $request->store_id)->get();
            if (count($existing_cashbacks) == 1) {
                $cashback->update(['default' => '1']);
            }
            DB::commit();
            return true;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            if (!$request->ajax()) {
                flash()->error('Error while creating cashback.');
                return redirect()->back();
            }
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'error' => 'Error while creating cashback.'
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            DB::rollBack();
            if (!$request->ajax()) {
                flash()->error('Error while creating cashback.');
                return redirect()->back();
            }
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Error while creating cashback.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchImages(Request $request)
    {
        if ($request->ajax()) {
            $store = Store::where('id', $request->store)->first();
            return view('admin-dashboard.stores.images-data', compact('store'))->render();
        }
    }

    public function updateCategories(Request $request)
    {
        DB::table('category_store')->where('store_id', $request->input('store_id'))->delete();
        if ($request->input('category_id') != null) {
            foreach ($request->input('category_id') as $category) {
                DB::table('category_store')->insert([
                    'store_id' => $request->input('store_id'),
                    'category_id' => $category,
                ]);
            }
        }
    }

    public function editorPicks()
    {
        $route = 'index';
        $networks = Network::all();
        $stores = Store::latest()->get();
        $categories = Category::where('parent_id', 0)->get();
        $picks = Store::has('editorPicks')->latest()->paginate(32);

        return view('admin-dashboard.stores.editor_picks', compact('picks', 'route', 'networks', 'stores', 'categories'));
    }

    public function fetchEditorPicks(Request $request)
    {
        if ($request->ajax()) {
            $route = 'index';
            $picks = Store::has('editorPicks')->latest()->paginate(32);
            return view('admin-dashboard.stores.picks_data', compact('picks', 'route'))->render();
        }
    }

    public function searchEditorPicks(Request $request, Store $picks)
    {
        $picks = $picks->newQuery();

        // Search by network.
        if ($request->input('network_id')) {
            $picks->where('network_id', $request->input('network_id'));
        }
        // Search by id.
        if ($request->input('store_id')) {
            $picks->where('id', $request->input('store_id'));
        }

        // Search by store name.
        if ($request->input('store_name')) {
            $picks->where('name', 'like', '%' . $request->input('store_name') . '%');
        }

        // Search by status.
        if ($request->input('status') != -1) {
            $picks->where('status', $request->input('status'));
        }

        $picks = $picks->has('editorPicks')->latest()->paginate(32);
        $route = 'search';
        return view('admin-dashboard.stores.picks_data', compact('picks', 'route'))->render();
    }

    public function createEditorPick(Request $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->input('picks') as $pick) {
                EditorPick::firstOrCreate(
                    [
                        'category_id' => $request->input('category_id'),
                        'store_id' => $pick,
                    ],
                    [
                        'category_id' => $request->input('category_id'),
                        'store_id' => $pick,
                    ]
                );
            }
            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'Category updated'
            ], JsonResponse::HTTP_OK);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $exception->getMessage() . 'Error while updating the category'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function overrideCategories(Request $request, Store $store)
    {
        try {
            $store->update([
                'override_categories' => $request->has('override_categories') ? 1 : 0,
            ]);
            if (!$request->ajax()) {
                flash()->success('Store info updated successfully');
                return redirect()->back();
            } else {
                return true;
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function overrideCashback(Request $request, Store $store)
    {
        try {
            $store->update([
                'override_cashback' => $request->has('override_cashback') ? 1 : 0,
            ]);
            if (!$request->ajax()) {
                flash()->success('Store info updated successfully');
                return redirect()->back();
            } else {
                return true;
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function showStore()
    {
        return view('admin-dashboard.stores.show');
    }

    public function fetchAddress(Request $request)
    {
        if ($request->ajax()) {
            $store = Store::with('storeAddress')->where('id', $request->store)->first();
            return view('admin-dashboard.stores.store_address', compact('store'))->render();
        }
    }

    public function fetchSeoRules(Request $request)
    {
        if ($request->ajax()) {
            $store = Store::with('storeRuleData')->where('id', $request->store)->first();
            return view('admin-dashboard.stores.store_seo_rule', compact('store'))->render();
        }
    }

    public function storeSeoRule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $url = url('/');
            $store = Store::whereId($request->input('store_id'))->first();

            $url = $url . '/cashback/' . $store['slug'];
            $store = StoreSeoData::create([
                'store_id' => $request->input('store_id'),
                'url' => $url,
                'type' => 'meta',
                'key' => $request->input('key'),
                'value' => $request->input('value'),
            ]);

            flash()->success('Store Seo rule added');
            return redirect()->back();
        } catch (Exception $exception) {
            flash()->error('Error while adding new Seo rule');
            return redirect()->back();
        }
    }

    public function addStoreAddress(Request $request)
    {
        $request->validate([
            'city' => 'required',
            'postal_code' => 'required',
            'latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
            'longitude' => ['required', 'numeric', 'min:-180', 'max:180'],
            'address' => 'required',
        ], [
            'latitude.regex' => 'Latitude should be between -90 to 90',
            'longitude.regex' => 'Longitude should be between -180 to 180'
        ]);

        try {
            DB::beginTransaction();
            StoreAddress::create([
                'store_id' => $request->input('store_id'),
                'city' => $request->input('city'),
                'postal_code' => $request->input('postal_code'),
                'address' => $request->input('address'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
            ]);

            DB::commit();

            if (!$request->ajax()) {
                flash()->success('store address added.');
                return redirect()->back();
            }
        } catch (Exception $e) {
            DB::rollBack();
            if (!$request->ajax()) {
                flash()->error('Error while adding store address.');
                return redirect()->back();
            }
        }
    }

    public function editStoreAddress($id)
    {
        $address = StoreAddress::where('id', $id)->first();
        return view('admin-dashboard.stores.address_edit_modal', compact('address'))->render();
    }

    public function updateStoreAddress(Request $request, StoreAddress $store_address)
    {
        $request->validate([
            'city' => 'required',
            'postal_code' => 'required',
            'latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
            'longitude' => ['required', 'numeric', 'min:-180', 'max:180'],
            'address' => 'required',
        ], [
            'latitude.regex' => 'Latitude should be between -90 to 90',
            'longitude.regex' => 'Longitude should be between -180 to 180'
        ]);

        try {
            DB::beginTransaction();
            $address = StoreAddress::where('id', $request->input('address_id'))->update([
                'city' => $request->input('city'),
                'postal_code' => $request->input('postal_code'),
                'address' => $request->input('address'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
            ]);
            DB::commit();

            if (!$request->ajax()) {
                flash()->success('Address updated successfully.');
                return redirect()->back();
            }
        } catch (Exception $e) {
            DB::rollBack();
            if (!$request->ajax()) {
                flash()->error("Error while update store address.");
                return redirect()->back();
            }
        }
    }

    public function deleteStoreAddress($id)
    {
        StoreAddress::where('id', $id)->delete();
        flash()->success('Seo rule deleted');
    }

    public function editStoreSeoRule($id)
    {
        $storeSeoRule = StoreSeoData::where('id', $id)->first();
        return view('admin-dashboard.stores.store_seo_edit_modal', compact('storeSeoRule'))->render();
    }

    public function updateStoreSeoRule(Request $request, StoreSeoData $Store_seo_data)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        StoreSeoData::where('id', $request->input('seo_id'))->update([
            'key' => $request->input('key'),
            'value' => $request->input('value'),
        ]);
        if (!$request->ajax()) {
            flash()->success('Seo rule updated successfully');
            return redirect()->back();
        }
    }

    public function deleteStoreSeoRule($id)
    {
        StoreSeoData::where('id', $id)->delete();
        flash()->success('Seo rule deleted');
    }

    public function importFakeData()
    {
        try {
            Artisan::call('db:seed --class=FakeStoresSeeder');
            return flash()->success('Fake data has been imported.');
        } catch (Exception $e) {
            return flash()->success($e->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
