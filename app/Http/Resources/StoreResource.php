<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CashbackResource;
use App\Http\Resources\VoucherResource;


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
        return [
            'id' => $this->id,
            'title' => $this->name,
            'url_key' => $this->slug,
            'banner_image' => getImageUrl($this->images()->where('title', 'cover')->first()),
            'big_icon' => getImageUrl($this->logo->first()),
            'description' => $this->when($this->description, $this->description),
            'terms_conditions' => $this->when($this->terms_conditions, $this->terms_conditions),
            'cashback' => $this->getCashback(),
            'is_fav' => checkFavorite($this->id) ? true : false,
        ];
    }
}
