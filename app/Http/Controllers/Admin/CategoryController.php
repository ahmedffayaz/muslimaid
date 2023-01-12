<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Store;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


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
        $categories = Category::where('parent_id', '=', 0)->orderBy('name', 'ASC')->get();
        $allCategories = Category::latest()->get();
        return view('admin-dashboard.categories.categories', compact('categories', 'allCategories', 'route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id', 0)->orderBy('name', 'ASC')->get();
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
            flash()->success($validator->errors()->first());
            return redirect()->route('admin.categories.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $category = Category::create([
                'name' => $request->input('name'),
                'parent_id' => $request->input('parent_id'),
                'description' => $request->input('description'),
                'logo_type' => $request->input('logo_type'),
                'logo_link' => $request->input('logo_link'),
                'banner_type' => $request->input('banner_type'),
                'banner_link' => $request->input('banner_link'),
                'sort' => $request->input('sort'),
                'status' => $request->input('status'),
                'title' => $request->input('title'),
                'meta_keyword' => $request->input('meta_keyword'),
                'meta_description' => $request->input('meta_description'),
                'slug' => Str::slug($request->name),
            ]);

            if ($request->input('logo_type') == 'upload') {
                if ($request->has('logo_upload')) {
                    $imageName = Str::slug($request->input('name')) . '_logo_' . time() . '.' . $request->logo_upload->extension();
                    $request->logo_upload->storeAs('public/categories/images', $imageName);

                    $category->logo_upload = $imageName;
                    $category->update();
                } else {
                    $category->logo_upload = 'category_default_logo.png';
                    $category->update();
                }
            }

            if ($request->input('banner_type') == 'upload') {

                if ($request->has('banner_upload')) {

                    $imageName = Str::slug($request->input('name')) . '_banner_' . time() . '.' . $request->banner_upload->extension();
                    $request->banner_upload->storeAs('public/categories/images', $imageName);

                    $category->banner_upload = $imageName;
                    $category->update();
                } else {
                    $category->banner_upload = 'category_default_banner.png';
                    $category->update();
                }
            }
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
        return view('admin-dashboard.categories.index', compact('categories', 'store_categories', 'route'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $categories = Category::latest()->where('parent_id', 0)->get();
        $stores = Store::latest()->get();
        $blog = Blog::latest()->get();

        return view('admin-dashboard.categories.edit', compact('category', 'categories', 'stores', 'blog'))->render();
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
                'name' => $request->input('name'),
                'parent_id' => $request->input('parent_id'),
                'description' => $request->input('description'),
                'logo_type' => $request->input('logo_type'),
                'logo_link' => $request->input('logo_link'),
                'banner_type' => $request->input('banner_type'),
                'banner_link' => $request->input('banner_link'),
                'sort' => $request->input('sort'),
                'status' => $request->input('status'),
                'feature_homepage' => 0,
                'feature_sidebar' => 0,
                'title' => $request->input('title'),
                'meta_keyword' => $request->input('meta_keyword'),
                'meta_description' => $request->input('meta_description')

            ]);

            foreach ($request->input('tags') as $tag) {
                $category->update([
                    $tag => 1
                ]);
            }

            if ($request->input('logo_type') == 'upload') {
                if ($request->has('logo_upload')) {
                    $imageName = Str::slug($request->input('name')) . '_logo_' . time() . '.' . $request->logo_upload->extension();
                    $request->logo_upload->storeAs('public/categories/images', $imageName);

                    $category->logo_upload = $imageName;
                    $category->update();
                }
            }
            if ($request->input('banner_type') == 'upload') {
                if ($request->has('banner_upload')) {
                    $imageName = Str::slug($request->input('name')) . '_banner_' . time() . '.' . $request->banner_upload->extension();
                    $request->logo_upload->storeAs('public/categories/images', $imageName);

                    $category->banner_upload = $imageName;
                    $category->update();
                }
            }

            if (!$request->ajax()) {
                flash()->success('Category updated');
                return redirect()->route('admin.categories.index');
            } else {
                return 1;
            }
        } catch (\Throwable $th) {
            flash()->error($th->getMessage() . 'Error while updating the category');
            return redirect()->route('admin.categories.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $childs = $category->childs;
        if (count($childs)) {
            foreach ($childs as $child) {
                $child->parent_id = $category->parent_id;
                $child->update();
            }
        }
        $category->delete();

        flash()->success('category deleted successfully');
        return redirect()->route('admin.categories.index');
    }
    function fetch(Request $request)
    {
        if ($request->ajax()) {
            $route = 'index';
            $categories = Category::latest()->paginate(20);
            return view('admin-dashboard.categories.index_data', compact('categories', 'route'))->render();
        }
    }
    public function exportCsv(Request $request)
    {
        try {
            $table = Category::latest()->get();
            $filename = "categories.csv";
            $handle = fopen($filename, 'w+');
            fputcsv($handle, array('Name', 'Parent Category', 'No of Stores', 'Status'));

            foreach ($table as $row) {
                fputcsv($handle, array($row->name, $row->parent->name ?? '', count($row->stores), $row->status ? 'active' : 'in-active'));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

            return Response::download($filename, 'categories.csv', $headers);
        } catch (\Throwable $th) {
            flash()->error('Error while exporting categories');
            return redirect()->route('admin.categories.index');
        }
    }
    public function searcCategories(Request $request, Category $categories)
    {
        $categories = $categories->newQuery();

        // Search by parent.
        if ($request->input('parent_id')) {
            $categories->where('parent_id', $request->input('parent_id'));
        }

        // Search by name.
        if ($request->input('title')) {
            $categories->where('name', 'like', '%' . $request->input('title') . '%');
        }

        $categories = $categories->latest()->paginate(20);
        $route = 'search';
        return view('admin-dashboard.categories.index_data', compact('categories', 'route'))->render();
    }

    public function picks(Category $category)
    {
        $categories = Category::latest()->where('parent_id', 0)->get();
        $stores = Store::latest()->get();
        return view('admin-dashboard.categories.picks-form', compact('category', 'categories', 'stores'))->render();
    }
}
