<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\FailedLogin;

class RecordFailedLogin implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  Failed  $event
     * @return void
     */
    public function handle(Failed $event)
    {
        // Store the failed login attempt in the failed_logins table
        FailedLogin::create([
            'user_id' => $event->user->id,
            'ip_address' => request()->ip(),
        ]);
    }
}