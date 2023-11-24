<?php

namespace App\Mail\Alert;

use App\Models\Alert;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\GovernmentOffice;
use Illuminate\Queue\SerializesModels;

class GovOfficeDelayVaccine extends Mailable
{
    use Queueable, SerializesModels;

    protected $reasonDelayVaccine;

    protected $governmentOffice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    // public function __construct(Alert $alert)
    public function __construct(string $reasonDelayVaccine, GovernmentOffice $governmentOffice)
    {
        // $this->alert = $alert;
        $this->reasonDelayVaccine = $reasonDelayVaccine;
        $this->governmentOffice = $governmentOffice;
    }    

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // dump("step 4");
        // dd($this->alert);
        // $city = $this->alert->city;
        // $reason = $this->alert->reasonDelayVaccines;
        $params = [
            'city' => $this->governmentOffice->county->name,
            'reason' => $this->reasonDelayVaccine,           
        ];                
        
        return $this->view('email.alert.gov-office-reason-delay-vaccine')
            ->with($params)
            ->subject('BAV | Criança com atraso vacinal');
    }
}
