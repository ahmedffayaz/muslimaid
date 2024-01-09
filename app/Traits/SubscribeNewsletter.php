<?php

namespace App\Traits;

use SendGrid;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use DrewM\MailChimp\MailChimp;
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
    // Add email in register list in provider
    function registerNewsletter($request) {
        try {
            $settings = SiteSetting();
            // Add email in SendGrid register list and check provider is enable/disable
            if (getImporterYMLSettings(config('app.sendgrid_yml_path')) && !empty($settings['sendgrid_api_key']) && !empty($settings['sendgrid_newsletter_list_id']) &&
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
                // Put fields value in register contact list
                $response = $sg->client->marketing()->contacts()->put($requestBody);

                // Display error in log file if response failed
                if ($response->statusCode() != 201 && $response->statusCode() != 202) {
                    Log::error('Get error on while user register account on SendGrid registration.');
                    return;
                }
            }

            // Add email in MailChimp register list and check provider is enable/disable
            if (getImporterYMLSettings(config('app.mailchimp_yml_path')) && !empty($settings['mailchimp_api_key']) && !empty($settings['mailchimp_list_id'])) {
                $user = $request['user'];
                if (!empty($user)) {
                    return $this->mailchimp($settings, $request, false, $user, null, null, 'register');
                }
            }

            $this->apiLogErrorMessage(); // Display error in log file if provider not exist
        } catch (Exception $e) {
            Log::error('Get error while sending request for newsletter on registration: ' . $e->getMessage());
        }
    }

    // Add email in newsletter list from footer in provider
    function subscribeNewsletter($request, $isApi = false)
    {
        try {
            $settings = SiteSetting();
            // Add email in SendGrid newsletter list and check provider is enable/disable
            if (getImporterYMLSettings(config('app.sendgrid_yml_path')) && !empty($settings['sendgrid_api_key']) && !empty($settings['sendgrid_newsletter_list_id']) &&
                isset($request->type) && $request->type === 'subscribe-newsletter'
            ) {
                if (isset($request->email)) {
                    $user = User::where('email', $request->email)->first();
                    $nameArray = isset($request->name) ? explode(' ', $request->name) : '';
                    // Check email exist in database
                    if (!empty($user)) {
                        // Put fields value in newsletter contact list
                        $response = $this->sendGrid($settings, $user, null, $nameArray);

                        if ($response->statusCode() == 201 || ($response->statusCode() == 202)) {
                            $user->update(['email_preference' => true]);
                            if (!$isApi) Session::put('allowSendgrid', '1');

                            return $this->newsletterJoiningMessage($request, $isApi); // Display success message
                        }
                    } else {
                        // Put fields value in newsletter contact list
                        $response = $this->sendGrid($settings, $user, $request->email, $nameArray);
                        if ($response->statusCode() == 201 || ($response->statusCode() == 202)) {
                            return $this->newsletterJoiningMessage($request, $isApi); // Display success message
                        }
                    }
                }
                return $this->errorMessage($request, $isApi);
            }

            // Add email in MailChimp newsletter list and check provider is enable/disable
            if (getImporterYMLSettings(config('app.mailchimp_yml_path')) && !empty($settings['mailchimp_api_key']) && !empty($settings['mailchimp_list_id'])) {
                if (isset($request->email)) {
                    $user = User::where('email', $request->email)->first();
                    $nameArray = isset($request->name) ? explode(' ', $request->name) : '';
                    // Check email exist in database
                    if (!empty($user)) {
                        return $this->mailchimp($settings, $request, $isApi, $user, null, $nameArray);
                    } else {
                        return $this->mailchimp($settings, $request, $isApi, $user, $request->email, $nameArray);
                    }
                }
                return $this->errorMessage($request, $isApi);
            }

            return $this->apiErrorMessage($request, $isApi); // Display error in log file if provider not exist
        } catch (Exception $e) {
            Log::error('Get error while sending request for newsletter: ' . $e->getMessage());
            return $this->errorMessage($request);
        }
    }

    // Add email in newsletter list from profile page in provider
    function profileSubscribeNewsletter($request, $isApi = false)
    {
        try {
            $settings = SiteSetting();
            // Add email in SendGrid newsletter list and check provider is enable/disable
            if (getImporterYMLSettings(config('app.sendgrid_yml_path')) && !empty($settings['sendgrid_api_key']) && !empty($settings['sendgrid_newsletter_list_id']) &&
                isset($request->type) && $request->type === 'profile-subscribe-newsletter'
            ) {
                $user = auth()->user();
                // Put fields value in newsletter contact list
                $response = $this->sendGrid($settings, $user);

                if ($response->statusCode() == 201 || ($response->statusCode() == 202)) {
                    $user = User::where('id', $user->id)->first();
                    if (!empty($user)) {
                        // update user email preference column if user exist
                        $user->update(['email_preference' => true]);
                        // put session to update contact fields if subscribe newsletter
                        if (!$isApi) Session::put('allowSendgrid', '1');

                        return $this->newsletterJoiningMessage($request, $isApi);
                    }
                }
                return $this->errorMessage($request, $isApi);
            }

            // Add email in MailChimp newsletter list and check provider is enable/disable
            if (getImporterYMLSettings(config('app.mailchimp_yml_path')) && !empty($settings['mailchimp_api_key']) && !empty($settings['mailchimp_list_id'])) {
                $user = auth()->user();
                return $this->mailchimp($settings, $request, $isApi, $user);
            }

            return $this->apiErrorMessage($request, $isApi); // Display error in log file if provider not exist
        } catch (Exception $e) {
            Log::error('Get error while sending request for newsletter: ' . $e->getMessage());
            return $this->errorMessage($request, $isApi);
        }
    }

    // Remove email in newsletter list from profile page in provider
    function unsubscribeNewsletter($request, $isApi = false)
    {
        try {
            $settings = SiteSetting();
            // Remove email in SendGrid newsletter list and check provider is enable/disable
            if (getImporterYMLSettings(config('app.sendgrid_yml_path')) && !empty($settings['sendgrid_api_key']) && !empty($settings['sendgrid_newsletter_list_id']) &&
                isset($request->type) && $request->type === 'profile-unsubscribe-newsletter'
            ) {
                $user = auth()->user();
                $sg = new SendGrid($settings['sendgrid_api_key']);
                // Get contacts from SendGrid provider
                $response = $sg->client->marketing()->contacts()->get();

                if ($response->statusCode() == 200) {
                    $email = $user->email;
                    $result = json_decode($response->body(), true);
                    $result = $result['result'];

                    // Get current user email from SendGrid contacts
                    $filterResult = array_filter($result, function ($contact) use ($email) {
                        return $contact['email'] == $email;
                    });

                    if (!empty($filterResult)) {
                        $filterResult = array_values($filterResult);

                        $sgRecipientId = $filterResult[0];
                        /// Contact's id
                        $queryParams = [
                            'contact_ids' => $sgRecipientId['id']
                        ];

                        // Remove email from SendGrid newsletter list
                        $response = $sg->client->marketing()->lists()->_($settings['sendgrid_newsletter_list_id'])->contacts()->delete(null, $queryParams);

                        $user = User::find($user->id);
                        if (!empty($user)) {
                            // put session to update contact fields if unsubscribe newsletter
                            $user->update(['email_preference' => false]);
                            if (!$isApi) Session::put('allowSendgrid', '0');

                            return $this->unsubscribeNewsletterMessage($request, $isApi);
                        }
                    }
                }
                return $this->errorMessage($request, $isApi);
            }

            if (getImporterYMLSettings(config('app.mailchimp_yml_path')) && !empty($settings['mailchimp_api_key']) && !empty($settings['mailchimp_list_id'])) {
                $user = auth()->user();
                $mailchimp = new MailChimp($settings['mailchimp_api_key']);
                $subscriberHash = MailChimp::subscriberHash($user->email);
                $path = 'lists/' . $settings['mailchimp_list_id'] . '/members/' . $subscriberHash;
                $mailchimp->delete($path);
                if ($mailchimp->success()) {
                    $user = User::find($user->id);
                    if (!empty($user)) {
                        // put session to update contact fields if unsubscribe newsletter
                        $user->update(['email_preference' => false]);
                        if (!$isApi) Session::put('allowSendgrid', '0');

                        return $this->unsubscribeNewsletterMessage($request, $isApi);
                    }
                    return $this->unsubscribeNewsletterMessage($request, $isApi);
                }
            }

            return $this->apiErrorMessage($request, $isApi); // Display error in log file if provider not exist
        } catch (Exception $e) {
            Log::error('Get error while sending request for newsletter: ' . $e->getMessage());
            return $this->errorMessage($request);
        }
    }

    // Update contact's field in newsletter list from profile page in provider
    function updateNewsletter($request, $isApi = false)
    {
        try {
            $settings = SiteSetting();
            // Update contact's field in newsletter list from profile page in provider and check provider is enable/disable
            if (getImporterYMLSettings(config('app.sendgrid_yml_path')) && !empty($settings['sendgrid_api_key']) && !empty($settings['sendgrid_newsletter_list_id']) &&
                isset($request->type) && $request->type === 'update-newsletter'
            ) {
                $user = auth()->user();
                // Update fields value in newsletter contact list
                $response = $this->sendGrid($settings, $user);

                if ($response->statusCode() == 201 || ($response->statusCode() == 202)) {
                    $user = User::where('id', $user->id)->first();
                    if (!empty($user)) {
                        $user->update(['email_preference' => true]);
                        if (!$isApi) Session::put('allowSendgrid', '1');
                        return;
                    }
                }
                Log::error('Something went wrong while updating user profile to subscribe newsletter.');
                return;
            }

            // Add email in MailChimp newsletter list and check provider is enable/disable
            if (getImporterYMLSettings(config('app.mailchimp_yml_path')) && !empty($settings['mailchimp_api_key']) && !empty($settings['mailchimp_list_id'])) {
                $user = auth()->user();
                $dob = !empty($user->date_of_birth) ? Carbon::parse($user->date_of_birth)->isoFormat('DD/MM') : '';
                $phone = !empty($user->phone) ? $user->phone : '';

                $addressArray = [
                    'addr1' => !empty($user->address) ? $user->address : '',
                    'addr2' => !empty($user->address_2) ? $user->address_2 : '',
                    'city' => !empty($user->stat) ? $user->stat : '',
                    'state' => !empty($user->metaData->where('type', 'state')->pluck('value')->first()) ? optional($user->metaData)->where('type', 'state')->pluck('value')->first() : '',
                    'zip' => !empty($user->postal_code) ? $user->postal_code : '',
                    'country' => !empty($user->country_id) && is_int($user->country_id) ? $user->country->name : ''
                ];

                if (!empty($addressArray['addr1']) && !empty($addressArray['addr2']) && !empty($addressArray['city']) && !empty($addressArray['state']) && !empty($addressArray['zip']) && !empty($addressArray['country'])) {
                    $address = $addressArray;
                } else {
                    $address = '';
                }

                $mailchimp = new MailChimp($settings['mailchimp_api_key']);
                $subscriberHash = MailChimp::subscriberHash($user->email);
                $mailchimp->patch("lists/{$settings['mailchimp_list_id']}/members/{$subscriberHash}?skip_merge_validation=false", [
                    'email_address' => $user->email,
                    'status' => 'subscribed',
                    'merge_fields' => [
                        'FNAME' => !empty($user->first_name) ? $user->first_name : '',
                        'LNAME' => !empty($user->last_name) ? $user->last_name : '',
                        'ADDRESS' => $address,
                        'BIRTHDAY' => $dob,
                        'PHONE' => $phone,
                    ],
                ]);

                if (!$mailchimp->success()) {
                    Log::error('Got error while updating user contact data using MailChimp');
                    return;
                }
                return;
            }

            $this->apiLogErrorMessage(); // Display error in log file if provider not exist
        } catch (Exception $e) {
            Log::error('Got error while sending request for newsletter: ' . $e->getMessage());
        }
    }

    private function sendGrid($settings, $user, $email = null, $nameArray = null)
    {
        $nameArray =  !empty($nameArray) && !is_null($nameArray) ? $nameArray : '';
        $country = !empty($user->country_id) && is_int($user->country_id) && isset($user->country) ? $user->country->name : '';
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

        // Insert custom fields in SendGrid contacts
        foreach ($customFields as $customField) {
            $sg->client->marketing()->field_definitions()->post($customField);
        }

        // Put fields value in contacts
        return $sg->client->marketing()->contacts()->put($requestBody);
    }

    private function mailchimp($settings, $request, $isApi = false, $user, $email = null, $nameArray = null, $type = null)
    {
        $nameArray =  !empty($nameArray) && !is_null($nameArray) ? $nameArray : '';
        $firstName = !empty($user->first_name) ? $user->first_name : (isset($nameArray[0]) ? $nameArray[0] : '');
        $lastName = !empty($user->last_name) ? $user->last_name : (isset($nameArray[1]) ? $nameArray[1] : '');
        $dob = !empty($user->date_of_birth) ? Carbon::parse($user->date_of_birth)->isoFormat('DD/MM') : '';
        $phone = !empty($user->phone) ? $user->phone : '';

        $addressArray = [
            'addr1' => isset($user) && !empty($user->address) ? $user->address : '',
            'addr2' => isset($user) && !empty($user->address_2) ? $user->address_2 : '',
            'city' => isset($user) && !empty($user->stat) ? $user->stat : '',
            'state' => isset($user) && !empty($user->metaData->where('type', 'state')->pluck('value')->first()) ? optional($user->metaData)->where('type', 'state')->pluck('value')->first() : '',
            'zip' => isset($user) && !empty($user->postal_code) ? $user->postal_code : '',
            'country' => isset($user) && !empty($user->country_id) && is_int($user->country_id) ? $user->country->name : ''
        ];

        if (!empty($addressArray['addr1']) && !empty($addressArray['addr2']) && !empty($addressArray['city']) && !empty($addressArray['state']) && !empty($addressArray['zip']) && !empty($addressArray['country'])) {
            $address = $addressArray;
        } else {
            $address = '';
        }

        $mailchimp = new MailChimp($settings['mailchimp_api_key']);

        // Get mailchimp lists
        $list = $mailchimp->get('lists');

        if (!empty($list)) {
            $path = 'lists/' . $settings['mailchimp_list_id'] . '/members/?skip_merge_validation=false';

            // Put fields value in newsletter contact list
            $result = $mailchimp->post($path, [
                'email_address' => !empty($user->email) ? $user->email : (isset($email) ? $email : ''),
                'status' => 'subscribed',
                'merge_fields' => [
                    'FNAME' => $firstName,
                    'LNAME' => $lastName,
                    'ADDRESS' => $address,
                    'BIRTHDAY' => $dob,
                    'PHONE' => $phone,
                ],
            ]);
            if ($result['status'] != 400 && $result['status'] == 'subscribed') {
                if (!empty($user)) {
                    $user = User::where('id', $user->id)->first();
                    if (!empty($user)) {
                        // update user email preference column if user exist
                        $user->update(['email_preference' => true]);
                        // put session to update contact fields if subscribe newsletter
                        if (!$isApi) Session::put('allowSendgrid', '1');

                        if (is_null($type)) return $this->newsletterJoiningMessage($request, $isApi);
                    }
                }
                if (empty($type)) return $this->newsletterJoiningMessage($request, $isApi); // Display success message
            } else if ($result['status'] == 400 && $result['title'] === 'Member Exists') {
                if (empty($type)) return $this->emailExistErrorMessage($request, $isApi);
            }
        }
    }

    private function newsletterJoiningMessage($request, $isApi = false)
    {
        $message = 'Thanks for joining our newsletter.';
        if ($isApi) {
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => $message
            ], JsonResponse::HTTP_OK);
        } else if ($request->ajax() && !$isApi) {
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => $message
            ], JsonResponse::HTTP_OK);
        }

        flash()->success($message);
        return redirect()->back();
    }

    private function unsubscribeNewsletterMessage($request, $isApi = false)
    {
        $message = 'You have successfully unsubscribe newsletter.';
        if ($isApi) {
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => $message
            ], JsonResponse::HTTP_OK);
        } else if ($request->ajax() && !$isApi) {
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => $message
            ], JsonResponse::HTTP_OK);
        }

        flash()->success($message);
        return redirect()->back();
    }

    private function errorMessage($request, $isApi = false)
    {
        $message = 'Something went wrong, try again later.';
        if ($isApi) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $message
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        if ($request->ajax() && !$isApi) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $message
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        flash()->error($message);
        return redirect()->back();
    }

    private function apiErrorMessage($request, $isApi = false)
    {
        $message = 'Internal server error.';
        Log::error('Newsletter provider not configured.');
        if ($isApi) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $message
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        } else if ($request->ajax() && !$isApi) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $message
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        flash()->error($message);
        return redirect()->back();
    }

    private function emailExistErrorMessage($request, $isApi = false)
    {
        $message = 'Email has already subscribed.';
        if ($isApi) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $message
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        if ($request->ajax() && !$isApi) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $message
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        flash()->error($message);
        return redirect()->back();
    }

    private function apiLogErrorMessage()
    {
        Log::error('Newsletter provider not configured.');
    }
}
