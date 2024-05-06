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
        if ($this->avatar == 'default.png' || $this->avatar == NULL || $this->avatar == '') {
            $avatar = asset('storage/__asset/img/default-avatar.png');
        } else {
            $avatar = !Storage::exists('public/users/images/avatar/' . $this->avatar)
                ? asset('storage/__asset/img/default-avatar.png')
                : asset('storage/users/images/avatar/' . $this->avatar);
        }

        $user = [
            "id" => $this->id,
            "name" => $this->first_name . ' ' . $this->last_name,
            "email" => $this->email,
            "is_verify" => $this->is_email_verified ? 'Yes' : 'No',
            "address" => empty($this->address) ? '' : $this->address,
            "phone" => $this->phone,
            "avatar" => $avatar,
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
                "amount" => currency($this->availableBalance(1) , false)
            ],
            "Confirmed" => [
                "title" =>  "Confirmed",
                "description" => "Transactions confirmed by the retailer and awaiting cashouts.",
                "amount" => currency($this->availableBalance(3) , false)
            ],
            "Payable" => [
                "title" => "Processing",
                "description" =>  "Transactions in process of cashouts or donation.",
                "amount" => currency($this->availableBalance(5) , false)
            ],
            "Paidout" => [
                "title" => "TOTAL",
                "description" => "Total sum of all your debit and credit amounts.",
                "amount" => currency($this->availableBalance() , false)
            ],
            "total_debit" => [
                "amount" => currency($this->availableBalance() , false)
            ],
            "donated" => [
                "title" =>  "Donated",
                "description" => "Your total donated amount.",
                "amount" => currency($this->availableBalance(7) , false)
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
