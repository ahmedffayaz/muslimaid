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
            'title' => $this->name,
            'url_key' => $this->slug,
            'big_icon' => getImageUrl($this->logo->first()),
            'cashback' => $this->getCashback()
        ];
    }
}
