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
        $baseUrl = url('/');
        return [
            'id' => $this->id,
            'title' => $this->title,
            'charity_type_id' => $this->charity_types_id,
            'country_id' => $this->country,
            'small_image' => empty($this->logo_upload) ?  $this->logo_link : $baseUrl . '/' . $this->logo_upload,
            'large_image' => empty($this->banner_upload) ?  $this->banner_link : $baseUrl . '/' . $this->banner_upload,
            'status' => $this->status == 1 ? 'Active' : 'Inactive',
            'large_image_type' => $this->banner_type,
            'small_image_type' => $this->logo_type,
            'description' => $this->description,
            'date_updated' => date('d-M-Y', strtotime($this->updated_at)),
            'date_created' => date('d-M-Y', strtotime($this->created_at)),
        ];
    }
}
