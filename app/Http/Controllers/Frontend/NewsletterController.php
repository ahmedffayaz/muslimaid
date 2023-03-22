<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use Newsletter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class NewsletterController extends Controller
{
    public function index()
    {
        Newsletter::subscribe('rincewind@discworld.com');
    }

    public function store(Request $request)
    {
        try {
            if (empty($settings['sendgrid_newsletter_list_id']) || empty($settings['sendgrid_api_key'])) {
                return redirect()->back()->with(['error' => 'Default list settings are not added!']);
            }

            $settings = SiteSetting();
            $email = $request->input('email');
            $nameArray = explode(' ', $request->input('name'));

            $requestBody = [
                'list_ids' => [
                    isset($settings['sendgrid_newsletter_list_id']) ? $settings['sendgrid_newsletter_list_id'] : "",
                ],
                'contacts' => [
                    [
                        'email' => $email,
                        'first_name' => isset($nameArray[0]) ? $nameArray[0] : '',
                        'last_name' => isset($nameArray[1]) ? $nameArray[1] : '',
                    ]
                ]
            ];

            $apiKey = isset($settings['sendgrid_api_key']) ? $settings['sendgrid_api_key'] : "";
            $sg = new \SendGrid($apiKey);


            $response = $sg->client->marketing()->contacts()->put($requestBody);
            Newsletter::subscribe($request->input('email'));

            if ($response->statusCode() == 201 || $response->statusCode() == 202) {
                return redirect()->back()->with(['success' => 'Thank you for subscribing to out newsletter']);
            }

            return redirect()->back()->with(['error' => 'Something went wrong!']);
        } catch (Exception $ex) {
            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }
    }
}
