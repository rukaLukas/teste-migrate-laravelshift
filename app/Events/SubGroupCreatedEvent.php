<?php

namespace App\Events;

use App\Models\SubGroup;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubGroupCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subGroup;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(SubGroup $subGroup)
    {
        $this->subGroup = $subGroup;
    }
}
