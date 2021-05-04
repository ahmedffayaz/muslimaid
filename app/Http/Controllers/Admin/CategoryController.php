<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route = 'index';
        $categories = Category::latest()->where('parent_id', '=', 0)->get();
        $allCategories = Category::latest()->get();
        return view('admin-dashboard.categories.categories', compact('categories','allCategories','route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::latest()->get();
        return view('admin-dashboard.categories.create', compact('categories'));

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
            return redirect()->route('admin.categories.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $category = Category::create([
                'name' => $request->input('name'), 
                'parent_id' => $request->input('parent_id'), 
            ]);

            flash()->success('New Category added');
            return redirect()->route('admin.categories.index');
            
        } catch (Exception $exception) {

            flash()->error('Error while adding new category');
            return redirect()->route('admin.categories.index');
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
        $route = 'index';
        $categories = Category::latest()->paginate(20);
        $store_categories = Category::latest()->get();
        return view('admin-dashboard.categories.index', compact('categories','store_categories','route'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $categories = Category::latest()->get()->except($category->id);

        return view('admin-dashboard.categories.edit', compact('category','categories'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        try {
            $category->update([
                'name'=>$request->input('name'),
                'parent_id' => $request->input('parent_id'), 
    
            ]);  
            flash()->success('Category updated');

            return redirect()->route('admin.categories.index');
        } catch (\Throwable $th) {

            flash()->error('Error while updating the category');
            return redirect()->route('admin.categories.index');
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
        $route = 'index';

        $categories = Category::latest()->paginate(20);

         return view('admin-dashboard.categories.index_data', compact('categories','route'))->render();
     }
    }
    public function exportCsv(Request $request)
    {
        try {

            $table = Category::latest()->get();
            $filename = "categories.csv";
            $handle = fopen($filename, 'w+');
            fputcsv($handle, array('Name', 'Parent Category','No of Stores', 'Status'));

            foreach($table as $row) {
                fputcsv($handle, array($row->name, $row->parent->name ?? '', count($row->stores), $row->status ? 'active' : 'in-active'));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

            return \Response::download($filename, 'categories.csv', $headers);
        } catch (\Throwable $th) {
            
            flash()->error('Error while exporting categories');
            return redirect()->route('admin.categories.index');

        }

    }
    public function searcCategories(Request $request, Category $categories)
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
        
        $categories = $categories->latest()->paginate(20);
        $route='search';
        return view('admin-dashboard.categories.index_data', compact('categories','route'))->render();

    }
}
