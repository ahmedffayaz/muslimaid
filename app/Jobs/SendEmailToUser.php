<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmailToUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $emailTemplateKey;
    protected $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emailTemplateKey, $details)
    {
        $this->emailTemplateKey = $emailTemplateKey;
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $templateKey = $this->emailTemplateKey;
        $details = $this->details;

        $emailTemplate = emailTemplate($templateKey, $details);
        $data = array(
            'name' =>  $details['name'],
            'email' => $details['email'],
            'message' => $details['message'],
            'email_message' => $emailTemplate['message'],
            'subject' => $emailTemplate['subject']
        );

        // Send to user
        Mail::send('emails.email_template', $data, function ($message) use ($data) {
            $message->to($data['email'], $data['name'])
                ->subject($data['subject']);
        });
    }
}
