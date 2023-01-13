<?php

namespace App\Http\Controllers\Client;

use Exception;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
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
        $referralBonus = array_key_exists('referral_bonus',SiteSetting()->toArray()) ? SiteSetting()['referral_bonus'] : 0;

        return view('frontend.referral.refer_a_friend',compact('referralBonus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function sendReferralLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'referral_email' => 'required|email',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $validator->getMessageBag()->toArray()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $email_template = EmailTemplate::where('key','referral_link')->first();
            $link = url('').'/register-form?referby='.base64_encode($user =\Auth::user()->id);
            $button = '<a href="'.$link.'" target="_blank"><input type="button" class="btn btn-success" value="Register"></a>';
            $filtered_message  = str_replace(['{{SITE_TITLE}}', '{{SITE_URL}}', '{{BUTTON}}'],[SiteSetting()['website_title'], url('/'), $button],$email_template->message );

            $email_data = array(
                'subject'=>$email_template->subject,
                'email_message'=>$filtered_message,
                'email'=>$request->referral_email
            );

            Mail::send('emails.email_template', $email_data, function ($message) use ($email_data) {
                $message->to($email_data['email'])
                    ->subject($email_data['subject']);
            });

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Referral email sent'
            ], JsonResponse::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'error' => 'Some thing went wrong'
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $exception->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
