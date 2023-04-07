<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->whereIn('type', ['special', 'general'])->first();
        if (empty($page)) abort(404);

        if (view()->exists("frontend.pages.{$slug}")) {
            return view("frontend.pages.{$slug}", compact('page'));
        }
        if($slug == 'blog'){
            $blogs = Blog::latest()->paginate(20);
            return view('frontend.pages.single-page', compact('page', 'slug', 'blogs'));
        }
        return view('frontend.pages.single-page', compact('page', 'slug'));
    }

    public function login(Request $request)
    {
        session(['prvUrl' => $request->get('prvUrl')]);
        return view('auth.login');
    }

    public function register(Request $request)
    {
        session(['prvUrl' => $request->get('prvUrl')]);
        return view('auth.register');
    }
}
