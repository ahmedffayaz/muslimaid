<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Blog;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index()
    {
        $page = Page::whereSlug('blogs')->whereType('system')->first();
        if (empty($page)) abort(404);
        
        $blogs = Blog::latest()->paginate(20);
        $latestBlogs = Blog::latest()->limit(5)->get();

        return view('frontend.pages.single-page', compact('page', 'blogs', 'latestBlogs'));
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        if (empty($blog)) abort(404);

        $latestBlogs = Blog::where('id', '<>', $blog->id)->latest()->limit(5)->get();

        return view('frontend.blogs.show', compact('blog', 'latestBlogs'));
    }
}
