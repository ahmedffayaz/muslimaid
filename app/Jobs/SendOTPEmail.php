<?php

namespace App\Jobs;

use App\Models\UserVerify;
use Illuminate\Support\Str;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendOTPEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $user = $this->details;
        $verificationEmailTemplate = EmailTemplate::where('key', 'mobile_otp_verification')->first();
        if (isset($verificationEmailTemplate)) {
            $token = Str::random(64);
            UserVerify::create([
                'user_id' => $user->id,
                'token' => $token
            ]);

            $appUrl = env('APP_URL');
            $link = $appUrl . '/account/verify/' . $token;
            $filteredMessage  = str_replace(['{{SITE_TITLE}}', '{{SITE_URL}}', '{{LINK}}', '{{OTP}}'], [SiteSetting()['website_title'], url('/'), $link, $user->otp], $verificationEmailTemplate->message);
            $data = array(
                'email' => $user->email,
                'email_message' => $filteredMessage,
                'subject' => $verificationEmailTemplate->subject,
                'key' => $verificationEmailTemplate->key
            );
            Mail::send('frontend.emails.frontend.email_template', $data, function ($message) use ($data) {
                $message->to($data['email'])
                    ->subject($data['subject']);
            });
        }
    }
}
