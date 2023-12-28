<?php

namespace App\Traits;

/*
|
| This trait will be used for SendGrid we sent to clients.
|
*/

trait SendGrid
{
    protected function sendGridRegistrationList($user)
    {
        $settings = SiteSetting();
        if (isset($settings['sendgrid_registered_list_id']) && isset($settings['sendgrid_api_key']) && !empty($settings['sendgrid_registered_list_id']) && !empty($settings['sendgrid_api_key'])) {
            $requestBody = [
                'list_ids' => [
                    isset($settings['sendgrid_registered_list_id']) ? $settings['sendgrid_registered_list_id'] : "",
                ],
                'contacts' => [
                    [
                        'email' => $user->email,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                    ]
                ]
            ];
            $apiKey = isset($settings['sendgrid_api_key']) ? $settings['sendgrid_api_key'] : "";
            $sg = new \SendGrid($apiKey);

            $response = $sg->client->marketing()->contacts()->put($requestBody);
            if ($response->statusCode() != 201 && $response->statusCode() != 202) {
                return redirect()->route('login')->with(['error' => 'Something went wrong!']);
            }
        }
    }
}
