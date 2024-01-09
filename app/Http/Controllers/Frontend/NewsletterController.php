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
}
