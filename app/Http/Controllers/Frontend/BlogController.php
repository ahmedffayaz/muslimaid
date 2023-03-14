<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->get();
        $latestBlogs = Blog::latest()->limit(5)->get();

        return view('frontend.blogs.index', compact('blogs', 'latestBlogs'));
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        if (empty($blog)) abort(404);

        $latestBlogs = Blog::where('id', '<>', $blog->id)->latest()->limit(5)->get();

        return view('frontend.blogs.show', compact('blog', 'latestBlogs'));
    }
}
