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
        $currency = ($this->type=='fixed') ? $this->currency :null;

        $cashback_value = $this->store->custom_cashback_percentage ? ($this->store->custom_cashback_percentage/100)*$this->sale_commission :(SiteSetting()['cashback_percentage']/100)*$this->sale_commission;

        $cashback = $currency ? $currency.$cashback_value: $cashback_value.'%';
        
        return [
                
            "cashback_name"=>$this->cashback_name,
            "click_url"=>$this->click_url!='#' ? $this->click_url: $this->store->tracking_url,
            "sale_commission"=>$cashback,
            "detail"=>$this->detail,
        ];
    }
}
