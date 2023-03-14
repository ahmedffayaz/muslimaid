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
        $settings = SiteSetting();
        $email = $request->input('email');
        $name_arr = explode(' ', $request->input('name'));
        $requestBody = [
            'list_ids' => [
                isset($settings['sendgrid_newsletter_list_id']) ? $settings['sendgrid_newsletter_list_id'] : "",
            ],
            'contacts' => [
                [
                    'email' => $email,
                    'first_name' => isset($name_arr[0]) ? $name_arr[0] : '',
                    'last_name' => isset($name_arr[1]) ? $name_arr[1] : '',
                ]
            ]
        ];
        $apiKey = isset($settings['sendgrid_api_key']) ? $settings['sendgrid_api_key'] : "";
        $sg = new \SendGrid($apiKey);
        try {
            $response = $sg->client->marketing()->contacts()->put($requestBody);
            $newsletter = Newsletter::subscribe($request->input('email'));
            if ($response->statusCode() == 201 || $response->statusCode() == 202) {
                return redirect()->back()->with(['success' => 'Thank you for subscribing to out newsletter'],);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong!']);
            }
        } catch (Exception $ex) {
            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }
    }
}
