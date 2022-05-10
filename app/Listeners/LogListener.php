<?php

namespace App\Listeners;

use App\Models\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Log $event)
    {
        $this->handle($event);
    }

    /**
     * Handle the event.
     *
     * @param Log $event
     * @return void
     */
    public function handle(Log $event)
    {
        Log::create([
            'user_id' => $event->user_id,
            'action' => $event->action,
            'status' => $event->status,
            'old_data' => $event->old_data,
            'new_data' => $event->new_data,
            'ip_address' => $event->ip_address,
            'user_agent' => $event->user_agent,
        ]);
    }
}
