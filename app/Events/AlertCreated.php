<?php

namespace App\Events;

use App\Models\Alert;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class AlertCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $alert;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Alert $alert)
    {        
        // dump("step 1");
        $this->alert = $alert;
    }
}
