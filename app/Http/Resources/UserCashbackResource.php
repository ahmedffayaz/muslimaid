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
            'Store'=>$this->store->name,
            'Order Amount'=> currency($this->order_value),
            'Cashback'=> currency($this->order_value),
            'Date'=>\Carbon\Carbon::parse($this->event_date)->isoFormat('Do MMMM YYYY'),
            'Status'=>$this->statusMap->status
        ];

    }
}
