<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Spatie\Newsletter\Newsletter;
use App\Http\Controllers\Controller;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $sub = Newsletter::subscribe('rincewind@discworld.com');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Newsletter::subscribe($request->input('email'))){
            return $message = 'Thank you for subscribing to out newsletter';
        }else{
            return $message = 'Something went wrong!';

        }

    }
}
