<?php

namespace App\Http\Resources\Home;

use Illuminate\Http\Resources\Json\JsonResource;

class FeaturedStoreResource extends JsonResource
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
            'banner_image' => getImageUrl($this->images()->where('title', 'cover')->first()),
            'big_icon' => getImageUrl($this->logo->first()),
            'cashback' => $this->getCashback()
        ];
    }
}
