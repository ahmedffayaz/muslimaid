<?php

namespace App\Http\Controllers\Client;

use Exception;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $referralBonus = array_key_exists('referral_bonus', SiteSetting()->toArray()) ? SiteSetting()['referral_bonus'] : 0;

        return view('frontend.referral.refer_a_friend', compact('referralBonus'));
    }

    public function sendReferralLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'referral_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $validator->getMessageBag()->toArray()
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            flash()->error($validator->getMessageBag()->toArray());
            return redirect()->back();
        }

        try {
            $emailTemplate = EmailTemplate::where('key', 'referral_link')->first();
            $link = url('') . '/register-form?referby=' . encrypt(auth()->user()->id);
            $filteredMessage  = str_replace(['{{SITE_TITLE}}', '{{SITE_URL}}', '{{LINK}}'], [SiteSetting()['website_title'], url('/'), $link], $emailTemplate->message);

            $emailData = array(
                'subject' => $emailTemplate->subject,
                'email_message' => $filteredMessage,
                'email' => $request->referral_email
            );

            SendEmail::dispatch($emailData);

            if ($request->ajax()) {
                return response()->json([
                    'status' => JsonResponse::HTTP_OK,
                    'message' => 'Referral email sent'
                ], JsonResponse::HTTP_OK);
            }

            flash()->success('Referral email sent');
            return redirect()->back();
        } catch (ModelNotFoundException $exception) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => JsonResponse::HTTP_NOT_FOUND,
                    'error' => 'Some thing went wrong, try again'
                ], JsonResponse::HTTP_NOT_FOUND);
            }

            flash()->error('Some thing went wrong, try again');
        } catch (Exception $exception) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'Some thing went wrong, try again'
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            flash()->error('Some thing went wrong, try again');
            return redirect()->back();
        }
    }
}
