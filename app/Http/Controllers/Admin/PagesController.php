<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route = 'index';
        $pages = Page::latest()->paginate(20);
        return view('admin-dashboard.pages.index', compact('pages', 'route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-dashboard.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ], [
            'title.required' => 'The Title field is required.',
        ]);

        try {
            DB::beginTransaction();
            $slug = Str::slug($request->input('title'));
            $lastId = Page::orderBy('id', 'desc')->pluck('id')->first();
            $pageSlug = Page::where('slug', $slug)->first();
            $page = new Page;
            $page->title = $request->title;
            $page->slug = isset($pageSlug) ? $slug . '-' . ($lastId + 1) : $slug;
            $page->excerpt = $request->excerpt;
            $page->lb_content = $request->content;
            $page->status = $request->status;
            $page->banner_image = parse_url($request->filepath)['path'];
            $page->description = $request->short_description;
            $page->meta_description = $request->meta_description;
            $page->meta_keyword = $request->meta_keyword;
            $page->default = 0;
            $page->save();
            DB::commit();

            if (!$request->ajax()) {
                flash()->success('New Page created successfully');
                return redirect()->route('admin.pages.edit', $page->id);
            } else {
                return response()->json([
                    'status' => JsonResponse::HTTP_OK,
                    'message' => 'Page created successfully',
                    'url' => route('admin.pages.edit', $page->id)
                ], JsonResponse::HTTP_OK);
            }
        } catch (Throwable $th) {
            DB::rollBack();
            if (!$request->ajax()) {
                flash()->error('Something went wrong, try again.');
                return redirect()->back();
            }
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong, try again.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('admin-dashboard.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required'
        ], [
            'title.required' => 'The Title field is required.',
        ]);

        try {
            DB::beginTransaction();
            $page->title = $request->title;
            $page->excerpt = $request->excerpt;
            $page->lb_content = $request->content;
            $page->status = $request->status;
            if (isset($request->filepath)) {
                $page->banner_image = parse_url($request->filepath)['path'];
            }
            $page->description = $request->short_description;
            $page->meta_description = $request->meta_description;
            $page->meta_keyword = $request->meta_keyword;
            $page->save();

            DB::commit();
            if (!$request->ajax()) {
                flash()->success('Page updated');
                return redirect()->route('admin.pages.index');
            } else {
                return response()->json([
                    'status' => JsonResponse::HTTP_OK,
                    'message' => 'Page updated'
                ], JsonResponse::HTTP_OK);
            }
        } catch (Throwable $th) {
            DB::rollBack();
            if (!$request->ajax()) {
                flash()->error('Something went wrong, try again.');
                return redirect()->back();
            }
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong, try again.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $page->delete();
        flash()->success('Page deleted');
        return redirect()->route('admin.pages.index');
    }

    public function runValidation($request)
    {
        return $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);
    }
}
