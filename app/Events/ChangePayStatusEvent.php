<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangePayStatusEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $data;
    public array $headers;
    public int $gateway_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data, $gateway_id, $headers = [])
    {
        $this->data = $data;
        $this->gateway_id = $gateway_id;
        $this->headers = $headers;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
