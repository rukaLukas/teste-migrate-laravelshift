<?php

namespace App\Mail\Record;

use App\Models\Alert;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChildWithoutStudying extends Mailable
{
    use Queueable, SerializesModels;

    protected $record;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Alert $record)
    {
        $this->record = $record;        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $city = $this->record->city;
        $params = [
            'city' => $city,           
        ];                

        return $this->view('email.record.child-wihtout-studying')
            ->with($params)
            ->subject('BAV | Criança fora da escola');
    }
}
