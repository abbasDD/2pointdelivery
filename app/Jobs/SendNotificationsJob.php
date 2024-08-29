<?php

namespace App\Jobs;

use App\Http\Controllers\FcmController;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\FcmService;

class SendNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $users;
    protected $title;
    protected $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($users, $title, $body)
    {
        $this->users = $users;
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(FcmController $fcm)
    {
        foreach ($this->users as $user) {
            $fcm->sendPushNotificationToUser($user->id, $this->title, $this->body);
        }
    }
}
