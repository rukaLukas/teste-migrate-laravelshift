<?php

namespace App\Listeners;

use App\Models\Alert;
use App\Events\RecordCreated;
use Illuminate\Support\Facades\Mail;
use App\Mail\Record\ChildWithoutStudying;
use App\Models\GovernmentOffice;

class NotifyChildWithoutStudyingListener
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
    public function handle(RecordCreated $event)
    {        
        $record = $event->record;
        if ($record->bae == Alert::FORA_DA_ESCOLA) {                                    
            $educationGovernmentOffice = GovernmentOffice::where('county_id', $record->county_id)
                ->where('type', GovernmentOffice::SECRETARIA_EDUCACAO)
                ->first()
                ->email;   
            
            if ($educationGovernmentOffice) {
                Mail::to($educationGovernmentOffice)->send(
                    new ChildWithoutStudying($record)
                );
            }
        }      
    }
}
