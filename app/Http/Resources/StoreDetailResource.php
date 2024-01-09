<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $headerToken = $request->bearerToken();
        return [
            'id' => $this->id,
            'title' => $this->name,
            'url_key' => $this->slug,
            'banner_image' => getImageUrl($this->images()->where('title', 'Cover')->first()),
            'banner_image_large' => getImageUrl($this->images->where('title', 'large cover')->first()),
            'big_icon' => getImageUrl($this->logo->first()),
            'icon_large' => getImageUrl($this->images->where('title', 'large logo')->first()),
            'description' => $this->when($this->description, $this->description),
            'terms_conditions' => $this->when($this->terms_conditions, $this->terms_conditions),
            'meta_title' => $this->storeRuleData()->where('key', 'meta:title')->pluck('value')->first(),
            'meta_description' => $this->storeRuleData()->where('key', 'meta:description')->pluck('value')->first(),
            'meta_keywords' => $this->storeRuleData()->where('key', 'meta:keywords')->pluck('value')->first(),
            'status' => $this->status,
            'deeplink' => $this->deeplink_url,
            'site_deeplink' => $this->tracking_url,
            'website_url' => $this->store_url,
            'address' => $this->storeAddress()->pluck('address')->first(),
            'postcode' => $this->storeAddress()->pluck('postal_code')->first(),
            'city_id' => $this->storeAddress()->pluck('city')->first(),
            'latitude' => $this->storeAddress()->pluck('latitude')->first(),
            'longitude' => $this->storeAddress()->pluck('longitude')->first(),
            "date_updated" => date('d-M-Y', strtotime($this->updated_at)),
            "date_created" => date('d-M-Y', strtotime($this->created_at)),
            'cashback' => $this->default_cashback,
            'cashbacks' => $this->cashback ? $this->when($this->cashbacks, CashbackResource::collection($this->cashbacks)) : [],
            'vouchers' => $this->vouchers ? $this->when($this->vouchers, VoucherResource::collection($this->vouchers)) : [],
            'is_fav' => checkFavorite($this->id, $headerToken) ? true : false,
        ];
    }
}
