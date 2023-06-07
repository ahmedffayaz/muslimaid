<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $title;
    protected $message;
    protected $deviceToken;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title, $message, $deviceToken)
    {
        $this->title = $title;
        $this->message = $message;
        $this->deviceToken = $deviceToken;
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

        $notification = [
            'title' => $title,
            'body' => $message,
        ];

        // dd($notification); // Add this line for debugging

        $firebaseMessage = CloudMessage::fromArray([
            'notification' => $notification,
            'token' => $deviceToken,
        ]);
        
        try {
            Firebase::messaging()->send($firebaseMessage);
        } catch (Exception $e) {
            Log::error('Exception occurred while sending notification: ' . $e->getMessage());
        }
    }
}
