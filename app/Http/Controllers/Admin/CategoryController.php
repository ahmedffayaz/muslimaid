<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Throwable;
use App\Models\Blog;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class CategoryController extends Controller
{
    public $imagePath = 'storage/categories/images/';
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
    public function store(CategoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $category = Category::create([
                'name' => $request->input('name'),
                'parent_id' => $request->input('parent_id'),
                'is_map_enable' => $request->input('is_map_enable') == 1 ? 1 : 0,
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

                    $category->logo_upload = $this->imagePath . $imageName;
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

                    $category->banner_upload = $this->imagePath . $imageName;
                    $category->update();
                } else {
                    $category->banner_upload = 'category_default_banner.png';
                    $category->update();
                }
            }
            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'New Category added'
            ], JsonResponse::HTTP_OK);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $exception->getMessage() . 'Error while adding new category'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
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

        return view('admin-dashboard.categories.create', compact('category', 'categories', 'stores', 'blog'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
         try {
            DB::beginTransaction();
            $category->update([
                'name' => $request->input('name'),
                'parent_id' => $request->input('parent_id'),
                'is_map_enable' => $request->input('is_map_enable') == 1 ? 1 : 0,
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
            if($category->parent_id === 0){
                foreach ($request->input('tags') as $tag) {
                    $category->update([
                        $tag => 1
                    ]);
                }
            }
            if ($request->input('logo_type') == 'upload') {
                if ($request->has('logo_upload')) {
                    $imageName = Str::slug($request->input('name')) . '_logo_' . time() . '.' . $request->logo_upload->extension();
                    $request->logo_upload->storeAs('public/categories/images', $imageName);

                    $category->logo_upload = $this->imagePath . $imageName;
                    $category->update();
                }
            }
            if ($request->input('banner_type') == 'upload') {
                if ($request->has('banner_upload')) {
                    $imageName = Str::slug($request->input('name')) . '_banner_' . time() . '.' . $request->banner_upload->extension();
                    $request->logo_upload->storeAs('public/categories/images', $imageName);

                    $category->banner_upload = $this->imagePath . $imageName;
                    $category->update();
                }
            }

            DB::commit();

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'Category updated'
            ], JsonResponse::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_FORBIDDEN,
                'error' => 'Something went wrong'
            ], JsonResponse::HTTP_FORBIDDEN);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'errors' => $exception->getMessage() . 'Error while updating the category'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
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
        try {
            DB::beginTransaction();
            $childs = $category->childs;
            if (count($childs)) {
                foreach ($childs as $child) {
                    $child->parent_id = $category->parent_id;
                    $child->update();
                }
            }
            $category->delete();
            DB::commit();

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'Category deleted successfully'
            ], JsonResponse::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'error' => 'Something went wrong'
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $exception->getMessage() . 'Error while updating the category'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
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
