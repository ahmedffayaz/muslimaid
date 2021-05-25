<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use Illuminate\Http\JsonResponse;
use \Illuminate\Support\Facades\Validator;
use Config;

class LanguageController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view languages', ['only' => ['index','show']]);
        $this->middleware('permission:edit languages', ['only' => ['edit','update']]);
        $this->middleware('permission:add languages', ['only' => ['create','Store']]);
        $this->middleware('permission:delete languages', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route = 'index';
        $all_languages = Config::get('languages.languages');
        $languages = Language::orderBy('id', 'desc')->paginate(20);
        return view('admin-dashboard.languages.index', compact('all_languages','languages','route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:languages,name'
            ]);

            if ($validator->fails())
                throw new \Exception($validator->errors()->first());

            $languages = Config::get('languages.languages');
            foreach ($languages as $language) {
                if ($language['name'] === $request->name) {
                    $code = $language['code'];
                    break;
                }
            }

            $language = Language::create([
                'name' => $request->name,
                'code' => $code
            ]);

            flash()->success('language added successfully');

            return redirect()->route('admin.languages.index');

        } catch (\Exception $e) {

            flash()->error('Something went wrong!');

            return redirect()->route('admin.languages.index');
           
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            
            $language = Language::findOrFail($id);

            if ($language->code === 'en')
                throw new \Exception('English language can not be deleted');

            $language->delete();

            flash()->success('language delete successfully');
            return redirect()->route('admin.languages.index');

        } catch (\Exception $e) {

            flash()->error('Something went wrong!');
            return redirect()->route('admin.languages.index');
        }
    }
    function fetch(Request $request)
    {
     if($request->ajax())
     {
        $route = 'index';

        $languages = Language::orderBy('id', 'desc')->paginate(20);
        return view('admin-dashboard.languages.index_data', compact('languages','route'))->render();
     }
    }
    public function searchLanguages(Request $request, Language $languages)
    {
        // dd($request->all());
        $languages = $languages->newQuery();

        // Search by code.
        if ($request->input('code')) {
            $languages->where('code', $request->input('code'));
        }

        // Search by name.
        if ($request->input('name')) {
            $languages->where('name','like', '%'.$request->input('name').'%');
           
        }

       
        
        $languages = $languages->latest()->paginate(20);
        $route='search';
        return view('admin-dashboard.languages.index_data', compact('languages','route'))->render();
        

    }
}
