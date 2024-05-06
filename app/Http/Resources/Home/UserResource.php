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
        if($this->first_name && $this->last_name && $this->email && $this->phone && $this->address && $this->date_of_birth && $this->street && $this->country_id && $this->postal_code) {
            $is_profile_complete = 1;
        } else{
           $is_profile_complete = 0;
        }

        if ($this->avatar == 'default.png' || $this->avatar == NULL || $this->avatar == '') {
            $avatar = asset('storage/__asset/img/default-avatar.png');
        } else {
            $avatar = !Storage::exists('public/users/images/avatar/' . $this->avatar)
                ? asset('storage/__asset/img/default-avatar.png')
                : asset('storage/users/images/avatar/' . $this->avatar);
        }

        $preferredAppeal = $this->metaData()->whereType('appeal_id')->first();

        $preferredAppeal = (isset($preferredAppeal) && $preferredAppeal->value == 0)
            ? ''
            : ($this->appeal ? $this->appeal->title : '');

        $user = [
            "id" => $this->id,
            "title" => empty($this->title) ? '' : $this->title,
            "name" => $this->first_name . ' ' . $this->last_name,
            "user_type" => ucwords($userRoles),
            "permission" =>  ucwords($userPermissions),
            "email" => $this->email,
            "paypal_email" =>  empty($this->paypalInfo) ? '' : $this->paypalInfo->paypal_email,
            "ref_id" => empty($this->referred_by) ? '' : $this->referred_by,
            "status" => $this->status,
            "dob" => date('d-M-Y', strtotime($this->date_of_birth)),
            "address" => empty($this->address) ? '' : $this->address,
            "address_2" => empty($this->address_2) ? '' : $this->address_2,
            "street" => empty($this->street) ? '' : $this->street,
            'country_id' => empty($this->country_id) ? '' : $this->country_id,
            'postal_code' => empty($this->postal_code) ? '' : $this->postal_code,
            "avatar" => $avatar,
            "avatar_type" => "upload",
            "is_verify" => $this->is_email_verified ? 'Yes' : 'No',
            "date_updated" => date('d-M-Y', strtotime($this->updated_at)),
            "date_created" => date('d-M-Y', strtotime($this->created_at)),
            "otp" => empty($this->otp) ? '' : $this->otp,
            "firstname" => empty($this->first_name) ? '' : $this->first_name,
            "lastname" =>  empty($this->last_name) ? '' : $this->last_name,
            "phone" => $this->phone,
            "account_name" => empty($this->bankInfo) ? '' : $this->bankInfo->account_name,
            "bank_title" => empty($this->bankInfo) ? '' : $this->bankInfo->bank_title,
            "account_number" => empty($this->bankInfo) ? '' : $this->bankInfo->account_number,
            "bank_sort_code" => empty($this->bankInfo) ? '' : $this->bankInfo->bank_sort_code,
            "bic" => empty($this->bankInfo) ? '' : $this->bankInfo->bic,
            "referral_code" => empty($this->referred_by) ? '' : $this->referred_by,
            "email_preference" => $this->email_preference ? 'Yes' : 'No',
            "token" => $token,
            "balance" => currency($this->availableBalance(3)),
            "state" => $this->metaData->where('type', 'state')->pluck('value')->first(),
            "is_profile_complete"=>  $is_profile_complete,
            'preffered_appeal' => $preferredAppeal,
        ];

        $data = [
            'user' => $user,
        ];
        return $data;
    }
}
