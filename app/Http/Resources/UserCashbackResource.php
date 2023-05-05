<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCashbackResource extends JsonResource
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
            'order_value' => currency($this->order_value),
            'cashback' => currency($this->amount),
            'date' => \Carbon\Carbon::parse($this->event_date)->isoFormat('Do MMMM YYYY'),
            'status' => $this->statusMap->status
        ];
    }
}
