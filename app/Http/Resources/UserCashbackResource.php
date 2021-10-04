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
            'Order Amount'=> currency().number_format((float)$this->order_value, 2, '.', ''),
            'Cashback'=> currency().number_format((float)$this->amount, 2, '.', ''),
            'Date'=>\Carbon\Carbon::parse($this->event_date)->isoFormat('Do MMMM YYYY'),
            'Status'=>$this->statusMap->status
        ];
       
    }
}
