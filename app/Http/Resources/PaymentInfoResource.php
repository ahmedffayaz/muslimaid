<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentInfoResource extends JsonResource
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
            'user_id' => $this->user_id,
            'payment_method' => $this->payment_method,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'paypal_email' => $this->paypal_email,
            'address' => $this->address,
            'city' => $this->city,
            'postcode' => $this->postcode,
            'country' => $this->country,
            'account_name' => $this->account_name,
            'bank_title' => $this->bank_title,
            'account_number' => $this->account_number,
            'bank_sort_code' => $this->bank_sort_code,
            'bic' => $this->bic,
            'status' => $this->status
        ];
    }
}
