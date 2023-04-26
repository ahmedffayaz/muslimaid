<?php

namespace App\Http\Resources\Home;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $roles = $this->getRoleNames();
        $userRoles = $roles->implode(', ');

        $permissions = $this->getAllPermissions()->pluck('name');
        $userPermissions = $permissions->implode(',');

        $token = $this->createToken('API Token')->plainTextToken;

        $user = [
            "id" => $this->id,
            "name" => $this->first_name . ' ' . $this->last_name,
            "user_type" => ucwords($userRoles),
            "permission" =>  ucwords($userPermissions),
            "email" => $this->email,
            "paypal_email" =>  empty($this->paypalInfo) ? '' : $this->paypalInfo->paypal_email,
            "ref_id" => empty($this->referred_by) ? '' : $this->referred_by,
            "status" => $this->status,
            "dob" => date('d-M-Y', strtotime($this->date_of_birth)),
            "address" => empty($this->address) ? '' : $this->address,
            "avatar" => url('/') . '/' .  ($this->avatar == 'default.png' || !Storage::exists('public/users/images/avatar/' . $this->avatar) ? 'admin-dashboard/images/avatar.png' : 'storage/users/images/avatar/' . $this->avatar),
            "avatar_type" => "upload",
            "is_verify" => $this->is_email_verified ? 'Yes' : 'No',
            "date_updated" => date('d-M-Y', strtotime($this->updated_at)),
            "date_created" => date('d-M-Y', strtotime($this->created_at)),
            "otp" => empty($this->otp) ? '' : $this->otp,
            "firstname" => empty($this->first_name) ? '' : $this->first_name,
            "lastname" =>  empty($this->last_name) ? '' : $this->last_name,
            "phone" => $this->phone,
            "sort_code" => empty($this->bankInfo) ? '' : $this->bankInfo->bank_sort_code,
            "bank_acc_no" => empty($this->bankInfo) ? '' : $this->bankInfo->account_number,
            "referral_code" => empty($this->referred_by) ? '' : $this->referred_by,
            "email_preference" => $this->email_preference ? 'Yes' : 'No',
            "token" => $token,
            "balance" => currency($this->availableBalance(3))
        ];

        $data = [
            'user' => $user,
        ];
        return $data;
    }
}
