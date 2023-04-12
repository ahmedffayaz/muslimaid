<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DashboardResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = [
            "id" => $this->id,
            "name" => $this->first_name . ' ' . $this->last_name,
            "email" => $this->email,
            "is_verify" => $this->is_email_verified ? 'Yes' : 'No',
            "address" => empty($this->address) ? '' : $this->address,
            "contact_number" => $this->phone,
            "user_image" => url('/') . '/' .  ($this->avatar == 'default.png' || !Storage::exists('public/users/images/avatar/' . $this->avatar) ? 'admin-dashboard/images/avatar.png' : 'storage/users/images/avatar/' . $this->avatar),
            "status" => $this->status,
            "paypal_email" =>  empty($this->paypalInfo) ? '' : $this->paypalInfo->paypal_email,
            "date_created" => date('d-M-Y', strtotime($this->created_at)),
        ];
        $data = [
            'user' => $user,
            'currency' => "£",
            "Pending" => [
                "title" => "Pending",
                "description" => "Transactions tracked by Cashblack and awaiting retailer confirmation.",
                "amount" =>  currency($this->availableBalance(1))
            ],
            "Confirmed" => [
                "title" =>  "Confirmed",
                "description" => "Transactions confirmed by the retailer and awaiting cashouts.",
                "amount" => currency($this->availableBalance(3))
            ],
            "Payable" => [
                "title" => "Processing",
                "description" =>  "Transactions in process of cashouts or donation.",
                "amount" =>  currency($this->availableBalance(5))
            ],
            "Paidout" => [
                "title" => "TOTAL",
                "description" => "Total sum of all your debit and credit amounts.",
                "amount" => currency($this->availableBalance())
            ],
            "total_debit" => [
                "amount" => currency($this->availableBalance())
            ],
            "limit" => getSpecificSetting('min_cashout_amount')
        ];
        $response = [
            'status' => 200,
            'message' => 'Successful',
            'data' => $data
        ];
        return  $response;
    }
}
