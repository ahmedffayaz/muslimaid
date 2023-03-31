<?php

namespace App\Http\Resources\Home;

use App\Http\Resources\StoresResource;
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
        if ($this->banner_type != 'link') {
            $banner = getBannerImageUrl($this->banner_upload, 'upload', $this);
        } else {
            $banner = $this->banner_link;
        }

        return [
            'title' => $this->name,
            'url_key' => $this->slug,
            'main_banner' => $banner,
            'stores' => StoresResource::collection($this->stores)
        ];
    }
}
