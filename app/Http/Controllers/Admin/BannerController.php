<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BannerController extends Controller
{
    function __construct()
    {
        $this->middleware('is_banner_module_access', ['only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::paginate(20);
        return view('admin-dashboard.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banners = Banner::get();
        return view('admin-dashboard.banners.edit', compact('banners'));
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
            'name' => 'required',
            'type' => 'required',
            'banner_type' => 'required',
            'status' => 'required',
            'banner_upload' => [
                Rule::requiredIf(function () use ($request){
                    return $request->banner_type == "upload";
                }),
                'nullable', 'mimes:jpeg,png,jpg'
            ],
            'banner_link' => [
                Rule::requiredIf(function() use ($request){
                    return $request->banner_type == "link";
                }),
                'nullable', 'sometimes', 'url'
            ]
        ]);

        if($validator->fails()){
            if(!$request->ajax()){
                flash()->error($validator->errors()->first());
                return redirect()->back()->withInput();
            }

            return response()->json(['status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            'errors' => $validator->errors()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        try {
            DB::beginTransaction();

            if ($request->hasFile('banner_upload'))
                $banner = saveResizeImage($request->file('banner_upload'), 'banners/images', 200);

            Banner::create([
                'name' => $request->name,
                'banner_type' => $request->banner_type,
                'banner_link' => isset($request->banner_link) ? $request->banner_link : null,
                'banner_upload' => isset($banner) ? $banner : null,
                'type' => $request->type,
                'status' => $request->status
            ]);

            DB::commit();

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'Banner added successfully.'
            ], JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong, try again.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
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
        $banner = Banner::findOrFail($id);

        return view('admin-dashboard.banners.edit', compact('banner'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'banner_type' => 'required',
            'status' => 'required',
            'banner_upload' => [
                Rule::requiredIf(function () use ($request){
                    return $request->banner_type == "upload";
                }),
                'nullable', 'mimes:jpeg,png,jpg'
            ],
            'banner_upload' => 'sometimes|mimes:jpeg,png,jpg',
            'banner_link' => [
                Rule::requiredIf(function () use ($request){
                    return $request->banner_type == "link";
                }),
                'nullable', 'sometimes', 'url'
            ],
        ]);

        if($validator->fails()){
            if(!$request->ajax()){
                flash()->error($validator->errors()->first());
                return redirect()->back()->withInput();
            }

            return response()->json(['status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            'errors' => $validator->errors()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        try {
            DB::beginTransaction();

            $banner = Banner::findOrFail($id);


            if ($request->hasFile('banner_upload'))
                $bannerImage = saveResizeImage($request->file('banner_upload'), 'banners/images', 200);

            $banner->update([
                'name' => $request->name,
                'banner_type' => $request->banner_type,
                'banner_link' => isset($request->banner_link) ? $request->banner_link : (!isset($bannerImage) ? ($banner->banner_link) : null),
                'banner_upload' => isset($bannerImage) ? $bannerImage : (!isset($request->banner_link) ? $banner->banner_upload : null),
                'type' => $request->type,
                'status' => $request->status
            ]);

            DB::commit();

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'Banner added successfully.'
            ], JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong, try again.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
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
            DB::beginTransaction();
            $banner = Banner::findOrFail($id);
            $banner->delete();
            DB::commit();

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'Banner deleted successfully.'
            ], JsonResponse::HTTP_OK);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Error while updating the banner.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
