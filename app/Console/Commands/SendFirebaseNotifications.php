<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Jobs\SendNotification;
use Illuminate\Console\Command;
use App\Jobs\FirebaseNotificationJob;
use Illuminate\Support\Facades\Notification;

class SendFirebaseNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firebase:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send all pending firebase notifications.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $allUsers = User::where('status', 'active')->with('devices')->get();
            $notifications = Notification::where('status', 'active')->with(['user', 'user.devices'])->get();
        
            foreach ($notifications as $notification) {
                SendNotification::dispatch($notification, $allUsers);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        
    }
}
