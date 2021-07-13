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
                
                "cashback_name"=>$this->cashback_name,
                "click_url"=>$this->click_url,
                "type"=>$this->type,
                "sale_commission"=>$this->sale_commission,
                "currency"=>$this->currency,
                "detail"=>$this->detail,
        ];
    }
}
