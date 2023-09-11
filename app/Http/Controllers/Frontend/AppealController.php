<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Appeal;
use App\Models\Page;
use Illuminate\Http\Request;

class AppealController extends Controller
{
    function __construct()
    {
        $this->middleware('is_appeal_module_access', ['only' => ['index', 'show', 'appealsView']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = Page::where('slug', 'appeals')->whereType('system')->first();
        if (empty($page)) abort(404);
        return view('frontend.appeals.index', compact('page'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $appeal = Appeal::whereSlug($slug)->whereStatus(1)->first();
        $appeals = Appeal::whereStatus(1)->inRandomOrder()->limit(3)->get();
        return view('frontend.appeals.show', compact('appeal', 'appeals'));
    }

    public function appealsView (Request $request)
    {
        $appeals = Appeal::whereStatus(1)->paginate($request->input('perPage'));

        return [
            'view' => view('frontend.appeals.appeals-view', compact('appeals'))->render()
        ];
    }
}
