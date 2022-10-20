<?php

namespace App\Listeners;

use App\Events\ChangePayStatusEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;

class ChangePayStatusListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\ChangePayStatusEvent $event
     * @return void
     */
    public function handle(ChangePayStatusEvent $event)
    {
        $default_headers = [
            'Content-Type' => 'application/json',
            'Content-Length' => strlen(json_encode($event->data)),
            'Cookie' => 'XDEBUG_SESSION=XDEBUG_ECLIPSE;'
        ];
        $headers = array_merge($default_headers, $event->headers);
        $response = Http::withHeaders($headers)->post(env('APP_URL') . '/api/v1/operation_history/' . $event->gateway_id, $event->data);
    }
}
