<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SiteSetting;

class SocialLoginConfigServiceProvider extends ServiceProvider
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
        if(\Schema::hasTable('site_settings')){
            $configServices=SiteSetting::latest()->get()->pluck('value','type');

            if ($configServices) {
                $facebookconfig = array(
                    'client_id'         => $configServices['facebook_client_id'] ?? '',
                    'client_secret'     => $configServices['facebook_client_secret'] ?? '',
                    'redirect'          => $configServices['facebook_url'] ?? '',
                );

                $googleconfig = array(
                    'client_id'         => $configServices['google_client_id'] ?? '',
                    'client_secret'     => $configServices['google_client_secret'] ?? '',
                    'redirect'          => $configServices['google_url'] ?? '',
                );
    
                \Config::set('services.facebook', $facebookconfig);
                \Config::set('services.google', $googleconfig);
            }
        }
    }
}
