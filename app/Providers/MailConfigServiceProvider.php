<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SiteSetting;

class MailConfigServiceProvider extends ServiceProvider
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
        $emailServices = SiteSetting::latest()->get()->pluck('value','type');

        if ($emailServices) {
            $config = array(
                'driver'     => $emailServices['mail_driver'] ?? '',
                'host'       => $emailServices['mail_host'] ?? '',
                'port'       => $emailServices['mail_port'] ?? '',
                'username'   => $emailServices['mail_username'] ?? '',
                'password'   => $emailServices['mail_password'] ?? '',
                'encryption' => null,
                'from'       => array('address' => $emailServices['mail_email'] ?? '', 'name' => $emailServices['mail_name'] ?? ''),
                'sendmail'   => '/usr/sbin/sendmail -bs',
                'pretend'    => false,
            );

            \Config::set('mail', $config);
        }
    }
    }
}
