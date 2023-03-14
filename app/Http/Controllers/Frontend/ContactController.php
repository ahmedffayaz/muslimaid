<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\Page;
use App\Models\ContactForm;
use Illuminate\Http\Request;
use App\Jobs\SendEmailToUser;
use App\Jobs\SendEmailToAdmin;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'contact')->first();
        if (empty($page)) abort(404);

        return view('frontend.contact.index', compact('page'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'g-recaptcha-response' => 'required|captcha',
        ]);

        try {
            ContactForm::create($request->all());

            $adminEmailTemplateKey = 'admin_new_contact';
            $userEmailTemplateKey = 'user_new_contact';

            $data = $request->all();

            SendEmailToAdmin::dispatch($adminEmailTemplateKey, $data, $filterMessageVariables = [], $requestFilteredMessage = []);
            SendEmailToUser::dispatch($userEmailTemplateKey, $data, $filterMessageVariables = [], $requestFilteredMessage = []);

            return redirect()->back()->with('success', 'Thanks for contact us.');
        } catch (Exception $exception) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
