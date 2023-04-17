<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CashoutResource extends JsonResource
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
            'id' => $this->id,
            'transactionDate' => date('d-M-Y', strtotime($this->created_at)),
            'amount' => currency($this->amount),
            "transactionType" => $this->payment_method ,
            "status" => $this->status,
        ];
    }
}
