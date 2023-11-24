<?php

namespace App\Mail\Alert;

use App\Models\Alert;
use App\Models\AlertStep;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClosedAlert extends Mailable
{
    use Queueable, SerializesModels;

    protected $closedAlert;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $detailsClosedAlert)
    {
        $this->closedAlert = $detailsClosedAlert;        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {           
        return $this->view('email.alert.closed')
            ->with($this->closedAlert)
            ->subject('BAV | Alerta Encerrado');
    }
}
