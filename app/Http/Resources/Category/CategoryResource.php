<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\SubCategoryResource;

class CategoryResource extends JsonResource
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
            'title' => $this->name,
            'url_key' => $this->slug,
            'icon' => ($this->logo_type != 'link') ? getLogoImageUrl($this->logo_upload, 'upload', $this) : $this->logo_link,
            'description' => $this->description,
            'main_banner' => ($this->banner_type != 'link') ? getBannerImageUrl($this->banner_upload, 'upload', $this) : $this->banner_link,
            'cat_stores_count' => $this->stores_count,
            'subcats' => SubCategoryResource::collection($this->whenLoaded('childs'))
        ];
    }
}
