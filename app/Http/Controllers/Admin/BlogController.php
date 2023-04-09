<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route='index';
        $blogs = Blog::latest()->paginate(10);
        return view('admin-dashboard.blogs.index',compact('blogs','route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-dashboard.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|regex:/^[\w. ]+$/',
            'filepath' => 'required',
        ],$messages = [
            'title.required' => 'The Title field is required.',
            'filepath.required' => 'The Featured Image is required.',
        ]);
        $blog = new Blog;
        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title);
        $blog->excerpt = $request->excerpt;
        $blog->lb_content = $request->content;
        $blog->featured_image = $request->filepath;
        $blog->url = 'http://127.0.0.1:8000/post/'.Str::slug($request->title,'_');
        $blog->meta_keyword = $request->meta_keyword;
        $blog->meta_description = $request->meta_description;
        $blog->save();

        $inserted_blog = Blog::where('title', $request->title)->get();
        $counter = count($inserted_blog);

        if($counter>1){
            if($blog->slug == ''){
                $blog->slug = $blog->id;
                $blog->save();
            }else{
                $blog->slug = $blog->slug."_".$counter;
                $blog->save();
            }
        }

        flash()->success('New blog post created successfully');
        return redirect()->route('admin.blogs.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        return view('admin-dashboard.blogs.edit',compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|regex:/^[\w. ]+$/',
        ],$messages = [
            'title.required' => 'The Title field is required.',
        ]);
        $blog->title = $request->title;
        $blog->excerpt = $request->excerpt;
        $blog->lb_content = $request->content;
        $blog->meta_keyword = $request->meta_keyword;
        $blog->meta_description = $request->meta_description;
        if ($request->has('filepath') && !empty($request->filepath)) {
            $blog->featured_image = $request->filepath;
        } else {
            $blog->featured_image = $blog->featured_image; 
        }
        $blog->update();
        flash()->success('blog updated successfully');
        return redirect()->route('admin.blogs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();
        flash()->success('blog deleted successfully');
        return redirect()->route('admin.blogs.index');
    }

    public function runValidation($request)
    {
        return $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);
    }
}
