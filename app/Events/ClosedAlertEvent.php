<?php

namespace App\Events;

use App\Models\AlertStep;
use App\Models\ClosedAlert;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClosedAlertEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $closedAlert;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AlertStep $closedAlert)
    {
        $this->closedAlert = $closedAlert;
    }
}
