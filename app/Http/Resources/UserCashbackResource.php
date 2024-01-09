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
        $title="";
        if($this->type == 'welcome_bonus'){
            $title = "Welcome bonus";
        } else if($this->type == 'referral_bonus'){
            $title = "Referral Bonus";
        } else {
            $title = optional($this->store)->name;
        }
        
        return [
            'id' => $this->id,
            'title' => $title,
            'order_value' => currency($this->order_value),
            'cashback' => currency($this->amount),
            'date' => \Carbon\Carbon::parse($this->event_date)->isoFormat('Do MMMM YYYY'),
            'status' => $this->statusMap->status
        ];
    }
}
