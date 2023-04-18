<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CashbackResource extends JsonResource
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
            'cashback' => $this->getCashback(),
            'icon' => !empty($this->image) ? getImageUrl(asset('storage/' . $this->image)) : null,
            'detail' => $this->detail,
            'sale_commission' => empty($this->sale_commission) ? '' : $this->sale_commission,
            'click_url' => empty($this->click_url) ? '' : $this->click_url,
            'deeplink_url' => empty($this->deeplink_url) ? '' : $this->deeplink_url,
            'tracking_url' => empty($this->tracking_url) ? '' : $this->tracking_url,
            "date_updated" => date('d-M-Y', strtotime($this->updated_at)),
            "date_created" => date('d-M-Y', strtotime($this->created_at)),
        ];
    }
}
