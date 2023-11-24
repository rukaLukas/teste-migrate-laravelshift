<?php

namespace App\Listeners;

use App\Models\Alert;
use App\Events\AlertCreated;
use App\Events\RecordCreated;
use App\Models\GovernmentOffice;
use Illuminate\Support\Facades\Mail;
use App\Mail\Alert\GovOfficeDelayVaccine;
use App\Mail\Record\ChildWithoutStudying;

class NotifyGovOfficesDelayVaccinesListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(AlertCreated $event)
    {        
        // d("step 2");
        $alert = $event->alert;

        // TODO: iterate over reason delay vaccines, and find the government office from this county related to this reason
        // dd($alert->reasonDelayVaccines);
        foreach ($alert->reasonDelayVaccines as $reasonDelayVaccine) {
            // get the government office email            
            foreach ($reasonDelayVaccine->governmentOffices as $governmentOffice) {
                // dump("step 3", $reasonDelayVaccine->description, $governmentOffice->name, $governmentOffice->email, $governmentOffice->county->name);
                Mail::to($governmentOffice->email)->send(
                    new GovOfficeDelayVaccine($reasonDelayVaccine->description, $governmentOffice)
                );
            }            
        }    
    }
}
