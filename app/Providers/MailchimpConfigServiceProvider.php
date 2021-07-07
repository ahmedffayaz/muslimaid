<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SiteSetting;

class MailchimpConfigServiceProvider extends ServiceProvider
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
                $mailchimpApiconfig = array(
                    'apiKey'      => $configServices['mailchimp_api_key'] ?? '',
                    'defaultListName' => 'subscribers',
                );

                $mailchimpListconfig = array(
                    'id'      => $configServices['mailchimp_list_id'] ?? '',
                );

                \Config::set('newsletter', $mailchimpApiconfig);
                \Config::set('newsletter.lists.subscribers.id', $mailchimpListconfig['id']);
            }
        }
    }
}
