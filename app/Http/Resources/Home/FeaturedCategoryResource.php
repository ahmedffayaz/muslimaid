<?php

namespace App\Http\Resources\Home;

use App\Http\Resources\HomeStoreResource;
use App\Http\Resources\StoreResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FeaturedCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $banner = $this->banner_type != 'link' ? getBannerImageUrl($this->banner_upload, 'upload', $this) : $this->banner_link;

        return [
            'title' => $this->name,
            'url_key' => $this->slug,
            'main_banner' => $banner,
            'stores' => HomeStoreResource::collection($this->stores)
        ];
    }
}
