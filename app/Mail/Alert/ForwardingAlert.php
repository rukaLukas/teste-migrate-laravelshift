<?php

namespace App\Mail\Alert;

use App\Models\Alert;
use App\Models\AlertStep;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForwardingAlert extends Mailable
{
    use Queueable, SerializesModels;

    protected $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $details)
    {
        $this->details = $details;        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {           
        return $this->view('email.alert.forwarding')
            ->with($this->details)
            ->subject('BAV | Encaminhamento de alerta');
    }
}
