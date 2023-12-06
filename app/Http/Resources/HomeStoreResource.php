<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HomeStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $headerToken = $request->bearerToken();
        return [
            'id' => $this->id,
            'title' => $this->name,
            'url_key' => $this->slug,
            'banner_image' => getImageUrl($this->images()->where('title', 'Cover')->first()),
            'banner_image_large' => getImageUrl($this->images->where('title', 'large cover')->first()),
            'big_icon' => getImageUrl($this->logo->first()),
            'icon_large' => getImageUrl($this->images->where('title', 'large logo')->first()),
            'status' => $this->status,
            'cashback' => $this->default_cashback,
            'is_fav' => checkFavorite($this->id, $headerToken) ? true : false,
        ];
    }
}
