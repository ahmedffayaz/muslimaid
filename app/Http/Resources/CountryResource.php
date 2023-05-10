<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'title' => $this->name,
            'iso_code' => $this->iso_code,
            'region' => !empty($this->region) ? $this->region->name : '',
            'currency_id' => $this->currency_id,
            'banner_image' => $baseUrl . '/' . $this->type_value,
            'status' => $this->status == 1 ? 'Active' : 'Inactive',
            'banner_image_type' => $this->upload_type,
            "date_updated" => date('d-M-Y', strtotime($this->updated_at)),
            "date_created" => date('d-M-Y', strtotime($this->created_at)),
        ];
    }
}
