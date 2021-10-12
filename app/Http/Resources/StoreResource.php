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

        if($this->cashback){

            $currency = ($this->cashback->type=='fixed') ? $this->cashback->currency :null;

            $cashback_value = $this->custom_cashback_percentage ? ($this->custom_cashback_percentage/100)*$this->cashback->sale_commission :(SiteSetting()['cashback_percentage']/100)*$this->cashback->sale_commission;
    
            $cashback = $currency ? $currency.$cashback_value: $cashback_value.'%';
            return [
            
                "network"=> $this->network->name,
                "name" => $this->name,
                "slug"=> $this->slug,
                "description"=> $this->description,
                "default_cashback"=>$cashback,
                "tracking_url"=> $this->tracking_url,
                "store_url"=> $this->store_url,
                "logo"=> $this->logo->first() ? ($this->logo->first()->is_fake ? url('frontend/images/logos/'.$this->logo->first()->image) :url('storage/stores/images/'.$this->logo->first()->image)) :url('frontend/images/products/product-16.jpg'),
                "cashbacks"=>CashbackResource::collection($this->cashbacks),
                "vouchers"=>VoucherResource::collection($this->vouchers),
            ];

        }
        
    }
}
