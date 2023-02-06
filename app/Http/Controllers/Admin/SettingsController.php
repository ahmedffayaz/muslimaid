<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Throwable;
use App\Models\Currency;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Models\CashbackStatus;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;

class SettingsController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view settings', ['only' => ['index', 'show']]);
        $this->middleware('permission:edit settings', ['only' => ['edit', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route = 'index';
        $settings = SiteSetting::latest()->get()->pluck('value', 'type');

        $currencies = Currency::all();
        $sc = Currency::where('id', $settings['currency'])->pluck('symbol')->first();
        return view('admin-dashboard.settings.settings', compact('settings', 'route', 'currencies', 'sc'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-dashboard.settings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
       
        $inputs = $request->all();
        $inputs['type'] = str_replace([' ', '-', '.'], '_', $request->input('type'));
        $setting = SiteSetting::create($inputs);
        flash()->success('setting saved successfully');
        return redirect()->route('admin.settings.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SiteSetting $setting)
    {
        return view('admin-dashboard.settings.edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SiteSetting $setting)
    {
        $inputs = $request->all();
        $inputs['type'] = str_replace([' ', '-', '.'], '_', $request->input('type'));
        $setting->update($inputs);
        flash()->success('setting updated successfully');
        return redirect()->route('admin.settings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SiteSetting $setting)
    {
        if ($setting->default) {
            flash()->error('default settings can not be deleted');
            return redirect()->route('admin.settings.index');
        }

        $setting->delete();

        flash()->success('setting deleted successfully');
        return redirect()->route('admin.settings.index');
    }

    function fetch(Request $request)
    {
        if ($request->ajax()) {
            $route = 'index';

            $settings = SiteSetting::latest()->paginate(20);
            return view('admin-dashboard.settings.index_data', compact('settings', 'route'))->render();
        }
    }

    public function searchSettings(Request $request, SiteSetting $settings)
    {
        $settings = $settings->newQuery();

        // Search by title.
        if ($request->input('title')) {
            $settings->where('title', 'like', '%' . $request->input('title') . '%');
        }

        // Search by key.
        if ($request->input('key')) {
            $settings->where('type', 'like', '%' . $request->input('key') . '%');
        }

        $settings = $settings->latest()->paginate(20);
        $route = 'search';

        return view('admin-dashboard.settings.index_data', compact('settings', 'route'))->render();
    }

    public function mailerSettings()
    {
        $settings = SiteSetting::latest()->get()->pluck('value', 'type');
        return view('admin-dashboard.settings.mailer_settings', compact('settings'));
    }

    public function saveSettings(Request $request)
    {
        try {
            $validated = $request->validate([
                'referral_bonus' => 'min:0|numeric', 
                'welcome_bonus' => 'min:0|numeric', 
                'min_cashout_amount'=> 'min:0|numeric',
            ], $messages = [
                'referral_bonus' => 'Value must be equal to or greater than 0.',  
                'welcome_bonus' => 'Value must be equal to or greater than 0.', 
                'min_cashout_amount' => 'Value must be equal to or greater than 0.',  
            ]);
            $request->offsetUnset('_method');
            $request->offsetUnset('_token');

            foreach ($request->input() as $key => $value) {
                SiteSetting::updateOrCreate([
                    'type'   => $key,
                    'title'  => ucwords(
                        str_replace('_', ' ', $key)
                    )
                ], [
                    'value'     => $value
                ]);
            }

            SiteSetting::updateOrCreate([
                'type'   => 'payment_method_paypal',
                'title'  => 'Payment Method Paypal',

            ], [
                'value'     =>  $request->has('payment_method_paypal') ? 1 : 0
            ]);

            SiteSetting::updateOrCreate([
                'type'   => 'payment_method_bank',
                'title'  => 'Payment Method Bank',

            ], [
                'value'     =>  $request->has('payment_method_bank') ? 1 : 0
            ]);

            SiteSetting::updateOrCreate([
                'type'   => 'payment_method_charity',
                'title'  => 'Payment Method Charity',

            ], [
                'value'     =>  $request->has('payment_method_charity') ? 1 : 0
            ]);

            if ($request->has('dashboard_logo')) {
                $imageName = 'dashboard_logo_' . time() . '.' . $request->dashboard_logo->extension();
                $request->dashboard_logo->storeAs('public/dashboard/images/logo', $imageName);

                SiteSetting::updateOrCreate([
                    'type'   => 'dashboard_logo',
                    'title'  => 'Dashboard Logo',

                ], [
                    'value'     =>  $imageName
                ]);
            }

            if ($request->has('website_logo')) {
                $imageName = 'website_logo_' . time() . '.' . $request->website_logo->extension();
                $request->website_logo->storeAs('public/dashboard/images/logo', $imageName);

                SiteSetting::updateOrCreate([
                    'type'   => 'website_logo',
                    'title'  => 'Website Logo',

                ], [
                    'value'     =>  $imageName
                ]);
            }

            if ($request->has('favicon')) {
                $imageName = 'favicon_' . time() . '.' . $request->favicon->extension();
                $request->favicon->storeAs('public/dashboard/images/logo', $imageName);

                SiteSetting::updateOrCreate([
                    'type'   => 'favicon',
                    'title'  => 'Favicon',

                ], [
                    'value'     =>  $imageName
                ]);
            }

            if ($request->has('dashboard_small_logo')) {
                $imageName = 'dashboard_small_logo_' . time() . '.' . $request->dashboard_small_logo->extension();
                $request->dashboard_small_logo->storeAs('public/dashboard/images/logo', $imageName);

                SiteSetting::updateOrCreate([
                    'type'   => 'dashboard_small_logo',
                    'title'  => 'Small Dashboare Logo',

                ], [
                    'value'     =>  $imageName
                ]);
            }

            return array(
                'message' => 'Settings saved',
                'response' => 'success'
            );
        } catch (Throwable $th) {
            return array(
                'message' => $th->getMessage(),
                'response' => 'error'
            );
        }
    }

    public function permissions()
    {
        if (!Auth::user()->hasRole('admin')) {
            $roles = Role::whereNotIn('name', ['admin', 'user'])->get();
        } else {
            $roles = Role::whereNotIn('name', ['admin'])->get();
        }

        $permissions = Permission::all();

        return view('admin-dashboard.settings.permissions', compact('roles', 'permissions'));
    }

    public function updatePermissions(Request $request)
    {
        try {
            $request->offsetUnset('_method');
            $request->offsetUnset('_token');

            foreach ($request->input() as $key => $permissions) {
                $role = Role::findByName($key);
                $role->syncPermissions($permissions);
            }

            flash()->success('Permissions updated successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            flash()->error('Something went wrong!');
            return redirect()->back();
        }
    }

    public function menu()
    {
        return view('admin-dashboard.menus.index');
    }

    public function cashbackStatusNames()
    {
        $statuses = CashbackStatus::all()->toArray();
        return view('admin-dashboard.settings.cashback_status_names', compact('statuses'));
    }

    public function saveCashbackStatuses(Request $request)
    {
        foreach ($request->input('status') as $key => $status) {
            CashbackStatus::find($key)->update([
                'status' => $status
            ]);
        }

        flash()->success('Cashback status titles updated successfully');
        return redirect()->back();
    }

    public function maintenance(Request $request)
    {
        try {
            if ($request->input('maintenance')) {
                Artisan::call('down');
                return array(
                    'message' => 'Maintenance Mode enabled',
                    'response' => 'success'
                );
            } else {
                Artisan::call('up');
                return array(
                    'message' => 'Maintenance Mode disabled',
                    'response' => 'success'
                );
            }
        } catch (\Throwable $th) {
            return array(
                'message' => 'Something went wrong!',
                'response' => 'error'
            );
        }
    }
}
