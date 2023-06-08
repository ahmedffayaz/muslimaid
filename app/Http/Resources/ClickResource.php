<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClickResource extends JsonResource
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
            'id' => $this->store->id,
            'title' => $this->store->name,
            'url_key' => $this->store->slug,
            'date' => \Carbon\Carbon::parse($this->created_at)->isoFormat('Do MMMM YYYY'),
            'converted' => isset($this->cashback) ? 'Converted' : 'Not Converted'
        ];
    }
}
