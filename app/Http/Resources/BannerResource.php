<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $bannerImage = $this->banner_type == 'link' ? $this->banner_link : asset('storage/' . $this->banner_upload);
        $refLink = url('/register-form?referby=' . auth()->user()->short_ref_id);

        return [
            'id' => $this->id,
            'banner_image' => $this->banner_type != 'link' ? getBannerImageUrl($this->banner_upload, 'upload', $this) : $this->banner_link,
            'banner_ref_link' => '<a href="' . $refLink . '"><img src="' . $bannerImage . '"></a>'
        ];
    }
}
