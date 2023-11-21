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
use Illuminate\Support\Facades\Notification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\RawMessageFromArray;

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
        $devices = [$this->deviceToken];
        $url =  $this->url;
        $userSchema = $this->user;

        $notification = [
            'title' => $title,
            'body' => $message,
            'url' => $url
        ];

        try {
            if(!empty($devices)) {
                $firebase_path = base_path('firebase-credentials.json');
                $firebase = (new Factory)->withServiceAccount($firebase_path);
                $messaging = $firebase->createMessaging();
                $devices_chunks = array_chunk($devices, 90);
                $count = 0;

                foreach ($devices_chunks as $devices) {
                    $message = new RawMessageFromArray([
                        'notification' => $notification,
                        'webpush' => [
                            // https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages#webpushconfig
                            'notification' => $notification
                        ],
                        'fcm_options' => [
                            // https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages#fcmoptions
                            'analytics_label' => 'some-analytics-label'
                        ]
                    ]);

                    $result = $messaging->sendMulticast($message, $devices);
                    if ($result->successes()->count()) {
                        Notification::send($userSchema, new FirebaseNotification($notification));
                    }

                    if ($result->hasFailures()) {
                        foreach ($result->failures()->getItems() as $failure) {
                            Log::error($failure->error()->getMessage().PHP_EOL);
                        }
                    }
                    $count += $result->count();
                }
                return $count;
            }
            return null;
        } catch (Exception $e) {
            Log::error('Exception occurred while sending notification: ' . $e->getMessage());
        }
    }
}
