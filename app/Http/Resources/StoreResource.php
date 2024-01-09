<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $headerToken = $request->bearerToken();
        return [
            'id' => $this->id,
            'title' => $this->name,
            'url_key' => $this->slug,
            'banner_image' => getImageUrl($this->images->where('title', 'Cover')->first()),
            'banner_image_large' => getImageUrl($this->images->where('title', 'large cover')->first()),
            'big_icon' => getImageUrl($this->logo->first()),
            'icon_large' => getImageUrl($this->images->where('title', 'large logo')->first()),
            'description' => $this->when($this->description, $this->description),
            'terms_conditions' => $this->when($this->terms_conditions, $this->terms_conditions),
            'cashback' => $this->default_cashback,
            'is_fav' => checkFavorite($this->id, $headerToken) ? true : false,
            'lat'=> optional($this->storeAddress->first())->latitude,
            'long' => optional($this->storeAddress->first())->longitude,
         ];
    }
}
