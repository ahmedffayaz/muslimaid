<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [ 
        "name"=> $this->name,
        "slug"=> $this->slug,
        "description"=> $this->description,
        "logo_type"=> $this->logo_type,
        "logo_upload"=> $this->logo_upload,
        "logo_link"=> $this->logo_link,
        "banner_type"=> $this->banner_type,
        "banner_upload"=> $this->banner_upload,
        "banner_link"=> $this->banner_link,
        "parent_id"=> $this->parent_id,
    ];
    }
}
