<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Network;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ImportedCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class ImportedCategoryController extends Controller
{

    function __construct()
    {
        
         $this->middleware('permission:map categories', ['only' => ['edit','update']]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ImportedCategory::latest()->get();
        return view('admin-dashboard.imported-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-dashboard.imported-categories.create');

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
            'name' => 'required|max:255',
           
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.importedcategories.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $category = ImportedCategory::create([
                'name' => $request->input('name'),
                
            ]);

            return redirect()->route('admin.importedcategories.index');
           
            
        } catch (Exception $exception) {
            return redirect()->route('admin.importedcategories.index');

            
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
    public function edit(ImportedCategory $importedcategory)
    {
        $site_categories = Category::latest()->where('parent_id', '=', 0)->get();
        $parent_categories = ImportedCategory::latest()->get()->except($importedcategory->id);

        return view('admin-dashboard.imported-categories.mapper', compact('importedcategory','site_categories','parent_categories'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImportedCategory $importedcategory)
    {
        try {
            $importedcategory->update([
                'mapped_to'=>$request->input('site_category_id'),
            ]);
    
    
             if($request->input('site_category_id')){
                 $mapped_category = Category::findOrFail($request->input('site_category_id'));
                DB::table('category_store')
                ->where('network_category_id',$importedcategory->id)
                ->update([
                    'category_id' => $importedcategory->mapped_to,
                    ]);
                    // $stores = DB::table('category_store')
                    // ->where('network_category_id',$importedcategory->id)->get();
                    // $this->assignChildCategories($mapped_category,$importedcategory,$stores); 
                    // $this->assignParentCategories($mapped_category,$importedcategory,$stores);

             }   
             flash()->success('Category updated');
            return redirect()->route('admin.networks.categories',$importedcategory->network);
        } catch (\Throwable $th) {
            flash()->error($th->getMessage().'Something went wrong!');
            return redirect()->route('admin.networks.categories',$importedcategory->network);
        }
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImportedCategory $importedcategory)
    {
        $importedcategory->delete();

        flash()->success('category deleted successfully');
        return redirect()->route('admin.networks.categories',$importedcategory->network);

    }
    function fetch(Request $request)
    {
     if($request->ajax())
     {
         $route = 'index';
         $categories = ImportedCategory::where('network_id',$request->network_id)->latest()->paginate(30);

         return view('admin-dashboard.imported-categories.index_data', compact('categories','route'))->render();
     }
    }
    public function searcImportedCategories(Request $request, ImportedCategory $categories)
    {
        // dd($request->all());
        $categories= $categories->newQuery();

        // Search by parent.
        if ($request->input('parent_id')) {
            $categories->where('parent_id', $request->input('parent_id'));
        }

        // Search by name.
        if ($request->input('title')) {
            $categories->where('name','like', '%'.$request->input('title').'%');
           
        }

        // Search by mapped cat.
        if ($request->input('mapped_id')!=-1) {
            $categories->where('mapped_to', $request->input('mapped_id'));
        }
        
        $categories = $categories->latest()->paginate(10);
        $route='search';
        return view('admin-dashboard.imported-categories.index_data', compact('categories','route'))->render();
    }

    public function assignChildCategories(Category $mapped_category, ImportedCategory $importedcategory, $stores){

        if($mapped_category->childs->count()){
            foreach($mapped_category->childs as $child){
                foreach($stores as $store){
                DB::table('category_store')
                ->insert([
                'network_category_id'=>$importedcategory->id,
                'category_id' => $child->id,
                'store_id' => $store->store_id
                ]);
            }
                $this->assignChildCategories($child,$importedcategory, $stores);
            }
            
        }

    }

    public function assignParentCategories(Category $mapped_category,ImportedCategory $importedcategory, $stores){
        if($mapped_category->parent_id){
            foreach($stores as $store){
                DB::table('category_store')
                ->insert([
                    'network_category_id'=>$importedcategory->id,
                    'category_id' => $mapped_category->parent_id,
                    'store_id' => $store->store_id
                    ]);
            }
            
                $this->assignParentCategories($mapped_category->parent,$importedcategory, $stores);

        }
    }
}
