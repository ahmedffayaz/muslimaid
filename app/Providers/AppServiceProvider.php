<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;

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
            $recaptchaSiteKey = SiteSetting::whereType('google_recaptcha_site_key')->first();
            $recaptchaSecretKey = SiteSetting::whereType('google_recaptcha_secret_key')->first();

            if (!empty($recaptchaSiteKey) && !empty($recaptchaSecretKey)) {
                Config::set('captcha.sitekey', $recaptchaSiteKey->value);
                Config::set('captcha.secret', $recaptchaSecretKey->value);
            }
        }
        Collection::macro('paginate', function($perPage, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage), // $items
                $this->count(),                  // $total
                $perPage,
                $page,
                [                                // $options
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}
