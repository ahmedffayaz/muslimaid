<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use Newsletter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class NewsletterController extends Controller
{
    public function index()
    {
        Newsletter::subscribe('rincewind@discworld.com');
    }

    public function store(Request $request)
    {
        try {
            $settings = SiteSetting();

            if (empty($settings['sendgrid_newsletter_list_id']) || empty($settings['sendgrid_api_key'])) {
                if ($request->ajax()) {
                    return response()->json([
                        'message' => 'Default list settings are not added! Try again after adding keys',
                        'color' => 'red',
                    ]);
                }
                return redirect()->back()->with(['error' => 'Default list settings are not added!']);
            }

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
                if ($request->ajax()) {
                    if (isset($request->userId)) {
                        $user = User::where('id', $request->userId)->first();
                        if (isset($user)) {
                            $user->email_preference = 1;
                            $user->save();
                        }
                    }
                    return response()->json([
                        'message' => 'You are subscribed successfully',
                        'color' => 'green',
                    ]);
                }
                return redirect()->back()->with(['success' => 'Thank you for subscribing to out newsletter']);
            }

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Something went wrong!',
                    'color' => 'red',
                ]);
            }
            return redirect()->back()->with(['error' => 'Something went wrong!']);
        } catch (Exception $ex) {
            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Something went wrong!',
                    'color' => 'red',
                ]);
            }
            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }
    }
}
