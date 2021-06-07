<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // $settings = \App\Models\SiteSetting::latest()->get()->pluck('value','type');
        // config()->set('settings',$settings);
        // $currency = \App\Models\Currency::where('id',$settings['currency'])->pluck('symbol')->first();
        // config()->set('currency',$currency);
    }
}
