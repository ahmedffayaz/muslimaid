<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Appeal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Throwable;

class AppealController extends Controller
{
    public $imagePath = 'storage/appeals/images/';

    function __construct()
    {
        $this->middleware('is_appeal_module_access', ['only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appeals = Appeal::latest()->paginate(10);
        return view('admin-dashboard.appeals.index', compact('appeals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-dashboard.appeals.create');
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
            'title' => 'required|max:255',
            'image_type' => 'required',
            'image_upload' => [
                Rule::requiredIf(function() use ($request){
                    return $request->image_type == "upload";
                }),
                'nullable', 'mimes:jpeg,png,jpg,svg'
            ],
            'image_link' => [
                Rule::requiredIf(function() use ($request){
                    return $request->image_type == "link";
                }),
                'nullable', 'sometimes', 'url'
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $appeal = Appeal::create([
                'title' => $request->input('title'),
                'slug' => Str::slug($request->title),
                'description' => $request->input('description'),
                'image_type' => $request->input('image_type'),
                'image_link' => $request->input('image_link'),
                'status' => $request->input('status'),
            ]);
            if ($request->input('image_type') == 'upload') {
                if ($request->has('image_upload')) {
                    $imageName = Str::slug($request->input('image_type')) . '_image_' . time() . '.' . $request->image_upload->extension();
                    $request->image_upload->storeAs('public/appeals/images', $imageName);
                    $appeal->image_upload =$this->imagePath . $imageName;
                    $appeal->update();
                } else {
                    $appeal->image_upload = 'category_default_logo.png';
                    $appeal->update();
                }
            }

            DB::commit();
            flash()->success('New Appeal added');
            return redirect()->route(getAdminPrefix() . '.appeals.index');
        } catch (Throwable $th) {
            DB::rollBack();
            flash()->error('Something went wrong, try again');
            return redirect()->back();
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
    public function edit(Appeal $appeal)
    {
        return view('admin-dashboard.appeals.edit', compact('appeal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appeal $appeal)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'image_type' => 'required',
            'image_link' => [
                Rule::requiredIf(function () use ($request){
                    return $request->image_type == "link";
                }),
                'url','sometimes','nullable'
            ],
            'image_upload' => 'sometimes|mimes:jpeg,png,jpg,gif'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $inputData = [
                'title' => $request->input('title'),
                'country' => $request->input('country'),
                'image_type' => $request->input('image_type'),
                'image_link' => $request->input('image_link'),
                'description' => $request->input('description'),
                'status' => $request->input('status'),
            ];

            if ($request->input('image_type') == 'upload' && $request->has('image_upload')) {
                $imageName = Str::slug($request->input('name')) . '_image_' . time() . '.' . $request->image_upload->extension();
                $request->image_upload->storeAs('public/appeals/images', $imageName);
                $inputData['image_upload'] = $this->imagePath . $imageName;
                $inputData['image_link'] = null;
                if (File::exists(public_path($appeal->image_upload))) {
                    File::delete(public_path($appeal->image_upload));
                }
            } else if ($request->input('image_type') == 'link') {
                $inputData['image_upload'] = null;
                if (File::exists(public_path($appeal->image_upload))) {
                    File::delete(public_path($appeal->image_upload));
                }
            }

            $appeal->update($inputData);

            DB::commit();

            if (!$request->ajax()) {
                flash()->success('Appeal updated');
                return redirect()->route(getAdminPrefix() . '.appeals.index');
            } else {
                return 1;
            }

        } catch (Exception $e) {
            DB::rollBack();
            flash()->error('Something went wrong, try again');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appeal $appeal)
    {
        try {
            DB::beginTransaction();
            $appeal->delete();
            DB::commit();
            flash()->success('Appeal deleted');
            return redirect()->route(getAdminPrefix() . '.appeals.index');
        } catch (Throwable $th) {
            DB::rollBack();
            flash()->error('Something went wrong, try again');
            return redirect()->back();
        }
    }
}
