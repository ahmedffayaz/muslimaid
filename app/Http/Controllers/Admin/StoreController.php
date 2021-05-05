<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\StoreCashback;
use App\Models\StoreImage;
use App\Models\Network;
use App\Models\Category;
use App\Models\StoreReview;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route='index';
        $networks = Network::all();
        $stores = Store::orderBy('id', 'DESC')->paginate(30);
        return view('admin-dashboard.stores.index', compact('stores','route','networks'));
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
        return view('admin-dashboard.stores.create', compact('networks','categories'));
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
            'category_id' => 'required',
            'tracking_url' => 'required',
            'store_url' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.stores.index')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $store = Store::create([
                'name'         => $request->input('store_name'),
                'network_id'   => $request->input('network_id'),
                'tracking_url' => $request->input('tracking_url'),
                'store_url'    => $request->input('store_url'),
                'description'    => $request->input('description'),
                'extra_info'    => $request->input('extra_info'),
                'terms_conditions'    => $request->input('terms_conditions'),
                'status'    => 1,
                'slug'    => \Str::slug($request->input('store_name')),
            ]);

            
            $cashback = StoreCashback::create([
                'store_id'=>$store->id,
                'sale_commission'=>$request->input('store_cashback'),
                'click_url'=>$request->input('tracking_url')
            ]);

            foreach ($request->input('category_id') as $category) {
                DB::table('category_store')->insert([
                    'store_id' => $store->id,
                    'category_id' => $category
                ]);
            }

            
            flash()->success('New store added');
            return redirect()->route('admin.stores.index');
           
            
        } catch (Exception $exception) {

            flash()->error('Error while adding new store');
            return redirect()->route('admin.stores.index');            
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
        $categories = Category::all();
        $stores = Store::all();
        return view('admin-dashboard.stores.show',compact( 'networks', 'categories','store','stores'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        $logo = StoreImage::where([ 'store_id'=>$store->id, 'title'=>'logo' ])->first();
        if(!$logo){
            $logoUrl = asset('admin-dashboard/images/avatar/default-logo.png');
        }else{
            $logoUrl = asset('storage/stores/images/'.$logo->image);
        }
        $networks = Network::all();
        $categories = Category::all();
        return view('admin-dashboard.stores.edit', compact('store', 'networks', 'categories','logoUrl'));

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
        // dd($request->all());

       
        try {
            $store->update([
                'name'         => $request->input('store_name'),
                'network_id'   => $request->input('network_id'),
                'tracking_url' => $request->input('tracking_url'),
                'store_url'    => $request->input('store_url'),
                'description'    => $request->input('description'),
                'extra_info'    => $request->input('extra_info'),
                'terms_conditions'    => $request->input('terms_conditions'),
                'status'    => $request->input('status'),
                'slug'    => \Str::slug($request->input('store_name')),
            ]);
            if($request->has('image')){
                
                $imageName = \Str::slug($request->input('store_name')).'_logo_'.time().'.'.$request->image->extension();          
                $request->image->storeAs('public/stores/images',$imageName);
                $logo = StoreImage::where([ 'store_id'=>$store->id, 'title'=>'logo' ])->first();

                if($logo){
                    Storage::delete(['public/stores/images/'.$logo->image]);
                    $logo->update([ 'image' => $imageName ]);
                }
                else{
                    $logo = StoreImage::create([
                        'store_id'=>$store->id,
                        'title' => 'logo',
                        'image' =>$imageName,
                        'image_type'=>'store_logo'
                    ]);
                }

            }

            

            $cashback = StoreCashback::where('store_id',$store->id)->first();
            $cashback->update([
                'sale_commission'=>$request->input('store_cashback'),
                'click_url'=>$request->input('tracking_url')
            ]);
    
            DB::table('category_store')->where('store_id', $store->id)->delete();
            
            foreach ($request->input('category_id') as $category) {
                DB::table('category_store')->insert([
                    'store_id' => $store->id,
                    'category_id' => $category
                ]);
            }

            if(!$request->ajax())
            { flash()->success('Store info updated successfully');
                return redirect()->back();
            }
            else{
                return true;
            }
            

            // return redirect()->route('admin.stores.index');
        } catch (\Throwable $th) {

            // flash()->error('Error while updating the store');
            // return redirect()->route('admin.stores.index');
            return $th;

        }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    function fetch(Request $request)
    {
        if($request->ajax())
        {
            $route='index';
            $stores = Store::orderBy('id', 'DESC')->paginate(30);

            return view('admin-dashboard.stores.index_data', compact('stores','route'))->render();
        }
    }
    public function exportCsv(Request $request)
    {
        try {
            
            $table = Store::latest()->get();
            $filename = "stores.csv";
            $handle = fopen($filename, 'w+');
            fputcsv($handle, array('Name', 'slug','Network', 'Cashback', 'Tracking Url','Store Url', 'Description','Terms & Conditions','Extra Info', 'Status'));

            foreach($table as $row) {
                fputcsv($handle, array($row->name, $row->network->name, $row->network->slug, $row->cashback->sale_commission ?? 'NA', $row->cashback->click_url ?? "#", $row['store_url'], strip_tags($row['description']), $row['terms_conditions'],$row['extra_info'], $row['status']));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

            return \Response::download($filename, 'stores.csv', $headers);

        } catch (\Throwable $th) {

            flash()->error('Error while exporting the stores');
            return redirect()->route('admin.stores.index');

        }
        


    }
    public function storeImages(Store $store){

        return view('admin-dashboard.stores.images',compact('store'));
    }

    public function uploadImage(Request $request, Store $store)
    {
        if($request->has('image')){

            $title_exist = StoreImage::where([ 'store_id'=>$store->id, 'title'=>$request->title ])->first();
            if($title_exist ){
                flash()->error('Image with same title already exist');
                return redirect()->route('admin.stores.images',$store);
            }
                
            $imageName = \Str::slug($store->name).'_logo_'.time().'.'.$request->image->extension();          
            $request->image->storeAs('public/stores/images',$imageName);
         
                $logo = StoreImage::create([
                    'store_id'=>$store->id,
                    'title' => $request->title,
                    'image' =>$imageName,
                    'image_type'=>'store_logo'
                ]);

            flash()->success('Image uploaded successfully');
            return redirect()->route('admin.stores.show',$store);

        }else{

            flash()->error('Image is required');
            return redirect()->route('admin.stores.show',$store);
        }        
    }

    public function deleteImage(StoreImage $storeimage)
    { 
        $store = Store::where('id',$storeimage->store_id)->first();
        Storage::delete(['public/stores/images/'.$storeimage->image]);
        $storeimage->delete();
        flash()->success('Image deleted');
        return redirect()->route('admin.stores.show',$store);

    }
    public function searchStores(Request $request, Store $stores)
    {
        // dd($request->all());
        $stores = $stores->newQuery();

        // Search by network.
        if ($request->input('network_id')) {
            $stores->where('network_id', $request->input('network_id'));
        }
        // Search by id.
        if ($request->input('store_id')) {
            $stores->where('id', $request->input('store_id'));
        }

        // Search by store name.
        if ($request->input('store_name')) {
            $stores->where('name','like', '%'.$request->input('store_name').'%');
           
        }

        // Search by status.
        if ($request->input('status')!=-1) {
            $stores->where('status', $request->input('status'));
        }
        
        $stores = $stores->latest()->paginate(10);
        $route='search';
        return view('admin-dashboard.stores.index_data', compact('stores','route'))->render();
    }
    function fetchVouchers(Request $request)
    {
        if($request->ajax())
        {
           $store = Store::where('id',$request->store)->first();

            return view('admin-dashboard.stores.vouchers', compact('store'))->render();
        }
    }
    function fetchCashbacks(Request $request)
    {
        if($request->ajax())
        {
           $store = Store::where('id',$request->store)->first();

            return view('admin-dashboard.stores.cashbacks', compact('store'))->render();
        }
    }
    function fetchReviews(Request $request)
    {
        if($request->ajax())
        {
           $store = Store::where('id',$request->store)->first();

            return view('admin-dashboard.stores.reviews', compact('store'))->render();
        }
    }
    public function editReview(Request $request, StoreReview $review )
    {  
       return view('admin-dashboard.stores.review-form', compact('review'))->render();

    }
    public function editCashback(Request $request, StoreCashback $cashback )
    {
        
        return view('admin-dashboard.stores.cashback-edit', compact('cashback'))->render();

    }
    public function updateCashback(Request $request, StoreCashback $cashback)
    {

        $cashback->update($request->all());
        return true;
    }
    public function createCashback(Request $request)
    {
        // $cashback = StoreCashback::create([
        //     'store_id'=>$store->id,
        //     'sale_commission'=>$request->input('store_cashback'),
        //     'click_url'=>$request->input('tracking_url')
        // ]);

        $cashback = StoreCashback::create($request->all());
        return true;
    }
    function fetchImages(Request $request)
    {
        if($request->ajax())
        {
           $store = Store::where('id',$request->store)->first();

            return view('admin-dashboard.stores.images-data', compact('store'))->render();
        }
    }
}
