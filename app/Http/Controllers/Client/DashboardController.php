<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Store;
use App\Models\Cashout;
use App\Models\Country;
use App\Models\Category;
use App\Models\ExitClick;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Appeal;
use App\Models\UserMeta;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $items =  $user->cashbacks()
            ->where(function ($query) {
                $query->whereNotNull('store_id')
                    ->whereHas('store')
                    ->orWhereNull('store_id');
            })->latest()->limit(5)->get();

        return view('frontend.client-dashboard.dashboard', compact('user', 'items'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        $countries = Country::where('status','1')->get();
        return view('frontend.client-dashboard.edit-profile', compact('user','countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'firstname' => 'required|regex:/^[A-Za-z ]+$/',
            'lastname' => 'required|regex:/^[A-Za-z ]+$/',
            'phoneNumber' => 'nullable|regex:/^\+44\d{10}$/',
            'appeal_id' => 'nullable|integer'
        ], [
            'firstname.required' => 'First name is required.',
            'lastname.required' => 'Last name is required.',
            'phoneNumber.regex' => 'The phone number must be valid UK phone number'
        ]);

        try {
            $user = auth()->user();

            $avatarImage = $user->avatar;
            if ($request->hasFile('avatar')) {
                $avatarImage = storeUserAvatar($request->file('avatar'), $avatarImage);
            }

            DB::beginTransaction();

            $user->update([
                'first_name' => $request->firstname,
                'last_name' => $request->lastname,
                'date_of_birth' => $request->date_of_birth != null ? formatDateForUk($request->date_of_birth) : $request->date_of_birth,
                'phone' => $request->phoneNumber,
                'address' => $request->address,
                'address_2' => $request->address_2,
                'street' => $request->street,
                'country_id' => $request->country_id,
                'postal_code' => $request->postal_code,
                'avatar' => $avatarImage,
                'title' => $request->title,
            ]);

            foreach ($request->input() as $key => $value) {
                // Define key mappings
                $keyMappings = [
                    'date_of_birth' => 'dob',
                    'phoneNumber' => 'phone',
                ];
                // Exclude email and country_id from being saved in UserMeta
                if ($key !== 'email' && $key !== 'country_id' && $key !== '_token' && $key !== '_method'
                    && $key !== 'profile-user-id' && $key !== 'sessionReceived' && $key !== 'profile-email'
                    && $key !== 'user_email' && !empty($key)) {

                    // Determine the target type (mapping or original key)
                    $type = array_key_exists($key, $keyMappings) ? $keyMappings[$key] : $key;

                    // Check if $value is not empty or null
                    if (!empty($value) || !is_null($value)) {
                        UserMeta::updateOrCreate([
                            'user_id' => auth()->user()->id,
                            'type' => $type
                        ], [
                            'value' => $value
                        ]);
                    }
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'status' => JsonResponse::HTTP_OK,
                    'success' => "User updated successfully"
                ], JsonResponse::HTTP_OK);
            }

            flash()->success('User updated successfully');
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json([
                    'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                    'error' => $e->getMessage() . ' Something went wrong'
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            flash()->error('Something went wrong');
            return redirect()->back();
        }

    }

    public function cashback()
    {
        $user = Auth::user();
        $stores = Store::where('status', 'active')->orderBy('name', 'asc')
            ->has('commissions')->whereHas('commissions', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
        return view('frontend.client-dashboard.cashback', compact('user',  'stores'));
    }

    public function searchCashback(Request $request)
    {
        $user = Auth::user();
        $cashbacks = UserCashback::where('user_id', $user->id);
        if (isset($request->status)) {
            $cashbacks->where('status', $request->status);
        }
        if (isset($request->store_id)) {
            $cashbacks->whereHas('store', function ($query) use ($request) {
                $query->where('id', $request->store_id);
            });
        }
        if (isset($request->date_from) && isset($request->date_to)) {
            $cashbacks->whereBetween('event_date', [$request->date_from, $request->date_to]);
        }
        $cashbacks = $cashbacks->latest()->paginate(20);
        return view('frontend.client-dashboard.cashback-table', compact('cashbacks'));
    }

    public function clicks()
    {
        $user = Auth::user();
        $userId = $user->id;
        $stores  = Store::whereHas('clicks', function ($query)  use ($userId) {
            $query->where('user_id', $userId);
        })->get();
        return view('frontend.client-dashboard.clicks', compact('user', 'stores'));
    }
    public function searchClick(Request $request)
    {
        $user = Auth::user();
        $clicks = ExitClick::where('user_id', $user->id);

        if (isset($request->store_id)) {
            $clicks->whereHas('store', function ($query) use ($request) {
                $query->where('id', $request->store_id);
            });
        }
        if (isset($request->date_from) && isset($request->date_to)) {
            $clicks->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }
        if (isset($request->conversion) && $request->conversion != '0') {
            if ($request->conversion == 'yes') {
                $clicks->whereHas('cashback');
            } else if ($request->conversion == 'no') {
                $clicks->whereDoesntHave('cashback');
            }
        }
        $clicks = $clicks->latest()->paginate(20);
        return view('frontend.client-dashboard.click-table', compact('clicks'));
    }


    public function changePassword()
    {
        return view('frontend.client-dashboard.change-password');
    }

    public function savePassword(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'password' => ['required', 'confirmed']
        ];

        $passwordRules = env('PASSWORD_VALIDATION', '');
        if(!empty($passwordRules)){
            $additionalRules = explode('|', $passwordRules);
            $rules['password'] = array_merge($rules['password'], $additionalRules);
        }else {
            $rules['password'][] = 'string';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            flash()->error($validator->errors()->first());
            return redirect()->back();
        }
        if (!Hash::check($request->old_password, $user->password)) {
            flash()->error('Old password does not match with our records');
            return redirect()->back();
        } else if ($request->password != $request->password_confirmation) {
            flash()->error('Password confirmation do not match');
            return redirect()->back();
        } else {
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            flash()->success('Password changed successfully');
            return redirect()->back();
        }
    }
    public function cashouts()
    {
        $user = Auth::user();
        $appeals = Appeal::all();
        return view('frontend.client-dashboard.cashouts', compact('user', 'appeals'));
    }
    public function searchCashouts(Request $request)
    {
        $user = Auth::user();
        $cashouts = Cashout::where('user_id', $user->id);
        if (isset($request->status)) {
            $cashouts->where('status', $request->status);
        }
        if (isset($request->payment_method)) {
            $cashouts->where('payment_method', $request->payment_method);
        }
        if(isset($request->appeal)){
            $cashouts->whereHas('metaData', function ($query) use ($request) {
                $query->where('type', 'appeal_id')->where('value', $request->appeal);
            });
        }
        if (isset($request->date_from) && isset($request->date_to)) {
            $cashouts->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }
        $cashouts = $cashouts->latest()->paginate(20);
        return view('frontend.client-dashboard.cashout-table', compact('cashouts'));
    }

    public function favoriteStores()
    {
        $title = "favorite_stores";
        $cashblackStoreIds = Store::whereHas('categories', function ($query) {
            $query->where('slug', 'cashblack-to-your-door');
        })->whereStatus('active')->pluck('id');
        $favoriteStores = auth()->user()->favoriteStores()->where('status', 'active')
            ->whereNotIn('stores.id', $cashblackStoreIds)
            ->paginate(20);
        return view('frontend.client-dashboard.favorite-stores', compact('favoriteStores', 'title'));
    }

    public function favoriteCashbackStores()
    {
        $title = "favorite_cashblack_to_door";
        $favoriteStores = auth()->user()->favoriteStores()->where('status', 'active')->whereHas('categories', function ($query) {
            $query->where('slug', 'cashblack-to-your-door');
        })->paginate(20);
        return view('frontend.client-dashboard.favorite-stores', compact('favoriteStores', 'title'));
    }
    public function addFavorite(Request $request)
    {
        $model = Store::find($request->storeId);
        if ($model) {
            Auth::user()->favoriteStores()->where('status', 'active')->syncWithoutDetaching([$model->id]);
        }
        return response()->json(
            ['message' => 'Store added to favorite list.', 'type' => 'success']
        );
    }

    public function removeFavorite(Request $request)
    {
        $model = Store::whereStatus('active')->find($request->storeId);
        if ($model) {
            Auth::user()->favoriteStores()->where('status', 'active')->detach($model->id);
        }
        return response()->json(
            ['message' => 'Store removed from favorite list.', 'type' => 'info']
        );
    }

    public function appeals(Request $request)
    {
        try {
            $appeals = Appeal::whereStatus(1)->get();
            $requestData = $request->all();

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'appeals' => view('frontend.client-dashboard.appeal-form', compact('appeals', 'requestData'))->render()
            ], JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage() . ' Something went wrong'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
