<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\emailTemp;
use App\Models\UserVerify;
use App\Models\EmailTemplate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
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
        $verification_email_temp = EmailTemplate::where('key','email_verification')->first();
        $token = Str::random(64);

        UserVerify::create([
            'user_id' => $user->id,
            'token' => $token
            ]);
        $email = new emailTemp();

       $appUrl = env('APP_URL');
        $link = $appUrl.'/account/verify/'.$token;
        $button = '<a href="'.$link.'" target="_blank"><input type="button" class="btn btn-success" value="Verify"></a>';
        $filtered_message  = str_replace(['{{SITE_TITLE}}', '{{SITE_URL}}', '{{BUTTON}}'],[SiteSetting()['website_title'], url('/'), $button],$verification_email_temp->message );
        $data = array(
            'email'=> $user->email,
            'email_message'=>$filtered_message,
            'subject'=>$verification_email_temp->subject
        );
        Mail::send('frontend.emails.email_template', $data, function ($message) use ($data) {
            $message->to($data['email'])
                ->subject($data['subject']);
        });
    }
}
