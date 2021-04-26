<?php

namespace App\Http\Controllers\Admin\Translations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Validator;
use Spatie\TranslationLoader\LanguageLine;
use App\Models\Language;

class LanguageLineController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($transationId)
    {
        $translation = LanguageLine::findOrFail($transationId);
        $languages = Language::whereNotIn('code', ['en'])->get();
        return view('admin-dashbaord.trasnlations.language_lines_modal', compact('translation', 'languages'));
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
                'code' => 'required',
                'text' => 'required',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->errors()->first());

            \DB::beginTransaction();
            $transationId = $request->translation_id;

            $transation = LanguageLine::find($transationId);
            $encoded = json_encode($transation->text);
            $decoded = json_decode($encoded, true);

            if (array_key_exists($request->code, $decoded)) {
                throw new \Exception('Translation already exist');
            }
            $incomingArray = [
                $request->code => $request->text
            ];

            $newArray = array_merge($decoded, $incomingArray);
            $transation->text = $newArray;
            $transation->save();
            
            \DB::commit();

            flash()->success('translation added successfully');
           return redirect()->back();

        } catch (\Exception $e) {
            \DB::rollBack();
            flash()->error('Something went wrong');
           return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($transationId, $code)
    {
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code)
    {
        try {
            $validator = Validator::make($request->all(), [
                'text' => 'required',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->errors()->first());
            $transationId = $request->translation_id;

            \DB::beginTransaction();

            $transation = LanguageLine::find($transationId);
            $encoded = json_encode($transation->text);
            $decoded = json_decode($encoded, true);

            if (!array_key_exists($code, $decoded)) {
                throw new \Exception('Translation not exist');
            }

            $decoded[$code] = $request->text;
            $transation->update(['text' => $decoded]);
            \DB::commit();
            flash()->success('translation updated successfully');
            return redirect()->back();

            

        } catch (\Exception $e) {
            \DB::rollBack();
            flash()->error('Something went wrong');
           return redirect()->back();
        }
    }
}
