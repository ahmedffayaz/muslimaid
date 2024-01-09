<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            "id" => $this->id,
            "ticket_type" => !empty($this->claim_type) ? $this->claim_type : '',
            "title" => !empty($this->title) ? $this->title : '',
            "user_id" =>  !empty($this->user_id) ? $this->user_id : '',
            "store_id" => !empty($this->store_id) ? $this->store_id : '',
            "exit_click_id" => !empty($this->click_id) ? $this->click_id : '',
            "cashback_id" => !empty($this->cashback_id) ? $this->cashback_id : '',
            "order_value" => !empty($this->claim_amount) ? $this->claim_amount : '',
            "description" => !empty($this->message) ? $this->message : '',
            "status" => !empty($this->status) ? $this->status : '',
            "date_created" => date('d-M-Y', strtotime($this->created_at)),
            "date_updated" => date('d-M-Y', strtotime($this->updated_at)),
            "store_name" => isset($this->store) ? $this->store->name : '',
        ];
    }
}
