<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'image' => getImageUrl($this->logo->first()),
            'id' => $this->id . '/' . $this->slug,
            'unique_id' => $this->id,
            'text' => $this->name,
            'extra' => $this->getCashback(),
        ];
    }
}
