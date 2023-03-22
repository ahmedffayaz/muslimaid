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
            'logo_upload' => 'required_if:logo_type,==,upload|image:jpeg,png,jpg,gif',
            'logo_link' => 'required_if:logo_type,==,link|nullable|url',
            'banner_type' => 'required',
            'banner_upload' => 'required_if:banner_type,==,upload|image:jpeg,png,jpg,gif',
            'banner_link' => 'required_if:banner_type,==,link|nullable|url',
            'status' => 'required',
            'sort' => 'required|integer|min:1'
            
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
            'sort' => 'The sort field is required',
            'sort.integer' => 'Sort field is must be integer',
            'banner_upload.required_if' => ' The banner upload field is required',
            'logo_upload.required_if' => ' The logo upload field is required',
            'banner_link.required_if' => ' The banner link field is required',
            'logo_link.required_if' => ' The logo link field is required',
        ];
    }
}
