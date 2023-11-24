<?php

namespace App\Events;

use App\Models\Forwarding;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ForwardingCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $forwarding;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Forwarding $forwarding)
    {
        $this->forwarding = $forwarding;
    }
}
