<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        if (Schema::hasTable('site_settings')) {
            $emailServices = SiteSetting::latest()->get()->pluck('value', 'type');

            if ($emailServices) {
                $config = array(
                    'driver'     => $emailServices['mail_driver'] ?? '',
                    'host'       => $emailServices['mail_host'] ?? '',
                    'port'       => $emailServices['mail_port'] ?? '',
                    'username'   => $emailServices['mail_username'] ?? '',
                    'password'   => $emailServices['mail_password'] ?? '',
                    'encryption' => $emailServices['mail_encryption'] ?? '',
                    'from'       => array('address' => $emailServices['mail_email'] ?? '', 'name' => $emailServices['mail_name'] ?? ''),
                    'sendmail'   => '/usr/sbin/sendmail -bs',
                    'pretend'    => false,
                );

                Config::set('mail', $config);
            }
        }
    }
}
