<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\SeoRule;
use Illuminate\Http\Request;
use App\Models\SeoRuleData;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SeoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seo_rules = SeoRule::with('ruleData')->get();

        return view('admin-dashboard.seo.index',compact('seo_rules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-dashboard.seo.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'type' => 'required',
            'type.*.key' => 'required',

        ], [
            'url.required' => 'The url field is required.',
            'type.required' => 'At least one SEO rule must be select.',
            'type.*.key.required' => 'key field is required.',
        ]);

        try {
            DB::beginTransaction();

            // Removing / from URL
            $final_string = rtrim($request->url, '/');

            $seo_rule = SeoRule::create([
                'url' => $final_string,
                'is_enabled' => isset($request->is_enabled) ? 1 : 0
            ]);

            if($request->type)
            {
                foreach($request->type as $type)
                {
                    $type['type'] = "meta";
                    $seo_rule->ruleData()->create($type);
                }
            }

            DB::commit();

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'SEO rule added successfully.'
            ], JsonResponse::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'error' => 'Some thing went wrong'
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $exception->getMessage()
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
    public function edit(SeoRule $seo)
    {
        $seoData = SeoRule::withCount('ruleData')->with('ruleData')->where('id',$seo['id'])->first();

        return view('admin-dashboard.seo.form',compact('seoData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  SeoRule $seo)
    {
        $request->validate([
            'url' => 'required|url',
            'type' => 'required',
            'type.*.key' => 'required',
        ], [
            'url.required' => 'The url field is required.',
            'type.required' => 'At least one SEO rule must be select.',
            'type.*.key.required' => 'key field is required.',
        ]);

        try {
            DB::beginTransaction();

            $seo->ruleData()->delete();

            // Removing / from URL
            $final_string = rtrim($request->url, '/');

            $seo->update([
                'url' => $final_string,
                'is_enabled' => isset($request->is_enabled) ? 1 : 0
            ]);

            if($request->type)
            {
                foreach($request->type as $val)
                {
                    $val['type'] = "meta";
                    $seo->ruleData()->create($val);
                }
            }

            DB::commit();

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'SEO rule updated successfully.'
            ], JsonResponse::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'error' => 'Some thing went wrong'
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $exception->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SeoRule $seo)
    {
        SeoRuleData::where('seo_rule_id',$seo['id'])->delete();
        $seo->delete();
        flash()->success('Seo rule deleted successfully');
        return redirect()->route('admin.seo.index');
    }
}
