<?php

namespace App\Traits;

use SendGrid;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

/*
|
| This trait will be used for subscribe newsletter we sent to clients.
|
*/

trait SubscribeNewsletter
{
    function registerNewsletter($request) {
        try {
            $settings = SiteSetting();
            if (!empty($settings['sendgrid_api_key']) && !empty($settings['sendgrid_newsletter_list_id']) &&
                isset($request['type']) && $request['type'] === 'register' && isset($request['user']))
            {
                $user = $request['user'];
                $requestBody = [
                    'list_ids' => [$settings['sendgrid_registered_list_id']],
                    'contacts' => [
                        [
                            'email' => $user['email'],
                            'first_name' => $user['first_name'],
                            'last_name' => $user['last_name'],
                        ]
                    ]
                ];
                $sg = new SendGrid($settings['sendgrid_api_key']);
                $response = $sg->client->marketing()->contacts()->put($requestBody);
                if ($response->statusCode() != 201 && $response->statusCode() != 202) {
                    Log::error('Get error on while user register account on SendGrid registration.');
                }
            }

            if (!empty($settings['mailchimp_api_key']) && !empty($settings['mailchimp_list_id'])) {
                //
            }

            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong while subscribing to newsletter.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            Log::error('Get error while sending request for newsletter on registration: ' . $e->getMessage());
        }
    }

    function subscribeNewsletter($request)
    {
        try {
            $settings = SiteSetting();
            if (!empty($settings['sendgrid_api_key']) && !empty($settings['sendgrid_newsletter_list_id']) &&
                $request->has('type') && $request->input('type') === 'subscribe-newsletter'
            ) {
                if ($request->has('email') && $request->has('name')) {
                    $user = User::where('email', $request->input('email'))->first();
                    $nameArray = explode(' ', request()->input('name'));
                    if (!empty($user)) {
                        $response = $this->sendGrid($settings, $user, $nameArray);
                        if ($response->statusCode() == 201 || ($response->statusCode() == 202)) {
                            $user->update(['email_preference' => true]);
                            Session::put('allowSendgrid', '1');

                            return $this->newsletterJoiningMessage($request);
                        }
                    } else {
                        $response = $this->sendGrid($settings, $user, $request->input('email'), $nameArray);
                        if ($response->statusCode() == 201 || ($response->statusCode() == 202)) {
                            return $this->newsletterJoiningMessage($request);
                        }
                    }
                }
                return $this->errorMessage($request);
            }

            if (!empty($settings['mailchimp_api_key']) && !empty($settings['mailchimp_list_id'])) {
                //
            }

            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong while subscribing to newsletter.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            Log::error('Get error while sending request for newsletter: ' . $e->getMessage());
            return $this->errorMessage($request);
        }
    }

    function profileSubscribeNewsletter($request)
    {
        try {
            $settings = SiteSetting();
            if (!empty($settings['sendgrid_api_key']) && !empty($settings['sendgrid_newsletter_list_id']) &&
                $request->has('type') && $request->input('type') === 'profile-subscribe-newsletter'
            ) {
                $user = auth()->user();
                $response = $this->sendGrid($settings, $user);

                if ($response->statusCode() == 201 || ($response->statusCode() == 202)) {
                    $user = User::where('id', $user->id)->first();
                    if (!empty($user)) {
                        $user->update(['email_preference' => true]);
                        Session::put('allowSendgrid', '1');

                        return $this->newsletterJoiningMessage($request);
                    }
                }
                return $this->errorMessage($request);
            }

            if (!empty($settings['mailchimp_api_key']) && !empty($settings['mailchimp_list_id'])) {
                //
            }

            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong while subscribing to newsletter.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            Log::error('Get error while sending request for newsletter: ' . $e->getMessage());
            return $this->errorMessage($request);
        }
    }

    function unsubscribeNewsletter($request)
    {
        try {
            $settings = SiteSetting();
            if (!empty($settings['sendgrid_api_key']) && !empty($settings['sendgrid_newsletter_list_id']) &&
                $request->has('type') && $request->input('type') === 'profile-unsubscribe-newsletter'
            ) {
                $user = auth()->user();
                $sg = new SendGrid($settings['sendgrid_api_key']);
                $response = $sg->client->marketing()->contacts()->get();

                if ($response->statusCode() == 200) {
                    $email = $user->email;
                    $result = json_decode($response->body(), true);
                    $result = $result['result'];

                    $filterResult = array_filter($result, function ($contact) use ($email) {
                        return $contact['email'] == $email;
                    });

                    if (!empty($filterResult)) {
                        $filterResult = array_values($filterResult);

                        $sgRecipientId = $filterResult[0];
                        $queryParams = [
                            'contact_ids' => $sgRecipientId['id']
                        ];
                        $response = $sg->client->marketing()->lists()->_($settings['sendgrid_newsletter_list_id'])->contacts()->delete(null, $queryParams);

                        $user = User::find($user->id);
                        if (!empty($user)) {
                            $user->update(['email_preference' => false]);
                            Session::put('allowSendgrid', '0');
                            return $this->unsubscribeNewsletterMessage($request);
                        }
                    }
                }
                return $this->errorMessage($request);
            }

            if (!empty($settings['mailchimp_api_key']) && !empty($settings['mailchimp_list_id'])) {
                //
            }

            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong while subscribing to newsletter.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            Log::error('Get error while sending request for newsletter: ' . $e->getMessage());
            return $this->errorMessage($request);
        }
    }

    function updateNewsletter($request)
    {
        try {
            $settings = SiteSetting();
            if (!empty($settings['sendgrid_api_key']) && !empty($settings['sendgrid_newsletter_list_id']) &&
                $request->has('type') && $request->input('type') === 'update-newsletter'
            ) {
                $user = auth()->user();
                $response = $this->sendGrid($settings, $user);

                if ($response->statusCode() == 201 || ($response->statusCode() == 202)) {
                    $user = User::where('id', $user->id)->first();
                    if (!empty($user)) {
                        $user->update(['email_preference' => true]);
                        Session::put('allowSendgrid', '1');

                        return $this->newsletterJoiningMessage($request);
                    }
                }
                return $this->errorMessage($request);
            }

            if (!empty($settings['mailchimp_api_key']) && !empty($settings['mailchimp_list_id'])) {
                //
            }

            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong while subscribing to newsletter.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            Log::error('Get error while sending request for newsletter: ' . $e->getMessage());
            return $this->errorMessage($request);
        }
    }

    private function sendGrid($settings, $user, $email = null, $nameArray = null)
    {
        $nameArray =  !empty($nameArray) && !is_null($nameArray) ? $nameArray : '';
        $country = !empty($user->country_id) && is_int($user->country_id) ? $user->country->name : '';
        $dob = !empty($user->date_of_birth) && Carbon::parse($user->date_of_birth)->format('Y-m-d') === $user->date_of_birth ? Carbon::parse($user->date_of_birth)->isoFormat('Do MMMM YYYY') : '';

        $state = '';
        $phone = '';
        if (isset($user) && !empty($user)) {
            $state = !empty($user->metaData->where('type', 'state')->pluck('value')->first()) ? optional($user->metaData)->where('type', 'state')->pluck('value')->first() : '';
            $phone = !empty($user->metaData->where('type', 'phone')->pluck('value')->first()) ? optional($user->metaData)->where('type', 'phone')->pluck('value')->first() : '';
        }

        $customFields = [
            [
                'name' => 'title',
                'field_type' => 'Text',
            ],
            [
                'name' => 'date_of_birth',
                'field_type' => 'Text',
            ],
        ];

        $requestBody = [
            'list_ids' => [$settings['sendgrid_newsletter_list_id']],
            'contacts' => [
                [
                    'email' => !empty($user->email) ? $user->email : (isset($email) ? $email : ''),
                    'first_name' => !empty($user->first_name) ? $user->first_name : (isset($nameArray[0]) ? $nameArray[0] : ''),
                    'last_name' => !empty($user->last_name) ? $user->last_name : (isset($nameArray[1]) ? $nameArray[1] : ''),
                    'address_line_1' => !empty($user->address) ? $user->address : '',
                    'address_line_2' => !empty($user->address_2) ? $user->address_2 : '',
                    'city' => !empty($user->street) ? $user->street : '',
                    'state_province_region' => $state,
                    'country' => $country,
                    'postal_code' => !empty($user->postal_code) ? $user->postal_code : '',
                    'phone_number' => $phone,
                    'custom_fields' => [
                        'title' => !empty($user->title) ? $user->title : '',
                        'date_of_birth' => $dob,
                    ],
                ],
            ],
        ];

        $sg = new SendGrid($settings['sendgrid_api_key']);

        foreach ($customFields as $customField) {
            $sg->client->marketing()->field_definitions()->post($customField);
        }

        return $sg->client->marketing()->contacts()->put($requestBody);
    }

    private function newsletterJoiningMessage($request)
    {
        if ($request->ajax()) {
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'Thanks for joining our newsletter.'
            ], JsonResponse::HTTP_OK);
        }

        flash()->success('Thanks for joining our newsletter.');
        return redirect()->back();
    }

    private function unsubscribeNewsletterMessage($request)
    {
        if ($request->ajax()) {
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'You have successfully unsubscribe newsletter.'
            ], JsonResponse::HTTP_OK);
        }

        flash()->success('You have successfully unsubscribe newsletter.');
        return redirect()->back();
    }

    private function errorMessage($request)
    {
        if ($request->ajax()) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong, try again later.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        flash()->error('Something went wrong, try again later.');
        return redirect()->back();
    }

    private function apiErrorMessage($request)
    {
        if ($request->ajax()) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong while subscribing to newsletter.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        flash()->error('Something went wrong while subscribing to newsletter.');
        return redirect()->back();
    }
}
