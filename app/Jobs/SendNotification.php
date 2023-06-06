<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Notification;
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
    
        $notification = Notification::create()
            ->setTitle($title)
            ->setBody($message);
    
        $message = CloudMessage::withTarget('token', $deviceToken)
            ->setNotification($notification);
    }
}
