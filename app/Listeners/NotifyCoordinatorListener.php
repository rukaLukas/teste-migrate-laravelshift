<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Occupation;
use App\Mail\Alert\ClosedAlert;
use App\Events\ClosedAlertEvent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyCoordinatorListener
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
    public function handle(ClosedAlertEvent $event)
    {       
        $closedAlert = $event->closedAlert;
        $detailsClosedAlert = [
            'city' => $closedAlert->user->county->name,
            'who' => $closedAlert->user->name,
            'when' => Carbon::parse($closedAlert->created_at)->format('d/m/Y'),
            'reason' => $closedAlert->reasonCloseAlert->description,
            'comment' => $closedAlert->comments,
            'link' => url('') . '/login',
        ];

        $coordinator = User::Where('occupation_id', Occupation::COORDENADOR_OPERACIONAL_SAUDE)            
            ->where('county_id', $closedAlert->user()->first()->county_id)
            ->first();

        if ($coordinator) {
            Mail::to($coordinator)->send(
                new ClosedAlert($detailsClosedAlert)
            );
        }         
    }
}
