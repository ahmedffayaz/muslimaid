<?php

namespace App\Notifications;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $token;

    public function __construct($data)
    {
        $this->token = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $emailTemplate = EmailTemplate::where('key', 'forgot_password')->first();

        $email = $notifiable->getEmailForPasswordReset();

        $user = [
            'name' => $notifiable->first_name . ' ' . $notifiable->last_name,
            'email' => $email,
            'message' => null,
            'subject' => null
        ];

        $url = url(config('app.url') . route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $button = '<a class="btn btn-primary" href="' . $url . '">Reset</a>';

        $filteredMessage  = str_replace(['{{SITE_TITLE}}', '{{SITE_URL}}', '{{NAME}}', '{{EMAIL}}', '{{SUBJECT}}', '{{MESSAGE}}', '{{BUTTON}}'],
                [SiteSetting()['website_title'], url('/'), $user['name'], $email, $user['subject'], $user['message'], $button],
                $emailTemplate->message);

        return (new MailMessage)->view('emails.password_reset', ['email_message' => $filteredMessage]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
