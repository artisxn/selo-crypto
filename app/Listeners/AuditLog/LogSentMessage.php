<?php

namespace App\Listeners\AuditLog;

use Illuminate\Support\Facades\Log;

class LogSentMessage
{
    /**
     * Handle the event.
     *
     * @param  Illuminate\Mail\Events\MessageSending $event
     * @return void
     */
    public function handle($event)
    {
        Log::info($event->message);
    }
}