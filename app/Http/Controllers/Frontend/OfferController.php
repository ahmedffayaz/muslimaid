<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OfferController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'offers')->whereType('system')->first();
        if (empty($page)) abort(404);

        $categories = Category::where('parent_id', 0)->with(['stores' => function ($query) {
            $query->withCount(['categories' => function ($query) {
                $query->whereStatus(0);
            }])->having('categories_count', 0);
        }])->get();

        return view('frontend.pages.single-page', compact('page', 'categories'));
    }
}
