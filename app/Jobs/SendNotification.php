<?php

namespace App\Jobs;

use Exception;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\FirebaseNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $url;
    protected $title;
    protected $message;
    protected $deviceToken;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title, $message, $deviceToken, $url, $user)
    {
        $this->title = $title;
        $this->message = $message;
        $this->deviceToken = $deviceToken;
        $this->url = $url;
        $this->user = $user;
    }



    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $title = $this->title;
        $message = $this->message;
        $deviceToken = 'dLQUx6juUaMCayv1AHppnF:APA91bFJHVQLsye7Fh1GmBZZ8kSakTOI2nXrzQPwRY4jvRHZY0yJwiTITz23vpRTTuNWnm-9Vnk-IJwO24cGbgRQfOT63c5sbge27a1vP_wLL5RxJjPQ5etH2NkL4g1ksBhYX1EGMTHb';
        $url =  $this->url;
        $userSchema = $this->user;

        $notification = [
            'title' => $title,
            'body' => $message,
            'url' => $url,
        ];
        $firebaseMessage = CloudMessage::fromArray([
            'notification' => $notification,
            'token' => $deviceToken,
        ]);

        // Log::info("Firebase information", ['object' => $firebaseMessage]);

        try {

            Firebase::messaging()->send($firebaseMessage);
            Notification::send($userSchema, new FirebaseNotification($notification));
        } catch (Exception $e) {
            Log::error('Exception occurred while sending notification: ' . $e->getMessage());
        }
    }
}
