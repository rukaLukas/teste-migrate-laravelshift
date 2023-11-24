<?php

namespace App\Listeners;

use App\Events\ForwardingCreated;
use App\Mail\Alert\ForwardingAlert;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\View\View;

class NotifyGovernmentOfficeListener
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
    public function handle(ForwardingCreated $event)
    {
        $forwarding = $event->forwarding;
        $alert = $forwarding->record->alerts()->latest('id')->first();        
        $details = [
            'name' => $alert->name,
            'description' => $forwarding->description,
            'cpf' => $alert->cpf,
            'suscard' => $alert->suscard,
        ];        

        if (!is_null($forwarding->email)) {
            Mail::to($forwarding->email)->send(
                new ForwardingAlert($details)
            );
        }               
    }
}
