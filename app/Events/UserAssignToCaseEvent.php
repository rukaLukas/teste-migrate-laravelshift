<?php

namespace App\Events;

use App\Models\CaseStep;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserAssignToCaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $caseStep;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CaseStep $caseStep)
    {
        $this->caseStep = $caseStep;
    }
}
