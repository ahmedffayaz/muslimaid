<?php

namespace App\Http\Resources\Home;

use Illuminate\Http\Resources\Json\JsonResource;

class SlideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $bannerLogo = ($this->logo == 'default1.png' || $this->logo == 'default2.png' || $this->logo == 'default3.png') ? asset('frontend/images/slides/logo/' . $this->logo) : asset($this->logo);
        $bannerImage = ($this->banner == 'default1.png' || $this->banner == 'default2.png' || $this->banner == 'default3.png') ? asset('frontend/images/slides/' . $this->banner) : asset($this->banner);

        $data = [
            'order' => $this->order,
            'banner_type' => $this->slider_type,
            'banner_logo' => $bannerLogo,
            'banner_image' => $bannerImage,
            'description' => $this->description,
            'link' => $this->link
        ];

        if ($this->slider_type == 'store') {
            $data['url_key'] = optional($this->store)->slug;
        }

        return $data;
    }
}
