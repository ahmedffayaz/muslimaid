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
        // $currency = ($this->type=='fixed') ? $this->currency :null;

        // $cashback_value = $this->store->custom_cashback_percentage ? ($this->store->custom_cashback_percentage/100)*$this->sale_commission :(SiteSetting()['cashback_percentage']/100)*$this->sale_commission;

        // $cashback = $currency ? $currency.$cashback_value: $cashback_value.'%';
        
        return [
            "title"=>$this->link_name,
            "description"=>$this->description,
            "promotion_type"=>$this->promotion_type,
            "coupon_code"=>$this->coupon_code,
            "click_url"=>$this->click_url,
            // "sale_commission"=>$this->sale_commission,
            "promotion_end_date"=>\Carbon\Carbon::parse($this->promotion_end_date)->isoFormat('DD-MM-YYYY'),
            "promotion_start_date"=>\Carbon\Carbon::parse($this->promotion_start_date)->isoFormat('DD-MM-YYYY'),
        ];
    }
}
