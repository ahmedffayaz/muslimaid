<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'logo_type' => 'required',
            'logo_upload' => 'nullable',
            'logo_link' => 'nullable|url',
            'banner_type' => 'required',
            'banner_upload' => 'nullable',
            'banner_link' => 'nullable|url',
            'status' => 'required',
            'sort' => 'required|integer'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name' => 'Name field is required',
            'logo_type.required' => 'Logo/Icon type must be select',
            'logo_upload.max' => 'Logo max size should be 1MB',
            'banner_upload.max' => 'Banner max size should be 1MB',
            'status' => 'Status field is required',
            'sort' => 'Sort field is required',
            'sort.integer'=> 'Sort field is must be integer',
        ];
    }
}
