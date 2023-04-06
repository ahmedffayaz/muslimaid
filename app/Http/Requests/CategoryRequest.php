<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;
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

    public function rules()
    {
        return [
            'name' => 'required',
            'logo_type' => 'required',
            'banner_type' => 'required',
            'status' => 'required',
            'sort' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'logo_type.required' => 'The logo type field is required.',
            'banner_type.required' => 'The banner type field is required.',
            'status.required' => 'The status field is required.',
            'sort.required' => 'The sort field is required.',
            'sort.integer' => 'The sort field must be an integer.',
            'sort.min' => 'The sort field must be at least :min.',
            'logo_upload.required_if' => 'The logo upload field is required when logo type is upload.',
            'logo_upload.image' => 'The logo upload field must be an image of type: jpeg, png, jpg, gif.',
            'banner_upload.required_if' => 'The banner upload field is required when banner type is upload.',
            'banner_upload.image' => 'The banner upload field must be an image of type: jpeg, png, jpg, gif.',
            'logo_link.required_if' => 'The logo link field is required when logo type is link.',
            'logo_link.url' => 'The logo link field must be a valid URL.',
            'banner_link.required_if' => 'The banner link field is required when banner type is link.',
            'banner_link.url' => 'The banner link field must be a valid URL.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $category = $this->route('category');
            if ($category) {
                if ($this->logo_type == 'upload' && empty($this->logo_upload) && empty($category->logo_upload)) {
                    $validator->errors()->add('logo_upload', 'The logo upload field is required when logo type is upload.');
                }

                if ($this->banner_type == 'upload' && empty($this->banner_upload) && empty($category->banner_upload)) {
                    $validator->errors()->add('banner_upload', 'The banner upload field is required when banner type is upload.');
                }

                if ($this->logo_type == 'link' && empty($this->logo_link) && empty($category->logo_link)) {
                    $validator->errors()->add('logo_link', 'The logo link field is required when logo type is link.');
                }

                if ($this->banner_type == 'link' && empty($this->banner_link) && empty($category->banner_link)) {
                    $validator->errors()->add('banner_link', 'The banner link field is required when banner type is link.');
                }
            }elseif(isset($this->parent_id) && $this->parent_id == 158){

            } else {
                $newValidator = Validator::make($this->all(), [
                    'logo_upload' => 'required_if:logo_type,==,upload|image:jpeg,png,jpg,gif',
                    'logo_link' => 'required_if:logo_type,==,link|nullable|url',
                    'banner_upload' => 'required_if:banner_type,==,upload|image:jpeg,png,jpg,gif',
                    'banner_link' => 'required_if:banner_type,==,link|nullable|url',
                ]);
    
                if ($newValidator->fails()) {
                    $validator->errors()->merge($newValidator->errors());
                }
            }
        });
    }
}
