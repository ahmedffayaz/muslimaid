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
            'Store' => $this->store->name,
            'store_id' => $this->store->id,
            'Date' => \Carbon\Carbon::parse($this->created_at)->isoFormat('Do MMMM YYYY'),

        ];
    }
}
