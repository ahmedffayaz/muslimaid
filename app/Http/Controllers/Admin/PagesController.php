<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class PagesController extends Controller
{
    public $imagePath = 'storage/pages/banners/';
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
            'title' => 'required',
            'banner_image' => 'required'
        ], [
        ], [
            'title.required' => 'The Title field is required.',
        ]);

        try {
            DB::beginTransaction();
            if ($request->has('banner_image')) {
                $imageName = Str::slug($request->input('title')) . '_banner_' . time() . '.' . $request->banner_image->extension();
                $request->banner_image->storeAs('public/pages/banners', $imageName);
                $banner_image = $this->imagePath . $imageName;
            }
            $slug = Str::slug($request->input('title'));
            $lastId = Page::orderBy('id', 'desc')->pluck('id')->first();
            $pageSlug = Page::where('slug', $slug)->first();
            $page = new Page;
            $page->title = $request->title;
            $page->slug = isset($pageSlug) ? $slug . '-' . ($lastId + 1) : $slug;
            $page->excerpt = $request->excerpt;
            $page->lb_content = $this->addContainerToParagraphs($request->content);
            $page->status = $request->status;
            $page->banner_image = $banner_image;
            $page->description = $request->short_description;
            $page->meta_description = $request->meta_description;
            $page->meta_keyword = $request->meta_keyword;
            $page->meta_title = $request->meta_title;
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
        ]);

        try {
            DB::beginTransaction();

            $page->update([
                'title' => $request->input('title'),
                'excerpt' => $request->input('excerpt'),
                'lb_content' => $this->addContainerToParagraphs($request->content),
                'status' => $request->input('status') == 'inactive' && $page->type == 'general' ? 'inactive' : 'active',
                'description' => $request->input('short_description'),
                'meta_description' => $request->input('meta_description'),
                'meta_keyword' => $request->input('meta_keyword'),
                'meta_title' => $request->input('meta_title'),
            ]);

            if ($request->has('banner_image')) {
                $imageName = Str::slug($request->input('title')) . '_banner_' . time() . '.' . $request->banner_image->extension();
                $request->banner_image->storeAs('public/pages/banners', $imageName);
                $page->banner_image = $this->imagePath . $imageName; 
                $page->update();
            }

            DB::commit();

            if (!$request->ajax()) {
                flash()->success('Page updated');
                return redirect()->route('admin.pages.index');
            }

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Page updated',
                'url' => route('admin.pages.index')
            ], JsonResponse::HTTP_OK);
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

    function addContainerToParagraphs($description){
        $updatedDescription = preg_replace('/<p>(?!\[.*?\])(.*?)<\/p>/', '<div class="container"><p>$1</p></div>', $description);
        $updatedDescription = preg_replace('/<div class="container">(.*?)<div class="container">(.*?)<\/div>(.*?)<\/div>/', '<div class="container">$1$2$3</div>', $updatedDescription);
        $updatedDescription = str_replace('<div class="container"><p></p></div>', '', $updatedDescription);
        $updatedDescription = str_replace('<div class="container"><p><p>', '<div class="container"><p>', $updatedDescription);
        $updatedDescription = preg_replace('/(<p[^>]*)class="([^"]*)"(>)/', '$1class="$2 container"$3', $updatedDescription);
        return $updatedDescription;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        if ($page->type == 'system') {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'System pages cannot be deleted.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

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

    public function getAvailableShortCodes()
    {
        $templates = File::allFiles(resource_path(convertPathForOS('views/frontend/templates')));

        $shortCodes = [];

        foreach ($templates as $template) {
            array_push(
                $shortCodes, 
                '[' . str_replace('.blade.php', '', $template->getFilename()) . ']'
            );
        }

        return view('admin-dashboard.pages.short-codes-modal', compact('shortCodes'));
    }
}
