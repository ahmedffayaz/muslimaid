<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
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
            "id" => $this->id,
            "title" => $this->name,
            "description" => $this->description,
            "promotion_type" => $this->promotion_type,
            "coupon_code" => $this->coupon_code ? $this->coupon_code : '',
            'image' => getImageUrl($this->store->logo->first()),
            "promotion_end_date" => \Carbon\Carbon::parse($this->promotion_end_date)->isoFormat('DD-MM-YYYY'),
            "promotion_start_date" => \Carbon\Carbon::parse($this->promotion_start_date)->isoFormat('DD-MM-YYYY'),
        ];
    }
}
