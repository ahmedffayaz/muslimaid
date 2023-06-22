<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CharityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'charity_type_id' => $this->charity_types_id,
            'country_id' => $this->country,
            'small_image' => ($this->logo_type != 'link') ? (isFileExist($this->logo_upload) ? asset($this->logo_upload) : asset('frontend/images/banners/categories/cashback.png')) : $this->logo_link, 
            'large_image' => ($this->banner_type != 'link') ? getBannerImageUrl($this->banner_upload, 'upload', $this) : $this->banner_link,
            'status' => $this->status == 1 ? 'Active' : 'Inactive',
            'large_image_type' => $this->banner_type,
            'small_image_type' => $this->logo_type,
            'description' => $this->description,
            'date_updated' => date('d-M-Y', strtotime($this->updated_at)),
            'date_created' => date('d-M-Y', strtotime($this->created_at)),
        ];
    }
}
