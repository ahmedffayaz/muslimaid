<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserVerify;
use App\Traits\UserBonus;
use Illuminate\Support\Str;
use DB;

class VerifyController extends Controller
{
    use UserBonus;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function store_function(){
        $datas = DB::select("SELECT * From store WHERE store.id BETWEEN 4368 AND 5000  ORDER BY store.id ASC");
        $data_arr = array();
        $i = 0;
        $j = 0;
        foreach($datas as $data){
            $i = $i + 1;
            $j = $j + 1;
            $data_arr[$j - 1]['id'] = $data->id;
            $data_arr[$j - 1]['network_id']  = $data->network_id;
            $data_arr[$j - 1]['advertiser_id']  = '';
            $data_arr[$j - 1]['name']  = $data->title;
            $data_arr[$j - 1]['description']  = '"'.$data->description.'"';
            $data_arr[$j - 1]['slug']  = Str::slug($data->title);
            $data_arr[$j - 1]['terms_conditions']  = $data->terms_and_conditions;
            $data_arr[$j - 1]['extra_info']  = '';
            $data_arr[$j - 1]['tracking_url']  = $data->deeplink;
            $data_arr[$j - 1]['store_url']  = $data->website_url;
            $data_arr[$j - 1]['network_status']  = '';
            $data_arr[$j - 1]['status_description']  = ''; 
            $data_arr[$j - 1]['override_cashback']  = 0;
            $data_arr[$j - 1]['override_categories']  = 0;
            $data_arr[$j - 1]['feature_homepage']  = 0;
            $data_arr[$j - 1]['feature_sidebar']  = 0;
            $data_arr[$j - 1]['editor_pick']  = 0;
            $data_arr[$j - 1]['cashback_percentage']  = $data->cashback_percentage;
            $data_arr[$j - 1]['status']  = ($data->status == 'active' ? 1 : 0);
            $data_arr[$j - 1]['is_fake'] = 0;
            $data_arr[$j - 1]['address'] = $data->address; 
            $data_arr[$j - 1]['city']  = $data->city_id;
            $data_arr[$j - 1]['postal_code'] = $data->postcode;
            $data_arr[$j - 1]['latitude']  = $data->latitude;
            $data_arr[$j - 1]['longitude']  = $data->longitude;
            $data_arr[$j - 1]['rating']  = 0;
            $data_arr[$j - 1]['created_at']  = $data->date_created;
            $data_arr[$j - 1]['updated_at']  = $data->date_updated;
            $data_arr[$j - 1]['deleted_at']  = '';
        }
       return $data_arr;
    }

    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();

        if (!is_null($verifyUser)) {
            $user = $verifyUser->user;

            if (!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();

                $bonusStatus = 3;

                $this->welcomBonus($user, $bonusStatus);

                if (!empty($verifyUser->user->referred_by)) {
                    $this->referralBonus($verifyUser->user->referred_by, $bonusStatus);
                }

                session()->flash('success', 'Your e-mail is verified. You can now login.');
            } else {
                session()->flash('success', 'Your e-mail is already verified. You can now login.');
            }
        } else {
            session()->flash('error', 'Sorry your email cannot be identified.');
        }

        return redirect()->route('login');
    }
}
