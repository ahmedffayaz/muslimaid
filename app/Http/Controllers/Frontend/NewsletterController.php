<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Spatie\Newsletter\Newsletter;
use App\Http\Controllers\Controller;

class NewsletterController extends Controller
{
    public function index()
    {
        Newsletter::subscribe('rincewind@discworld.com');
    }

    public function store(Request $request)
    {
        if (Newsletter::subscribe($request->input('email'))) {
            return 'Thank you for subscribing to out newsletter';
        }

        return 'Something went wrong!';
    }
}
