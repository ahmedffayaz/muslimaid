<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Schema::defaultStringLength(125);

        if (Schema::hasTable('site_settings')) {
            $recaptchaSiteKey = SiteSetting::whereType('g_recaptcha_site_key')->first();
            $recaptchaSecretKey = SiteSetting::whereType('g_recaptcha_secret_key')->first();

            if (!empty($recaptchaSiteKey) && !empty($recaptchaSecretKey)) {
                Config::set('captcha.sitekey', $recaptchaSiteKey->value);
                Config::set('captcha.secret', $recaptchaSecretKey->value);
            }
        }
    }
}
