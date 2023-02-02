<?php

namespace App\Traits;

use App\Jobs\SendEmailToUser;

/*
|
| This trait will be used for any bonus we sent to clients.
|
*/

trait WelcomeEmail
{
    protected function welcomeEmail($data)
    {
        $userEmailTemplateKey = 'user_welcome';
        $filterMessageVariables = [];
        $requestFilteredMessage = [];
        SendEmailToUser::dispatch($userEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);
    }
}
