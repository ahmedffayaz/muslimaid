<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        
        if($this->banner == 'default1.png' || $this->banner == 'default2.png' || $this->banner == 'default3.png')
            $banner_url = url('frontend/images/slides/'.$this->banner);
        else
            $banner_url = url('storage/slider/slides/images/'.$this->banner);

        if($this->logo == 'default1.png' || $this->logo == 'default2.png' || $this->logo == 'default3.png')
            $logo_url =  url('frontend/images/slides/logo/'.$this->logo);
        else
            $logo_url =  url('storage/slider/slides/images/'.$this->logo);

        $currency = ($this->store->cashback->type=='fixed') ? $this->store->cashback->currency :null;

        $cashback_value = $this->store->custom_cashback_percentage ? ($this->store->custom_cashback_percentage/100)*$this->store->cashback->sale_commission :(SiteSetting()['cashback_percentage']/100)*$this->store->cashback->sale_commission;

        $cashback = $currency ? $currency.$cashback_value: $cashback_value.'%';

        return [
            'background_image'=>$banner_url,
            'logo'=>$logo_url,
            'store_name'=>$this->store->name,
            'store_slug'=>$this->store->slug,
            'cashback'=>$cashback,
            'description'=>$this->description,
        ];
    }
}
