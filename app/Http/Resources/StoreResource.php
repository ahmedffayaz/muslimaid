<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CashbackResource;


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
            
            "network"=> $this->network->name,
            "name" => $this->name,
            "slug"=> $this->slug,
            "description"=> $this->description,
            "default_cashback"=>$this->cashback->sale_commission,
            "cashback_type"=>$this->cashback->type,
            "currency"=>$this->cashback->currency,
            "tracking_url"=> $this->tracking_url,
            "store_url"=> $this->store_url,
            "logo"=> $this->logo->first()->is_fake ? url('frontend/images/logos/'.$this->logo->first()->image) :url('storage/stores/images/'.$this->logo->first()->image),
            "cashbacks"=>CashbackResource::collection($this->cashbacks),
            // "vouchers"=>$this->vouchers,
        ];
    }
}
