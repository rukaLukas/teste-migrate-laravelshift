<?php

namespace App\Events;

use App\Models\Alert;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class RecordCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $record;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Alert $record)
    {
        $this->record = $record;
    }
}
