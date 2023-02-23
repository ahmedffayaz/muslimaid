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
            'parent_id' => 'required',
            'logo_type' => 'required',
            'logo_upload' => 'nullable|mimes:jpeg,jpg,png,gif|max:1024',
            'logo_link' => 'nullable|url',
            'banner_type' => 'required',
            'banner_upload' => 'nullable|mimes:jpeg,jpg,png,gif|max:1024',
            'banner_link' => 'nullable|url',
            'status' => 'required'
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
            'name' => 'Name is required',
            'parent_id.required' => 'Parent category must be select',
            'logo_type.required' => 'Logo/Icon type must be select',
            'logo_upload.max' => 'Logo max size should be 1MB',
            'banner_upload.max' => 'Banner max size should be 1MB'
        ];
    }
}
