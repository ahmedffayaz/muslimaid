<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Traits\SubscribeNewsletter;
use App\Http\Controllers\Controller;

class SubscribeNewsletterController extends Controller
{
    use SubscribeNewsletter;

    public function index (Request $request)
    {
        if ($request->has('type') && $request->input('type') === 'subscribe-newsletter')
            return $this->subscribeNewsletter($request);

        if ($request->has('type') && $request->input('type') === 'profile-subscribe-newsletter')
            return $this->profileSubscribeNewsletter($request);

        if ($request->has('type') && $request->input('type') === 'profile-unsubscribe-newsletter')
            return $this->unsubscribeNewsletter($request);

        if ($request->has('type') && $request->input('type') === 'update-newsletter')
            return $this->updateNewsletter($request);
    }
}
