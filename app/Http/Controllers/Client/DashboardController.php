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
        ], [
            'firstname.required' => 'First name is required.',
            'lastname.required' => 'Last name is required.'
        ]);

        $user = auth()->user();

        $avatarImage = $user->avatar;
        if ($request->hasFile('avatar')) {
            $avatarImage = storeUserAvatar($request->file('avatar'), $avatarImage);
        }
        $user->update([
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'date_of_birth' => formatDateForUk($request->date_of_birth),
            'phone' => $request->phone,
            'address' => $request->address,
            'address_2' => $request->address_2,
            'street' => $request->street,
            'country_id' => $request->country_id,
            'postal_code' => $request->postal_code,
            'avatar' => $avatarImage,
            'title' => $request->title,
        ]);

        flash()->success('User updated successfully');
        return redirect()->back();
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
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

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
        return view('frontend.client-dashboard.cashouts', compact('user'));
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
        })->pluck('id');
        $favoriteStores = auth()->user()->favoriteStores()
            ->whereNotIn('stores.id', $cashblackStoreIds)
            ->paginate(20);
        return view('frontend.client-dashboard.favorite-stores', compact('favoriteStores', 'title'));
    }

    public function favoriteCashbackStores()
    {
        $title = "favorite_cashblack_to_door";
        $favoriteStores = auth()->user()->favoriteStores()->whereHas('categories', function ($query) {
            $query->where('slug', 'cashblack-to-your-door');
        })->paginate(20);
        return view('frontend.client-dashboard.favorite-stores', compact('favoriteStores', 'title'));
    }
    public function addFavorite(Request $request)
    {
        $model = Store::find($request->storeId);
        if ($model) {
            Auth::user()->favoriteStores()->syncWithoutDetaching([$model->id]);
        }
        return response()->json(
            ['message' => 'Store added to favorite list.', 'type' => 'success']
        );
    }

    public function removeFavorite(Request $request)
    {
        $model = Store::find($request->storeId);
        if ($model) {
            Auth::user()->favoriteStores()->detach($model->id);
        }
        return response()->json(
            ['message' => 'Store removed from favorite list.', 'type' => 'info']
        );
    }
}
