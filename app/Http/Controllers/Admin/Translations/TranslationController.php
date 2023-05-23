<?php

namespace App\Http\Controllers\Admin\Translations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\TranslationLoader\LanguageLine;
use Illuminate\Database\QueryException;
use \Illuminate\Support\Facades\Validator;
use App\Models\Language;

class TranslationController extends Controller
{
    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        $this->middleware('auth');
        $this->middleware('permission:view translations', ['only' => ['index','show']]);
        $this->middleware('permission:add translations', ['only' => ['create','store']]);
        $this->middleware('permission:edit translations', ['only' => ['edit','update']]);
        $this->middleware('permission:delete translations', ['only' => ['destroy']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route = 'index';
        $translations = LanguageLine::latest()->paginate(20);
        return view('admin-dashboard.translations.index',compact('translations','route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::orderBy('id', 'desc')->get();
        return view('admin-dashboard.translations.create',compact('languages'));
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
            'group' => 'required',
            'key' => 'required',
        ]);
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $data = LanguageLine::where('group', $request->group)->where('key', $request->key)->first();
        if (!empty($data)) {
           flash()->error('group and key already exist');
           return redirect()->route(getAdminPrefix() . '.translations.index');
        }
        
        LanguageLine::create($request->all());
        flash()->success('translation added successfully');
        return redirect()->route(getAdminPrefix() . '.translations.index');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $translation = LanguageLine::findOrFail($id);
        $languages = Language::whereNotIn('code', ['en'])->get();
        return view('admin-dashboard.translations.show', compact('translation', 'languages'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $translation = LanguageLine::findOrFail($id);
        $languages = Language::latest()->get();
        return view('admin-dashboard.translations.edit', compact('translation', 'languages'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $translation = LanguageLine::find($id);
        $translation->update($request->all());
        flash()->success('translation updated successfully');
        return redirect()->route(getAdminPrefix() . '.translations.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    function fetch(Request $request)
    {
     if($request->ajax())
     {
        $route = 'index';

        $translations = LanguageLine::latest()->paginate(20);
        return view('admin-dashboard.translations.index_data', compact('translations','route'))->render();
     }
    }
    public function searchTranslations(Request $request, LanguageLine $translations)
    {
        // dd($request->all());
        $translations = $translations->newQuery();

        

        // Search by group.
        if ($request->input('group')) {
            $translations->where('group','like', '%'.$request->input('group').'%');
           
        }
        // Search by key.
        if ($request->input('key')) {
            $translations->where('key','like', '%'.$request->input('key').'%');
           
        }

       
        
        $translations = $translations->latest()->paginate(20);
        $route='search';
        return view('admin-dashboard.translations.index_data', compact('translations','route'))->render();

        

    }
    public function comingSoon(){
        return view('admin-dashboard.translations.coming-soon');
    }

}
