<?php

namespace App\Jobs;

use App\Notifications\FirebaseNotification;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\RawMessageFromArray;
use Kreait\Laravel\Firebase\Facades\Firebase;

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
        $deviceToken = $this->deviceToken;
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

        try {

            Firebase::messaging()->send($firebaseMessage);
            Notification::send($userSchema, new FirebaseNotification($notification));
        } catch (Exception $e) {
            Log::error('Exception occurred while sending notification: ' . $e->getMessage());
        }
    }
}
