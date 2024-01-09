<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\SubscribeNewsletter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscribeNewsletterController extends Controller
{
    use SubscribeNewsletter;

    public function index(Request $request)
    {
        if (isset($request->type) && $request->type === 'subscribe-newsletter')
            return $this->subscribeNewsletter($request, true);

        if (isset($request->type) && $request->type === 'profile-subscribe-newsletter')
            return $this->profileSubscribeNewsletter($request, true);

        if (isset($request->type) && $request->type === 'profile-unsubscribe-newsletter')
            return $this->unsubscribeNewsletter($request, true);

        return response()->json([
            'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            'error' => 'Something went wrong, try again later'
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
